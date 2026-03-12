<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    protected $fillable = ['client_name', 'client_phone', 'service_id', 'employee_id', 'date', 'notified'];

    protected $casts = [
        'date' => 'date',
        'notified' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
