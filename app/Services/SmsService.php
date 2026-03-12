<?php

namespace App\Services;

use App\Models\SmsQueue;
use App\Models\Setting;

class SmsService
{
    /**
     * Queue an SMS message.
     */
    public static function queue($phone, $message, $source = null, $bookingId = null, $type = null)
    {
        // Basic phone cleanup (ensure it's not empty)
        if (empty($phone) || empty($message)) {
            return null;
        }

        return SmsQueue::create([
            'booking_id' => $bookingId,
            'phone' => $phone,
            'message' => $message,
            'status' => 'pending',
            'source' => $source,
            'type' => $type,
        ]);
    }

    /**
     * Format a booking confirmation message.
     */
    public static function formatBookingConfirmation($booking)
    {
        $businessName = Setting::getValue('business_name', config('app.name'));
        $date = $booking->date->format('d.m.Y');
        $time = $booking->start_time->format('H:i');

        return "Programarea ta la {$businessName} este confirmată pentru {$date} la {$time}.";
    }

    /**
     * Format a booking reminder message.
     */
    public static function formatBookingReminder($booking, $hoursBefore)
    {
        $businessName = Setting::getValue('business_name', config('app.name'));
        $time = $booking->start_time->format('H:i');
        
        if ($hoursBefore >= 24) {
            $date = $booking->date->format('d.m.Y');
            return "Memento: Ai o programare la {$businessName} mâine ({$date}) la ora {$time}. Te așteptăm!";
        }

        return "Memento: Te așteptăm la {$businessName} peste aproximativ 2 ore (la ora {$time}).";
    }
}
