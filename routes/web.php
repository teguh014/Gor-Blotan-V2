<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CourtController as AdminCourtController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public schedule API (no auth required — used by FullCalendar on landing & dashboard)
Route::get('/schedule/events', [ScheduleController::class, 'events'])->name('schedule.events');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (all logged-in users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Smart dashboard redirect based on role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Breeze Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {

    // Customer dashboard: my bookings list
    Route::get('/dashboard', [BookingController::class, 'index'])->name('dashboard');

    // Booking CRUD (customer)
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/receipt', [BookingController::class, 'receipt'])->name('bookings.receipt');

    // Removed simulated payment route
    // Route::patch('/bookings/{booking}/pay', [BookingController::class, 'pay'])->name('bookings.pay');

    // Check payment status from Xendit manually
    Route::post('/bookings/{booking}/check-status', [BookingController::class, 'checkStatus'])->name('bookings.check-status');

    // Cancel booking: pending → cancelled
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (protected by 'admin' middleware alias)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin dashboard with stats overview
    Route::get('/dashboard', [AdminBookingController::class, 'dashboard'])->name('dashboard');

    // Court management (full CRUD)
    Route::resource('courts', AdminCourtController::class);

    // Financial Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');

    // Booking management
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/receipt', [AdminBookingController::class, 'receipt'])->name('bookings.receipt');

    // Admin actions on bookings
    Route::patch('/bookings/{booking}/complete', [AdminBookingController::class, 'complete'])->name('bookings.complete');
    Route::patch('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

    // Venue Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Breeze Auth Routes (login, register, forgot password, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Webhooks
|--------------------------------------------------------------------------
*/
Route::post('/webhook/xendit', [WebhookController::class, 'xendit'])->name('webhook.xendit');
