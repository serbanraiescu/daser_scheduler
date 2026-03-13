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

        return view('employee.dashboard', compact('bookings', 'date', 'stats'));
    }

    public function create()
    {
        $services = \App\Models\Service::where('active', true)->get();
        return view('employee.bookings.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
        ]);

        $service = \App\Models\Service::findOrFail($validated['service_id']);
        $startTime = \Carbon\Carbon::parse($validated['date'] . ' ' . $validated['time']);
        $endTime = $startTime->copy()->addMinutes($service->duration_minutes);

        if (\App\Models\Booking::isOverlapping(auth()->id(), $startTime, $endTime)) {
            return back()->withErrors(['time' => 'Intervalul este deja ocupat.'])->withInput();
        }

        $client = \App\Models\Client::firstOrCreate(
            ['phone' => $validated['client_phone']],
            ['name' => $validated['client_name']]
        );

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
}
