<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSchedule extends Model
{
    protected $table = 'employee_schedule';
    
    protected $fillable = ['employee_id', 'date', 'start_time', 'end_time', 'break_start', 'break_end', 'is_off'];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
