<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Setting;
use App\Providers\AppServiceProvider;

class MailConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_smtp_settings_are_applied_from_database()
    {
        // Set values in database
        Setting::setValue('mail_host', 'smtp.custom.com');
        Setting::setValue('mail_port', '587');
        Setting::setValue('mail_username', 'user@custom.com');
        Setting::setValue('mail_password', 'secret');
        Setting::setValue('mail_encryption', 'tls');
        Setting::setValue('mail_from_address', 'no-reply@custom.com');
        Setting::setValue('business_name', 'Micul Salon');

        // Re-run the boot method of AppServiceProvider to apply settings
        // Normally this happens at startup, in tests we can trigger it manually or just check Config
        (new AppServiceProvider(app()))->boot();

        $this->assertEquals('smtp.custom.com', config('mail.mailers.smtp.host'));
        $this->assertEquals('587', config('mail.mailers.smtp.port'));
        $this->assertEquals('user@custom.com', config('mail.mailers.smtp.username'));
        $this->assertEquals('tls', config('mail.mailers.smtp.encryption'));
        $this->assertEquals('no-reply@custom.com', config('mail.from.address'));
        $this->assertEquals('Micul Salon', config('mail.from.name'));
    }
}
