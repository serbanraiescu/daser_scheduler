<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/view-log', function() {
    $logPath = storage_path('logs/laravel.log');
    if (!file_exists($logPath)) return "Log file not found.";
    return nl2br(e(file_get_contents($logPath)));
});

Route::get('/seed-demo', function() {
    try {
        Artisan::call('db:seed', ['--class' => 'BarbershopDemoSeeder', '--force' => true]);
        return "Demo seeded successfully!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::middleware(['check.license'])->group(function () {
    Route::get('/', [\App\Http\Controllers\PublicWebsiteController::class, 'index'])->name('home');
    Route::get('/page/{slug}', [\App\Http\Controllers\PublicWebsiteController::class, 'show'])->name('pages.show');
    
    // Booking Flow
    Route::prefix('booking')->group(function() {
        Route::get('/', [\App\Http\Controllers\PublicBookingController::class, 'index'])->name('bookings.index');
        Route::get('/api/categories', [\App\Http\Controllers\PublicBookingController::class, 'apiCategories']);
        Route::get('/api/services', [\App\Http\Controllers\PublicBookingController::class, 'apiServices']);
        Route::get('/api/slots', [\App\Http\Controllers\PublicBookingController::class, 'apiSlots']);
        Route::get('/employee', [\App\Http\Controllers\PublicBookingController::class, 'selectEmployee'])->name('bookings.employee');
        Route::get('/slots', [\App\Http\Controllers\PublicBookingController::class, 'selectSlots'])->name('bookings.slots');
        Route::get('/details', [\App\Http\Controllers\PublicBookingController::class, 'details'])->name('bookings.details');
        Route::post('/confirm', [\App\Http\Controllers\PublicBookingController::class, 'confirm'])->name('bookings.confirm');
    });

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
        Route::resource('service-categories', \App\Http\Controllers\Admin\ServiceCategoryController::class);
        Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::patch('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('clients/import', [\App\Http\Controllers\Admin\ClientController::class, 'import'])->name('clients.import');
        Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class);
        Route::resource('vouchers', \App\Http\Controllers\Admin\VoucherController::class);

        // Website CMS Routes
        Route::get('/website', [\App\Http\Controllers\Admin\WebsiteController::class, 'index'])->name('website.index');
        Route::patch('/website/settings', [\App\Http\Controllers\Admin\WebsiteController::class, 'updateSettings'])->name('website.settings.update');
        Route::get('/website/pages/create', [\App\Http\Controllers\Admin\WebsiteController::class, 'pageCreate'])->name('website.pages.create');
        Route::post('/website/pages', [\App\Http\Controllers\Admin\WebsiteController::class, 'pageStore'])->name('website.pages.store');
        Route::get('/website/pages/{page}/edit', [\App\Http\Controllers\Admin\WebsiteController::class, 'pageEdit'])->name('website.pages.edit');
        Route::patch('/website/pages/{page}', [\App\Http\Controllers\Admin\WebsiteController::class, 'pageUpdate'])->name('website.pages.update');
        Route::delete('/website/pages/{page}', [\App\Http\Controllers\Admin\WebsiteController::class, 'pageDestroy'])->name('website.pages.destroy');
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
