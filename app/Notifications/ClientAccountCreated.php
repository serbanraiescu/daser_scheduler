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
    public function toMail($notifiable): MailMessage
    {
        $businessName = \App\Models\Setting::getValue('business_name') ?? config('app.name');
        $websiteSettings = \App\Models\WebsiteSetting::first();
        $logoUrl = $websiteSettings ? asset($websiteSettings->logo_alt_url ?: $websiteSettings->logo_url) : null;

        $mail = (new MailMessage)
            ->subject('Contul tău a fost creat - ' . $businessName)
            ->greeting('Salut ' . $notifiable->name . ',')
            ->line('Ne bucurăm să te avem printre clienții noștri!')
            ->line('Ți-am creat automat un cont pe platforma ' . $businessName . ' pentru a-ți putea gestiona programările, a vedea istoricul vizitelor și a beneficia de sistemul nostru de fidelitate.')
            ->line('Datele tale de autentificare sunt:')
            ->line('**Email:** ' . $notifiable->email)
            ->line('**Parolă:** ' . $this->password)
            ->action('Autentificare', route('login'))
            ->line('Te rugăm să îți schimbi parola după prima autentificare din secțiunea profilului tău pentru o siguranță sporită.')
            ->line('Îți mulțumim că ne-ai ales!')
            ->salutation('Cu drag, echipa ' . $businessName);

        // Notă: Imaginea va fi încărcată de client de la URL dacă setările SMTP permit HTML
        // Laravel's default notification template supports markdown and captures the app name.
        
        return $mail;
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
