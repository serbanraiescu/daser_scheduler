<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:send-booking-reminders')->everyFiveMinutes();

Schedule::command('app:send-birthday-vouchers')->dailyAt('09:00');
Schedule::command('app:run-reactivation-campaign')->dailyAt('10:00');
Schedule::command('app:check-voucher-expiring')->dailyAt('00:00');
