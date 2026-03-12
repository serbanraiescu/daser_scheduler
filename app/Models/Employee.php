<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['user_id', 'active'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'employee_services');
    }

    public function schedule()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    public function blockedSlots()
    {
        return $this->hasMany(BlockedSlot::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'employee_id', 'user_id');
    }
}
