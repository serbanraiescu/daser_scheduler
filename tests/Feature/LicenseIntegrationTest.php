<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use App\Services\LicenseService;
use Carbon\Carbon;

class LicenseIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::setValue('license_key', 'test-key');
        Setting::setValue('license_kill_token', 'kill-me');
    }

    public function test_accessible_when_active()
    {
        Setting::setValue('license_status', 'active');
        Setting::setValue('license_last_check', now()->toDateTimeString());
        Cache::forget(LicenseService::CACHE_KEY);

        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_lockout_when_denied()
    {
        Setting::setValue('license_status', 'denied');
        Cache::forget(LicenseService::CACHE_KEY);

        $response = $this->get('/');
        $response->assertStatus(403);
        $response->assertSee('Licență Invalidă sau Expirată');
    }

    public function test_lockout_after_grace_period()
    {
        Setting::setValue('license_status', 'active');
        // Set last check 50 hours ago (more than 48h GRACE_PERIOD)
        Setting::setValue('license_last_check', now()->subHours(50)->toDateTimeString());
        Cache::forget(LicenseService::CACHE_KEY);

        $response = $this->get('/');
        $response->assertStatus(403);
    }

    public function test_grace_period_warning()
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        
        Setting::setValue('license_status', 'active');
        // Set last check 20 hours ago (more than 12h CHECK_INTERVAL, less than 48h GRACE_PERIOD)
        Setting::setValue('license_last_check', now()->subHours(20)->toDateTimeString());
        Cache::forget(LicenseService::CACHE_KEY);

        $response = $this->get('/admin/dashboard');
        $response->assertSee('Perioadă de grație');
    }

    public function test_remote_kill_switch()
    {
        $response = $this->post('/api/v1/license-kill', [
            'kill_token' => 'kill-me'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('denied', Setting::getValue('license_status'));
    }

    public function test_exempted_routes_work_when_denied()
    {
        Setting::setValue('license_status', 'denied');
        Cache::forget(LicenseService::CACHE_KEY);

        // Login page should work
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}
