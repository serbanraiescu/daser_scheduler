<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'client_id', 'employee_id', 'service_id', 'date', 
        'start_time', 'end_time', 'status', 'manage_token', 'notes', 'gift_voucher_id'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected static function booted()
    {
        static::updated(function ($booking) {
            if ($booking->wasChanged('status') && $booking->status === 'no_show') {
                $booking->client->increment('no_show_count');
            }
        });
    }

    /**
     * Check if a new booking overlaps with existing bookings.
     * Condition: (new_start < existing_end) AND (new_end > existing_start)
     */
    public static function isOverlapping($employeeId, $startTime, $endTime, $excludeBookingId = null)
    {
        $query = self::where('employee_id', $employeeId)
            ->where('status', '!=', 'cancelled')
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->exists();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
