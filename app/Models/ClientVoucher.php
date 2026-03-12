<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientVoucher extends Model
{
    protected $fillable = ['client_id', 'voucher_id', 'expires_at', 'used'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
