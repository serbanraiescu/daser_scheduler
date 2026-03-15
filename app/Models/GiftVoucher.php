<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftVoucher extends Model
{
    protected $fillable = [
        'code',
        'client_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'value_amount',
        'service_id',
        'remaining_uses',
        'remaining_value',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'value_amount' => 'decimal:2',
        'remaining_value' => 'decimal:2',
        'expires_at' => 'date',
        'remaining_uses' => 'integer',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isValid()
    {
        return $this->status === 'active' && !$this->isExpired();
    }
}
