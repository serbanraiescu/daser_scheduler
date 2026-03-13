<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandardSchedule extends Model
{
    protected $fillable = ['employee_id', 'day_of_week', 'start_time', 'end_time', 'break_start', 'break_end', 'is_off'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
