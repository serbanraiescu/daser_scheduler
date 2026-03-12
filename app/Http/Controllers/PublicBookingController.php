<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    public function index()
    {
        $services = \App\Models\Service::where('is_active', true)->get();
        return view('bookings.index', compact('services'));
    }

    public function selectEmployee(Request $request)
    {
        $serviceId = $request->input('service_id');
        $service = \App\Models\Service::findOrFail($serviceId);
        $employees = $service->employees;
        
        return view('bookings.employee', compact('service', 'employees'));
    }

    public function selectSlots(Request $request)
    {
        $serviceId = $request->input('service_id');
        $employeeId = $request->input('employee_id');
        $date = $request->input('date', now()->format('Y-m-d'));
        
        $service = \App\Models\Service::findOrFail($serviceId);
        
        if ($employeeId) {
            $employees = [\App\Models\User::findOrFail($employeeId)];
        } else {
            $employees = $service->employees;
        }

        $availableSlots = [];
        foreach ($employees as $employee) {
            $slots = $this->generateSlots($employee, $service, $date);
            if (!empty($slots)) {
                $availableSlots[] = [
                    'employee' => $employee,
                    'slots' => $slots
                ];
            }
        }

        return view('bookings.slots', compact('service', 'availableSlots', 'date', 'employeeId'));
    }

    private function generateSlots($employee, $service, $date)
    {
        $duration = $service->duration;
        $start = \Carbon\Carbon::parse($date . ' 09:00:00');
        $end = \Carbon\Carbon::parse($date . ' 18:00:00');
        
        $existingBookings = \App\Models\Booking::where('user_id', $employee->id)
            ->whereDate('start_time', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        $slots = [];
        $current = $start->copy();
        
        while ($current->copy()->addMinutes($duration)->lte($end)) {
            $slotStart = $current->copy();
            $slotEnd = $current->copy()->addMinutes($duration);
            
            $isAvailable = true;
            foreach ($existingBookings as $booking) {
                if ($slotStart->lt($booking->end_time) && $slotEnd->gt($booking->start_time)) {
                    $isAvailable = false;
                    break;
                }
            }
            
            if ($isAvailable && $slotStart->gt(now())) {
                $slots[] = $slotStart->format('H:i');
            }
            
            $current->addMinutes(30); // 30 min increments for slot starting points
        }
        
        return $slots;
    }

    public function details(Request $request)
    {
        $serviceId = $request->input('service_id');
        $employeeId = $request->input('employee_id');
        $date = $request->input('date');
        $time = $request->input('time');

        $service = \App\Models\Service::findOrFail($serviceId);
        $employee = \App\Models\User::findOrFail($employeeId);

        return view('bookings.details', compact('service', 'employee', 'date', 'time'));
    }

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time' => 'required',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
        ]);

        // Find or create guest client
        $client = \App\Models\Client::firstOrCreate(
            ['phone' => $validated['phone']],
            ['name' => $validated['name'], 'email' => $validated['email']]
        );

        $service = \App\Models\Service::findOrFail($validated['service_id']);
        $startTime = \Carbon\Carbon::parse($validated['date'] . ' ' . $validated['time']);
        $endTime = $startTime->copy()->addMinutes($service->duration);

        $booking = \App\Models\Booking::create([
            'client_id' => $client->id,
            'user_id' => $validated['employee_id'],
            'service_id' => $service->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price_at_booking' => $service->price,
            'management_link' => \Illuminate\Support\Str::uuid(),
            'status' => 'confirmed',
        ]);

        return redirect()->route('bookings.show', $booking->management_link)->with('success', 'Booking confirmed!');
    }

    public function show($link)
    {
        $booking = \App\Models\Booking::where('management_link', $link)->with(['client', 'service', 'employee'])->firstOrFail();
        return view('bookings.show', compact('booking'));
    }

    public function cancel($link)
    {
        $booking = \App\Models\Booking::where('management_link', $link)->firstOrFail();
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Booking cancelled.');
    }
}
