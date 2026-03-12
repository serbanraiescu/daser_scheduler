<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Employee;
use App\Models\Booking;
use App\Models\EmployeeSchedule;
use App\Models\BlockedSlot;
use Carbon\Carbon;

class BookingEngineTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $employee;
    protected $user;
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'employee']);
        $this->employee = Employee::create(['user_id' => $this->user->id, 'active' => true]);
        $this->client = \App\Models\Client::create([
            'name' => 'Test Client',
            'phone' => '123456789',
            'email' => 'client@test.com'
        ]);

        $this->service = Service::create([
            'name' => 'Test Service',
            'duration_minutes' => 60,
            'price' => 100,
            'active' => true
        ]);
        
        $this->employee->services()->attach($this->service->id);

        EmployeeSchedule::create([
            'employee_id' => $this->employee->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '17:00'
        ]);
    }

    public function test_cannot_book_overlapping_slots()
    {
        $date = now()->addDay()->format('Y-m-d');
        
        // Create first booking 10:00 - 11:00
        Booking::create([
            'client_id' => $this->client->id,
            'employee_id' => $this->user->id,
            'service_id' => $this->service->id,
            'date' => $date,
            'start_time' => Carbon::parse($date . ' 10:00'),
            'end_time' => Carbon::parse($date . ' 11:00'),
            'status' => 'confirmed',
            'manage_token' => 'test-token-1'
        ]);

        // Try to book 10:30 - 11:30 (Overlap)
        $response = $this->post(route('bookings.confirm'), [
            'service_id' => $this->service->id,
            'employee_id' => $this->user->id,
            'date' => $date,
            'time' => '10:30',
            'name' => 'John Doe',
            'phone' => '123456789'
        ]);

        $response->assertSessionHasErrors('time');
    }

    public function test_cannot_book_during_blocked_slots()
    {
        $date = now()->addDay()->format('Y-m-d');

        BlockedSlot::create([
            'employee_id' => $this->employee->id,
            'date' => $date,
            'start_time' => '12:00',
            'end_time' => '13:00'
        ]);

        // Generate slots and verify 12:00 is missing
        $response = $this->get(route('bookings.slots', [
            'service_id' => $this->service->id,
            'employee_id' => $this->user->id,
            'date' => $date
        ]));

        $response->assertDontSee('12:00');
    }

    public function test_service_must_fit_in_schedule()
    {
        $date = now()->addDay()->format('Y-m-d');

        // Professional ends at 17:00. Service 60 mins. 
        // 16:30 is NOT valid because it ends at 17:30.
        
        $response = $this->get(route('bookings.slots', [
            'service_id' => $this->service->id,
            'employee_id' => $this->user->id,
            'date' => $date
        ]));

        $response->assertDontSee('16:30');
        $response->assertSee('16:00'); // Valid
    }

    public function test_cannot_book_during_breaks()
    {
        $date = now()->addDay()->format('Y-m-d');

        // Update schedule with a break 12:00-13:00
        EmployeeSchedule::where('employee_id', $this->employee->id)
            ->whereDate('date', $date)
            ->update([
                'break_start' => '12:00',
                'break_end' => '13:00'
            ]);

        $response = $this->get(route('bookings.slots', [
            'service_id' => $this->service->id,
            'employee_id' => $this->user->id,
            'date' => $date
        ]));

        $response->assertDontSee('12:00');
        $response->assertSee('11:00');
        $response->assertSee('13:00');
    }
}
