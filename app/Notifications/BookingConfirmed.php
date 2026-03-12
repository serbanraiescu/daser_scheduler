<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Booking Confirmed: ' . $this->booking->service->name)
                    ->line('Your appointment has been confirmed.')
                    ->line('Service: ' . $this->booking->service->name)
                    ->line('Professional: ' . $this->booking->employee->name)
                    ->line('Date: ' . $this->booking->date->format('M d, Y'))
                    ->line('Time: ' . $this->booking->start_time->format('H:i'))
                    ->action('Manage Appointment', route('bookings.show', $this->booking->manage_token))
                    ->line('Thank you for choosing us!');
    }
}
