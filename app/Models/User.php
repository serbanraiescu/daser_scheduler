<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'birth_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'employee_id');
    }

    public function isSuperAdmin() { return $this->role === 'superadmin'; }
    public function isAdmin() { return $this->role === 'admin' || $this->role === 'superadmin'; }
    public function isEmployee() { return $this->role === 'employee'; }
    public function isClient() { return $this->role === 'client'; }
}
