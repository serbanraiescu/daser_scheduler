<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use App\Models\Service;
use App\Models\SmsQueue;
use App\Services\SmsService;
use Carbon\Carbon;

class SmsIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['sms.api_key' => 'test-secret']);
        \App\Models\Setting::setValue('license_status', 'active');
        \App\Models\Setting::setValue('license_last_check', now()->toDateTimeString());
    }

    public function test_api_pending_security()
    {
        // Unauthorized
        $response = $this->getJson('/api/sms_pending?key=wrong');
        $response->assertStatus(401);

        // Authorized
        $response = $this->getJson('/api/sms_pending?key=test-secret');
        $response->assertStatus(200);
    }

    public function test_api_pending_retrieval()
    {
        SmsQueue::create(['phone' => '123', 'message' => 'msg1', 'status' => 'pending']);
        SmsQueue::create(['phone' => '456', 'message' => 'msg2', 'status' => 'sent']);

        $response = $this->getJson('/api/sms_pending?key=test-secret');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['phone' => '123']);
    }

    public function test_api_confirm()
    {
        $sms = SmsQueue::create(['phone' => '123', 'message' => 'msg1', 'status' => 'pending']);

        $response = $this->postJson('/api/sms_confirm?key=test-secret', [
            'id' => $sms->id,
            'status' => 'sent'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('sent', $sms->fresh()->status);
        $this->assertNotNull($sms->fresh()->sent_at);
    }

    public function test_booking_queues_sms()
    {
        $client = Client::create(['name' => 'John', 'phone' => '0740000000', 'email' => 'john@example.com']);
        $user = User::factory()->create(['role' => 'admin']);
        $service = Service::create(['name' => 'Test Service', 'duration_minutes' => 30, 'price' => 100]);
        
        // Setup Employee relationship
        $employee = \App\Models\Employee::create(['user_id' => $user->id, 'active' => true]);
        $employee->services()->attach($service->id);

        $date = now()->addDays(2)->format('Y-m-d');

        $response = $this->post('/book/confirm', [
            'service_id' => $service->id,
            'employee_id' => $user->id,
            'date' => $date,
            'time' => '10:00',
            'name' => 'John',
            'phone' => '0740000000',
            'email' => 'john@example.com',
        ]);

        $response->assertRedirectContains('/appointment/');
        
        $this->assertDatabaseHas('sms_queue', [
            'phone' => '0740000000',
            'type' => 'confirmation'
        ]);
    }

    public function test_reminder_command_queues_reminders()
    {
        $client = Client::create(['name' => 'John', 'phone' => '0740000000']);
        $employee = User::factory()->create(['role' => 'admin']);
        $service = Service::create(['name' => 'Test Service', 'duration_minutes' => 30, 'price' => 100]);
        
        // Booking tomorrow (24h reminder) - Use 24.5 hours to be safe
        $booking24 = Booking::create([
            'client_id' => $client->id,
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'date' => now()->addHours(24)->toDateString(),
            'start_time' => now()->addHours(24)->addMinutes(30),
            'end_time' => now()->addHours(25),
            'status' => 'confirmed',
            'manage_token' => 'test1'
        ]);

        // Booking in 2h - Use 2.5 hours to be safe
        $booking2 = Booking::create([
            'client_id' => $client->id,
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'date' => now()->addHours(2)->toDateString(),
            'start_time' => now()->addHours(2)->addMinutes(30),
            'end_time' => now()->addHours(3),
            'status' => 'confirmed',
            'manage_token' => 'test2'
        ]);

        $this->artisan('app:send-booking-reminders')
             ->assertExitCode(0);

        $this->assertDatabaseHas('sms_queue', [
            'booking_id' => $booking24->id,
            'type' => '24h_reminder'
        ]);

        $this->assertDatabaseHas('sms_queue', [
            'booking_id' => $booking2->id,
            'type' => '2h_reminder'
        ]);

        // Run again, should not duplicate
        $this->artisan('app:send-booking-reminders');
        $this->assertEquals(2, SmsQueue::where('source', 'reminder')->count());
    }
}
