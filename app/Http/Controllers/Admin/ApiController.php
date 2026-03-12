<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function bookings(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        // Note: start and end are typically ISO8601 strings from FullCalendar
        $bookings = \App\Models\Booking::with(['client', 'service', 'employee'])
            ->whereBetween('start_time', [$start, $end])
            ->get();

        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->client->name . ' - ' . $booking->service->name,
                'start' => $booking->start_time->toIso8601String(),
                'end' => $booking->end_time->toIso8601String(),
                'backgroundColor' => $this->getStatusColor($booking->status),
                'extendedProps' => [
                    'client' => $booking->client->name,
                    'service' => $booking->service->name,
                    'employee' => $booking->employee->name,
                    'status' => $booking->status,
                ]
            ];
        });

        return response()->json($events);
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            'confirmed' => '#3b82f6', // blue
            'completed' => '#10b981', // green
            'cancelled' => '#ef4444', // red
            'no_show' => '#f59e0b', // orange
            default => '#6b7280', // gray
        };
    }
}
