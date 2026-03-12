<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'duration_minutes', 'price', 'description', 'active'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_services');
    }

    public function waitlists()
    {
        return $this->hasMany(Waitlist::class);
    }
}
