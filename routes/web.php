<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

Route::resource('categories', CategoryController::class);
Route::get('/categories/{category}/events', [CategoryController::class, 'events'])->name('categories.events');


/***** JING YIN EDIT FOR ADMIN!!!!!!!!!!!!! ***********/
/***************** Admin ******************************/
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

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
    
    Route::resource('categories', CategoryController::class);
});

Route::middleware('auth:admin,web')->group(function () {
    // Events (both admin and user can view)
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

    // Admin-only event management
    Route::middleware('auth:admin')->group(function () {
        Route::get('/admin/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    });
});


Route::redirect('/', '/home');
Route::get('/home', [HomeController::class,'index'])->name('home');

// 暂时的， 后续会改成controller的方式
Route::view('/forgotPassword', 'auth.forgotPassword')->name('password.request');

Route::get('/auth/google', function () {return 'Google login page';})->name('auth.google');


Route::prefix('pages')->group(function () {
    // Info pages
    Route::view('/terms', 'pages.terms')->name('pages.terms');
    Route::view('/privacy', 'pages.privacy')->name('pages.privacy');
    Route::view('/support', 'pages.support')->name('pages.support');
    Route::view('/contact', 'pages.contact')->name('pages.contact');
    Route::view('/faq', 'pages.faq')->name('pages.faq');
    Route::view('/refund', 'pages.refund')->name('pages.refund');
});

// Authentication Routes
// guest middleware ensures only unauthenticated users can access login/register pages
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {

    // if want use button need change to post method
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    //booking routes
    Route::get('/myBookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // funtion can be added inside controller later
    Route::get('/profile', [UserController::class, 'index'])->name('profile.index');

    // Profile pages
    Route::get('/profile-detail', [UserController::class, 'showDetail'])->name('profile.detail');
    Route::get('/profile/my-bookings', [UserController::class, 'tickets'])->name('profile.tickets');
    Route::get('/profile/purchase-history', [UserController::class, 'history'])->name('profile.history');
    Route::get('/profile/change-password', [UserController::class, 'password'])->name('profile.password');
    Route::post('/profile/change-password', [UserController::class, 'updatePassword']);

});

// check auth status route for testing
Route::get('/test-user', function () {
    return auth('web')->check() ? '已登录' : '未登录';
});

Route::get('/test-admin', function () {
    return auth('admin')->check() ? 'Admin已登录' : 'Admin未登录';
});
