<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('bookings.index');
});

Route::get('/book', [\App\Http\Controllers\PublicBookingController::class, 'index'])->name('bookings.index');
Route::get('/book/employee', [\App\Http\Controllers\PublicBookingController::class, 'selectEmployee'])->name('bookings.employee');
Route::get('/book/slots', [\App\Http\Controllers\PublicBookingController::class, 'selectSlots'])->name('bookings.slots');
Route::get('/book/details', [\App\Http\Controllers\PublicBookingController::class, 'details'])->name('bookings.details');
Route::post('/book/confirm', [\App\Http\Controllers\PublicBookingController::class, 'confirm'])->name('bookings.confirm');
Route::get('/appointment/{link}', [\App\Http\Controllers\PublicBookingController::class, 'show'])->name('bookings.show');
Route::post('/appointment/{link}/cancel', [\App\Http\Controllers\PublicBookingController::class, 'cancel'])->name('bookings.cancel');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/api/bookings', [\App\Http\Controllers\Admin\ApiController::class, 'bookings'])->name('api.bookings');
        Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
        Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
        Route::post('clients/import', [\App\Http\Controllers\Admin\ClientController::class, 'import'])->name('clients.import');
        Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class);
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
