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

        // 1. Standard Weekly Schedule
        $standardSchedules = $employee->standardSchedules->keyBy('day_of_week');
        $weekDays = [
            1 => 'Luni',
            2 => 'Marți',
            3 => 'Miercuri',
            4 => 'Joi',
            5 => 'Vineri',
            6 => 'Sâmbătă',
            0 => 'Duminică',
        ];

        // 2. Specific Exceptions (Next 14 days)
        $exceptions = $employee->schedule()->orderBy('date')->get()->keyBy(function($s) {
            return $s->date->format('Y-m-d');
        });

        $days = [];
        for ($i = 0; $i < 14; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            $days[$date] = $exceptions->get($date) ?? null;
        }

        // 3. Blocked Slots (Vacations/Holidays)
        $blockedDates = \App\Models\BlockedSlot::where('employee_id', $employee->id)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->get();

        return view('employee.schedule.index', compact('standardSchedules', 'weekDays', 'days', 'blockedDates'));
    }

    public function updateStandard(Request $request)
    {
        $employee = auth()->user()->employee;
        $data = $request->input('standard', []);

        foreach ($data as $day => $dayData) {
            $employee->standardSchedules()->updateOrCreate(
                ['day_of_week' => $day],
                [
                    'start_time' => $dayData['start_time'] ?? '09:00',
                    'end_time' => $dayData['end_time'] ?? '18:00',
                    'break_start' => $dayData['break_start'] ?? null,
                    'break_end' => $dayData['break_end'] ?? null,
                    'is_off' => isset($dayData['is_off']),
                ]
            );
        }

        return back()->with('success', 'Programul standard a fost actualizat.');
    }

    public function update(Request $request)
    {
        $employee = auth()->user()->employee;
        $data = $request->input('schedule', []);

        foreach ($data as $date => $dayData) {
            if (isset($dayData['is_off']) && $dayData['is_off'] == '1') {
                $employee->schedule()->updateOrCreate(
                    ['date' => $date],
                    ['is_off' => true]
                );
                continue;
            }

            if (isset($dayData['start_time'])) {
                $employee->schedule()->updateOrCreate(
                    ['date' => $date],
                    [
                        'start_time' => $dayData['start_time'],
                        'end_time' => $dayData['end_time'],
                        'break_start' => $dayData['break_start'] ?? null,
                        'break_end' => $dayData['break_end'] ?? null,
                        'is_off' => false,
                    ]
                );
            }
        }

        return back()->with('success', 'Excepțiile au fost salvate.');
    }

    public function block(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'reason' => 'nullable|string|max:255',
        ]);

        $employee = auth()->user()->employee;
        $start = Carbon::parse($validated['date']);
        $end = $validated['end_date'] ? Carbon::parse($validated['end_date']) : $start;
        
        $current = $start->copy();
        while ($current->lte($end)) {
            \App\Models\BlockedSlot::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'date' => $current->toDateString(),
                ],
                [
                    'start_time' => '00:00:00',
                    'end_time' => '23:59:59',
                    'reason' => $validated['reason'] ?? 'Concediu/Inactiv',
                ]
            );
            $current->addDay();
        }

        return back()->with('success', 'Zilele au fost blocate.');
    }

    public function unblock($id)
    {
        $employee = auth()->user()->employee;
        \App\Models\BlockedSlot::where('id', $id)->where('employee_id', $employee->id)->delete();
        return back()->with('success', 'Ziua a fost deblocată.');
    }
}
