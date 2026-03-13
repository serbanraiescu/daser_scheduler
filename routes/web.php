<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

Route::get('/system-check', function() {
    $paths = [
        storage_path('framework/sessions'),
        storage_path('framework/views'),
        storage_path('framework/cache'),
        storage_path('logs'),
        base_path('bootstrap/cache'),
    ];

    $results = [];
    foreach ($paths as $path) {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
            $results[$path] = "Created";
        } else {
            $results[$path] = "Exists (" . substr(sprintf('%o', fileperms($path)), -4) . ")";
        }
    }

    return [
        'php_version' => PHP_VERSION,
        'app_key_set' => strlen(config('app.key')) > 0,
        'public_path' => public_path(),
        'vite_manifest_exists' => File::exists(public_path('build/manifest.json')),
        'storage_checks' => $results,
        'log_writable' => is_writable(storage_path('logs')),
    ];
});

// Emergency Log Viewer
Route::get('/view-log', function() {
    $logPath = storage_path('logs/laravel.log');
    if (!File::exists($logPath)) {
        return "Log file does not exist yet.";
    }
    
    $lines = 100;
    $data = array_slice(explode("\n", File::get($logPath)), -$lines);
    return response("<pre>" . implode("\n", $data) . "</pre>");
});

Route::middleware(['check.license'])->group(function () {
    Route::get('/', [\App\Http\Controllers\PublicBookingController::class, 'index'])->name('bookings.index');
    Route::get('/book/employee', [\App\Http\Controllers\PublicBookingController::class, 'selectEmployee'])->name('bookings.employee');
    Route::get('/book/slots', [\App\Http\Controllers\PublicBookingController::class, 'selectSlots'])->name('bookings.slots');
    Route::get('/book/details', [\App\Http\Controllers\PublicBookingController::class, 'details'])->name('bookings.details');
    Route::post('/book/confirm', [\App\Http\Controllers\PublicBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::get('/appointment/{link}', [\App\Http\Controllers\PublicBookingController::class, 'show'])->name('bookings.show');
    Route::post('/appointment/{link}/cancel', [\App\Http\Controllers\PublicBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/appointment/{link}/ics', [\App\Http\Controllers\IcsController::class, 'download'])->name('bookings.ics');
});

Route::middleware(['auth', 'verified', 'check.license'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/api/bookings', [\App\Http\Controllers\Admin\ApiController::class, 'bookings'])->name('api.bookings');
        Route::patch('/api/bookings/{id}', [\App\Http\Controllers\Admin\ApiController::class, 'update'])->name('api.bookings.update');

        // License Management
        Route::post('/license/reverify', function(\App\Services\LicenseService $licenseService) {
            $result = $licenseService->sync();
            if (isset($result['success'])) {
                return back()->with('success', 'Licență verificată cu succes.');
            }
            return back()->withErrors(['license' => $result['error']]);
        })->name('license.reverify');

        Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
        Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
        Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::patch('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('clients/import', [\App\Http\Controllers\Admin\ClientController::class, 'import'])->name('clients.import');
        Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class);
        Route::resource('vouchers', \App\Http\Controllers\Admin\VoucherController::class);
    });

    // Employee Routes
    Route::middleware(['role:employee'])->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('dashboard');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::post('/api/v1/license-kill', [\App\Http\Controllers\LicenseKillController::class, 'kill']);

// SMS Gateway (Android App)
Route::get('/api/sms_pending', [\App\Http\Controllers\SmsGatewayController::class, 'pending']);
Route::post('/api/sms_confirm', [\App\Http\Controllers\SmsGatewayController::class, 'confirm']);
