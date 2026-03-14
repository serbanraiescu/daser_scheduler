<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\BlockedSlot;
use App\Models\EmployeeSchedule;
use App\Models\StandardSchedule;

class CalendarController extends Controller
{
    public function index()
    {
        return view('employee.calendar.index');
    }

    public function events(Request $request)
    {
        $start = tap(Carbon::parse($request->start), fn($c) => $c->setTimezone('Europe/Bucharest'))->toDateString();
        $end = tap(Carbon::parse($request->end), fn($c) => $c->setTimezone('Europe/Bucharest'))->toDateString();
        
        $employeeModel = auth()->user()->employee;
        if (!$employeeModel) {
            return response()->json([]);
        }

        $employeeId = $employeeModel->id;
        $userId = auth()->id();

        // 1. Fetch Bookings (Occupied)
        $bookings = Booking::where('employee_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->with(['client', 'service'])
            ->get();

        $events = [];

        foreach ($bookings as $booking) {
            $events[] = [
                'id' => 'booking_' . $booking->id,
                'title' => ($booking->client->name ?? 'Client') . ' : ' . ($booking->service->name ?? 'Serviciu'),
                'start' => Carbon::parse($booking->start_time)->format('Y-m-d\TH:i:s'),
                'end' => Carbon::parse($booking->end_time)->format('Y-m-d\TH:i:s'),
                'color' => '#4f46e5', // Indigo 600
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'phone' => $booking->client->phone ?? '',
                    'service' => $booking->service->name ?? '',
                ]
            ];
        }

        // 2. Fetch Business Hours (Available) - Background Events
        $currentDate = Carbon::parse($start);
        $endDateObj = Carbon::parse($end);

        while ($currentDate <= $endDateObj) {
            $date = $currentDate->toDateString();

            // Check if blocked entirely
            $isDayBlocked = BlockedSlot::where('employee_id', $employeeId)
                ->whereDate('date', $date)
                ->where('start_time', '<=', '00:00:00')
                ->where('end_time', '>=', '23:59:59')
                ->exists();

            if ($isDayBlocked) {
                // Do not emit working hours background event
                // Instead, emit an all-day block.
            } else {
                // Check Active Schedule
                $schedule = EmployeeSchedule::where('employee_id', $employeeId)
                    ->whereDate('date', $date)
                    ->first();

                $dayStartStr = null;
                $dayEndStr = null;
                $breakStartStr = null;
                $breakEndStr = null;

                if (!$schedule || $schedule->is_off) {
                    $dayOfWeek = $currentDate->dayOfWeek;
                    $standard = StandardSchedule::where('employee_id', $employeeId)
                        ->where('day_of_week', $dayOfWeek)
                        ->first();

                    if ($standard && !$standard->is_off) {
                        $dayStartStr = $standard->start_time;
                        $dayEndStr = $standard->end_time;
                        $breakStartStr = $standard->break_start;
                        $breakEndStr = $standard->break_end;
                    }
                } else {
                    if (!$schedule->is_off) {
                        $dayStartStr = $schedule->start_time;
                        $dayEndStr = $schedule->end_time;
                        $breakStartStr = $schedule->break_start;
                        $breakEndStr = $schedule->break_end;
                    }
                }

                if ($dayStartStr && $dayEndStr) {
                    // We emit background events for the available slots.
                    // If there's a break, emit two events: before break, after break.
                    
                    if ($breakStartStr && $breakEndStr) {
                        $events[] = [
                            'start' => $date . 'T' . $dayStartStr,
                            'end' => $date . 'T' . $breakStartStr,
                            'display' => 'background',
                            'color' => 'rgba(16, 185, 129, 0.2)', // Emerald green transparent
                        ];
                        $events[] = [
                            'start' => $date . 'T' . $breakEndStr,
                            'end' => $date . 'T' . $dayEndStr,
                            'display' => 'background',
                            'color' => 'rgba(16, 185, 129, 0.2)',
                        ];
                    } else {
                        $events[] = [
                            'start' => $date . 'T' . $dayStartStr,
                            'end' => $date . 'T' . $dayEndStr,
                            'display' => 'background',
                            'color' => 'rgba(16, 185, 129, 0.2)',
                        ];
                    }
                }
            }
            
            $currentDate->addDay();
        }

        return response()->json($events);
    }
}
