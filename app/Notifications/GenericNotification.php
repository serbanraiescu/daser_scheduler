<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\WebsiteSetting;

class GenericNotification extends Notification
{
    use Queueable;

    public $subject;
    public $message;
    public $actionText;
    public $actionUrl;

    public function __construct($subject, $message, $actionText = null, $actionUrl = null)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->actionText = $actionText;
        $this->actionUrl = $actionUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $websiteSettings = WebsiteSetting::first();
        $logoUrl = $websiteSettings ? $websiteSettings->logo_url : null;
        
        $mail = (new MailMessage)
            ->subject($this->subject)
            ->greeting('Salut ' . $notifiable->name . ',')
            ->line($this->message);

        if ($this->actionText && $this->actionUrl) {
            $mail->action($this->actionText, $this->actionUrl);
        }

        // Add salon name to footer
        $businessName = \App\Models\Setting::getValue('business_name') ?? config('app.name');
        $mail->salutation('Cu drag, echipa ' . $businessName);

        return $mail;
    }
}
