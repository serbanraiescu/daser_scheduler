<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $employee = auth()->user();
        $date = request('date', now()->toDateString());
        
        $bookings = \App\Models\Booking::where('employee_id', $employee->id)
            ->whereDate('date', $date)
            ->with(['client', 'service'])
            ->orderBy('start_time')
            ->get();

        $stats = [
            'today' => \App\Models\Booking::where('employee_id', $employee->id)->whereDate('date', now()->toDateString())->count(),
            'upcoming' => \App\Models\Booking::where('employee_id', $employee->id)->where('date', '>', now()->toDateString())->count(),
        ];

        // Generate 14-day calendar
        $calendar = [];
        for ($i = 0; $i < 14; $i++) {
            $d = now()->addDays($i);
            $calendar[] = [
                'date' => $d->toDateString(),
                'dayName' => $d->translatedFormat('D'), // short day name
                'dayNumber' => $d->format('d'),
            ];
        }

        return view('employee.dashboard', compact('bookings', 'date', 'stats', 'calendar'));
    }

    public function create()
    {
        $services = \App\Models\Service::where('active', true)->get();
        $employee = auth()->user()->employee;
        $date = request('date', now()->toDateString());
        $time = request('time', '09:00'); 
        $search = request('search', ''); // New parameter for voice search pre-fill
        $carbonDate = \Carbon\Carbon::parse($date);
        
        $minHour = 0;
        $maxHour = 23;

        if ($employee) {
            // Check specific schedule override
            $schedule = \App\Models\EmployeeSchedule::where('employee_id', $employee->id)
                ->whereDate('date', $date)
                ->first();

            if (!$schedule || $schedule->is_off) {
                // Check standard schedule
                $schedule = \App\Models\StandardSchedule::where('employee_id', $employee->id)
                    ->where('day_of_week', $carbonDate->dayOfWeek)
                    ->first();
            }

            if ($schedule && !$schedule->is_off) {
                $startHour = (int) explode(':', $schedule->start_time)[0];
                $endHour = (int) explode(':', $schedule->end_time)[0];
                
                $minHour = max(0, $startHour - 2);
                $maxHour = min(23, $endHour + 2);
            }
        }

        return view('employee.bookings.create', compact('services', 'minHour', 'maxHour', 'time', 'date', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'client_email' => 'nullable|email|max:255',
        ]);

        $service = \App\Models\Service::findOrFail($validated['service_id']);
        $startTime = \Carbon\Carbon::parse($validated['date'] . ' ' . $validated['time']);
        $endTime = $startTime->copy()->addMinutes((int) $service->duration_minutes);

        if (\App\Models\Booking::isOverlapping(auth()->id(), $startTime, $endTime)) {
            return back()->withErrors(['time' => 'Intervalul este deja ocupat.'])->withInput();
        }

        $client = \App\Models\Client::firstOrCreate(
            ['phone' => $validated['client_phone']],
            ['name' => $validated['client_name'], 'email' => $validated['client_email']]
        );

        // Update email if it was previously null but provided now
        if ($validated['client_email'] && !$client->email) {
            $client->update(['email' => $validated['client_email']]);
        }


        \App\Models\Booking::create([
            'client_id' => $client->id,
            'employee_id' => auth()->id(),
            'service_id' => $service->id,
            'date' => $validated['date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'confirmed',
            'manage_token' => \Illuminate\Support\Str::random(16),
            'notes' => 'Adăugat manual de angajat',
        ]);

        return redirect()->route('employee.dashboard')->with('success', 'Programarea a fost adăugată cu succes.');
    }

    public function searchClients(Request $request)
    {
        $query = $request->input('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $clients = \App\Models\Client::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(5)
            ->get(['name', 'phone', 'email']);

        return response()->json($clients);
    }
}
