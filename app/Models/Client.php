<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use Notifiable;
    protected $fillable = [
        'name', 'phone', 'email', 'birth_date', 'notes',
        'user_id', 'loyalty_points', 'special_discount', 'fidelity_card_number'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'special_discount' => 'decimal:2',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
