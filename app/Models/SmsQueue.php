<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsQueue extends Model
{
    protected $table = 'sms_queue';
    
    protected $fillable = [
        'booking_id',
        'phone',
        'message',
        'status',
        'source',
        'type',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
