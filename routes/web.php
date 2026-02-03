<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MpesaController;
use App\Models\Ticket;

/*
|--------------------------------------------------------------------------|
| Public Booking Flow
|--------------------------------------------------------------------------|
*/

// Home
Route::get('/', fn () => view('home'))->name('home');

// Booking type
Route::get('/booking/type', [BookingController::class, 'bookingType'])->name('booking.type');

// Individual
Route::get('/booking/individual', [BookingController::class, 'individualBooking'])->name('booking.individual');
Route::post('/booking/individual', [BookingController::class, 'submitIndividualBooking'])->name('booking.submit.individual');

// Group
Route::get('/booking/group', [BookingController::class, 'groupBooking'])->name('booking.group');
Route::post('/booking/group', [BookingController::class, 'submitGroupBooking'])->name('group-booking.submit');

// Checkout (confirm + pay)
Route::get('/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::post('/checkout', [BookingController::class, 'processCheckout'])->name('booking.checkout.process');

// M-Pesa
Route::post('/mpesa/stk', [MpesaController::class, 'stkPush'])->name('mpesa.stk');

// Ticket
Route::get('/ticket', [BookingController::class, 'ticket'])->name('booking.ticket');

/*
|--------------------------------------------------------------------------|
| Admin Authentication
|--------------------------------------------------------------------------|
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::middleware(['admin.auth'])->prefix('admin')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/validate', [AdminController::class, 'validatePage'])->name('admin.validate');
    Route::post('/validate', [AdminController::class, 'validateTicket'])->name('admin.validate.submit');
});
