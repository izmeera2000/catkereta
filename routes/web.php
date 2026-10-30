<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('bookings.store');
Route::get('/bookings/{booking:public_id}', [BookingController::class, 'show'])
    ->middleware('signed:relative')
    ->name('bookings.show');
Route::get('/bookings/{booking:public_id}/photos/{photo}', [StaffBookingController::class, 'photo'])
    ->middleware('signed:relative')
    ->name('bookings.photos.show');

Route::middleware('guest')->group(function () {
    Route::get('/staff/login', [StaffAuthController::class, 'create'])->name('staff.login');
    Route::post('/staff/login', [StaffAuthController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('staff.login.store');
});

Route::middleware(['auth', 'can:manage-bookings'])->prefix('staff')->name('staff.')->group(function () {
    Route::post('/logout', [StaffAuthController::class, 'destroy'])->name('logout');
    Route::get('/', [StaffBookingController::class, 'index'])->name('dashboard');
    Route::get('/bookings/{paintBooking}', [StaffBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{paintBooking}', [StaffBookingController::class, 'update'])->name('bookings.update');
    Route::post('/bookings/{paintBooking}/photos', [StaffBookingController::class, 'storePhoto'])
        ->name('bookings.photos.store');
});
