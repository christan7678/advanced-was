<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::redirect('/', '/home');
Route::get('/home/live', [HomeController::class, 'live'])->name('home.live');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('pages')->group(function () {
    Route::view('/terms', 'pages.terms')->name('pages.terms');
    Route::view('/privacy', 'pages.privacy')->name('pages.privacy');
    Route::view('/support', 'pages.support')->name('pages.support');
    Route::view('/contact', 'pages.contact')->name('pages.contact');
    Route::view('/faq', 'pages.faq')->name('pages.faq');
    Route::view('/refund', 'pages.refund')->name('pages.refund');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgotPassword', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/profile', [UserController::class, 'index'])->name('profile.index');
    Route::get('/profile-detail', [UserController::class, 'showDetail'])->name('profile.detail');
    Route::get('/profile/my-tickets', [UserController::class, 'tickets'])->name('profile.tickets');
    Route::get('/profile/purchase-history', [UserController::class, 'history'])->name('profile.history');
    Route::get('/profile/change-password', [UserController::class, 'password'])->name('profile.password');
    Route::post('/profile/change-password', [UserController::class, 'updatePassword']);
    
    Route::resource('categories', CategoryController::class)->only(['index', 'show']);
    Route::get('/categories/{category}/events', [CategoryController::class, 'events'])->name('categories.events');

    Route::get('/myBookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/{booking}/afterBooking', [BookingController::class, 'afterBooking'])->name('bookings.after');

    Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{booking}', [PaymentController::class, 'process'])->name('payment.process');

    Route::get('/tickets/{ticket}/qr', [TicketController::class, 'showQR'])->name('tickets.qr');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::redirect('/', '/admin/dashboard');

    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/events', [AdminEventController::class, 'folderIndex'])->name('events.index');
    Route::get('/events/category/{category}', [AdminEventController::class, 'categoryEvents'])->name('events.category');
    Route::resource('events', AdminEventController::class)->except(['index']);

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/search', [AdminUserController::class, 'searchUsers'])->name('users.search');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/bookings', [AdminUserController::class, 'getBookings'])->name('users.bookings');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::post('/refund/{payment}', [RefundController::class, 'refund'])->name('refund.process');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/admins', [AdminManagementController::class, 'index'])->name('admins.index');
    Route::get('/admins/{admin}', [AdminManagementController::class, 'show'])->name('admins.show');
    Route::post('/admins', [AdminManagementController::class, 'store'])->name('admins.store');
    Route::put('/admins/{admin}', [AdminManagementController::class, 'update'])->name('admins.update');
    Route::delete('/admins/{admin}', [AdminManagementController::class, 'destroy'])->name('admins.destroy');
});
