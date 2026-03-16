<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('production')) {
            $this->app->usePublicPath(base_path('../public_html'));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Schema::hasTable('settings')) {
                $mailSettings = \App\Models\Setting::whereIn('key', [
                    'mail_host', 'mail_port', 'mail_username', 'mail_password', 
                    'mail_encryption', 'mail_from_address', 'business_name'
                ])->get()->pluck('value', 'key');

                if ($mailSettings->has('mail_host') && $mailSettings['mail_host']) {
                    config([
                        'mail.mailers.smtp.host' => $mailSettings['mail_host'],
                        'mail.mailers.smtp.port' => $mailSettings['mail_port'] ?? '465',
                        'mail.mailers.smtp.username' => $mailSettings['mail_username'],
                        'mail.mailers.smtp.password' => $mailSettings['mail_password'],
                        'mail.mailers.smtp.encryption' => $mailSettings['mail_encryption'] ?? 'ssl',
                        'mail.from.address' => $mailSettings['mail_from_address'] ?? $mailSettings['mail_username'],
                        'mail.from.name' => $mailSettings['business_name'] ?? config('app.name'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('SMTP Config Load Error: ' . $e->getMessage());
        }
    }
}
