<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the bookings assigned to this employee.
     */
    public function employeeBookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * The services this employee can perform.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'employee_service', 'user_id', 'service_id');
    }

    /**
     * Role checks.
     */
    public function isSuperAdmin() { return $this->role === 'superadmin'; }
    public function isAdmin() { return $this->role === 'admin' || $this->role === 'superadmin'; }
    public function isEmployee() { return $this->role === 'employee'; }
    public function isClient() { return $this->role === 'client'; }
}
