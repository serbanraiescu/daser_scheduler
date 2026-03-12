<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['code', 'type', 'value', 'start_date', 'end_date', 'max_uses', 'active'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function clientVouchers()
    {
        return $this->hasMany(ClientVoucher::class);
    }
}
