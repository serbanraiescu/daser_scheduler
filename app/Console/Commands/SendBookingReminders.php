<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\SmsQueue;
use App\Services\SmsService;
use Carbon\Carbon;

class SendBookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-booking-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find upcoming bookings and queue SMS reminders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for upcoming bookings to send reminders...');

        $now = now();
        
        // 1. Check for 24h reminders
        // Target window: bookings happening in 24-25 hours from now
        $startTime24h = $now->copy()->addHours(24);
        $endTime24h = $now->copy()->addHours(25);

        $bookings24h = Booking::where('status', 'confirmed')
            ->whereBetween('start_time', [$startTime24h, $endTime24h])
            ->get();

        foreach ($bookings24h as $booking) {
            $this->queueReminder($booking, 24, '24h_reminder');
        }

        // 2. Check for 2h reminders
        // Target window: bookings happening in 2-3 hours from now
        $startTime2h = $now->copy()->addHours(2);
        $endTime2h = $now->copy()->addHours(3);

        $bookings2h = Booking::where('status', 'confirmed')
            ->whereBetween('start_time', [$startTime2h, $endTime2h])
            ->get();

        foreach ($bookings2h as $booking) {
            $this->queueReminder($booking, 2, '2h_reminder');
        }

        $this->info('Reminder check completed.');
    }

    /**
     * Queue a reminder if not already queued.
     */
    private function queueReminder($booking, $hours, $type)
    {
        // Check if already queued for this booking and type
        $exists = SmsQueue::where('booking_id', $booking->id)
            ->where('type', $type)
            ->exists();

        if (!$exists) {
            SmsService::queue(
                $booking->client->phone,
                SmsService::formatBookingReminder($booking, $hours),
                'reminder',
                $booking->id,
                $type
            );
            $this->line("Queued {$type} for Booking #{$booking->id}");
        }
    }
}
