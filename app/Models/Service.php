<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'duration', 'price', 'is_active'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'employee_service', 'service_id', 'user_id');
    }

    public function waitlists()
    {
        return $this->hasMany(Waitlist::class);
    }
}
