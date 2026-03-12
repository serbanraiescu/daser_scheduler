<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class IcsController extends Controller
{
    public function download($token)
    {
        $booking = Booking::where('manage_token', $token)->with(['client', 'service', 'employee'])->firstOrFail();
        
        $start = $booking->start_time->format('Ymd\THis');
        $end = $booking->end_time->format('Ymd\THis');
        $summary = "Booking: " . $booking->service->name;
        $description = "Professional: " . $booking->employee->name . "\nService: " . $booking->service->name;
        $location = config('app.name');
        $stamp = now()->format('Ymd\THis');
        $uid = $booking->manage_token . "@" . request()->getHost();

        $ics = [
            "BEGIN:VCALENDAR",
            "VERSION:2.0",
            "PRODID:-//DaserScheduler//NONSGML v1.0//EN",
            "BEGIN:VEVENT",
            "UID:$uid",
            "DTSTAMP:$stamp",
            "DTSTART:$start",
            "DTEND:$end",
            "SUMMARY:$summary",
            "DESCRIPTION:$description",
            "LOCATION:$location",
            "BEGIN:VALARM",
            "TRIGGER:-PT1H",
            "ACTION:DISPLAY",
            "DESCRIPTION:Reminder: Upcoming Appointment",
            "END:VALARM",
            "END:VEVENT",
            "END:VCALENDAR"
        ];

        return response(implode("\r\n", $ics))
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="booking-' . $booking->id . '.ics"');
    }
}
