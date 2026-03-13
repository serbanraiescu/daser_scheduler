<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeSchedule;
use App\Models\Employee;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            $employee = Employee::create(['user_id' => auth()->id()]);
        }

        $schedules = $employee->schedule()->orderBy('date')->get()->groupBy(function($s) {
            return $s->date->format('Y-m-d');
        });

        // Generate next 14 days
        $days = [];
        for ($i = 0; $i < 14; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            $days[$date] = $schedules->get($date)?->first() ?? null;
        }

        return view('employee.schedule.index', compact('days'));
    }

    public function update(Request $request)
    {
        $employee = auth()->user()->employee;
        $data = $request->input('schedule', []);

        foreach ($data as $date => $dayData) {
            if (isset($dayData['is_off']) && $dayData['is_off'] == '1') {
                $employee->schedule()->whereDate('date', $date)->delete();
                continue;
            }

            $employee->schedule()->updateOrCreate(
                ['date' => $date],
                [
                    'start_time' => $dayData['start_time'] ?? '09:00',
                    'end_time' => $dayData['end_time'] ?? '18:00',
                    'break_start' => $dayData['break_start'] ?? null,
                    'break_end' => $dayData['break_end'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Programul a fost actualizat cu succes.');
    }
}
