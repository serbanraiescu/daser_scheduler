<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function bookings(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        if (!$start || !$end) {
            return response()->json(['error' => 'Start and end dates are required'], 400);
        }

        $bookings = Booking::with(['client', 'service', 'employee'])
            ->where('start_time', '<', Carbon::parse($end))
            ->where('end_time', '>', Carbon::parse($start))
            ->get();

        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => ($booking->client->name ?? 'Unknown') . ' - ' . ($booking->service->name ?? 'N/A'),
                'start' => $booking->start_time->toIso8601String(),
                'end' => $booking->end_time->toIso8601String(),
                'backgroundColor' => $this->getStatusColor($booking->status),
                'extendedProps' => [
                    'client' => $booking->client->name ?? 'Unknown',
                    'service' => $booking->service->name ?? 'N/A',
                    'employee' => $booking->employee->name ?? 'Unassigned',
                    'status' => $booking->status,
                ]
            ];
        });

        return response()->json($events);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);

        $start = Carbon::parse($validated['start']);
        $end = Carbon::parse($validated['end']);

        // Overlap Check (excluding current booking)
        if (Booking::isOverlapping($booking->employee_id, $start, $end, $booking->id)) {
            return response()->json(['error' => 'This shift overlaps with an existing booking.'], 422);
        }

        $booking->update([
            'date' => $start->format('Y-m-d'),
            'start_time' => $start,
            'end_time' => $end,
        ]);

        return response()->json(['success' => true]);
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
