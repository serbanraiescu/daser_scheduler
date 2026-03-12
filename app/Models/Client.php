<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use Notifiable;
    protected $fillable = ['name', 'phone', 'email', 'birth_date', 'notes'];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ClientTag::class, 'client_tag_pivot', 'client_id', 'tag_id');
    }

    public function vouchers()
    {
        return $this->hasMany(ClientVoucher::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(ClientSubscription::class);
    }
}
