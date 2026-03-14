<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Carbon\Carbon;

class SidebarCalendarComposer
{
    public function compose(View $view)
    {
        // Only compute this for authenticated employees to save performance
        if (auth()->check() && auth()->user()->isEmployee()) {
            
            // Get requested date, fallback to today
            $requestDate = request('date', now()->toDateString());
            try {
                $date = Carbon::parse($requestDate);
            } catch (\Exception $e) {
                $date = now();
            }

            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            // Find the first day of the calendar grid (Monday)
            $startDate = $startOfMonth->copy()->startOfWeek();
            // Find the last day of the calendar grid (Sunday)
            $endDate = $endOfMonth->copy()->endOfWeek();

            $calendarWeeks = [];
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $week = [];
                for ($i = 0; $i < 7; $i++) {
                    $week[] = [
                        'date' => $currentDate->toDateString(),
                        'dayNumber' => $currentDate->format('j'), // Without leading zero
                        'isCurrentMonth' => $currentDate->month === $date->month,
                        'isToday' => $currentDate->isToday(),
                        'isSelected' => $currentDate->toDateString() === $date->toDateString(),
                    ];
                    $currentDate->addDay();
                }
                $calendarWeeks[] = $week;
            }

            $currentMonthName = ucfirst($date->translatedFormat('F Y'));

            $view->with([
                'sidebarCalendarWeeks' => $calendarWeeks,
                'sidebarCurrentMonth' => $currentMonthName,
                'sidebarSelectedDate' => $date->toDateString(),
            ]);
        }
    }
}
