<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientAccountCreated extends Notification
{
    use Queueable;

    public $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Contul tău a fost creat - Bine ai venit!')
            ->greeting('Salut ' . $notifiable->name . ',')
            ->line('Ne bucurăm să te avem printre clienții noștri!')
            ->line('Ți-am creat automat un cont pe platforma noastră pentru a-ți putea gestiona programările, a vedea istoricul vizitelor și a beneficia de sistemul nostru de fidelitate.')
            ->line('Datele tale de autentificare sunt:')
            ->line('**Email:** ' . $notifiable->email)
            ->line('**Parolă:** ' . $this->password)
            ->action('Autentificare', route('login'))
            ->line('Te rugăm să îți schimbi parola după prima autentificare din secțiunea profilului tău pentru o siguranță sporită.')
            ->line('Îți mulțumim că ne-ai ales!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
