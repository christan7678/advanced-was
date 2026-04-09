<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('home');
})->name('home');

/* Login and Registration Routes */
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::view('/forgotPassword', 'auth.forgotPassword')->name('password.request');

Route::get('/auth/google', function () {
    return 'Google login page';
})->name('auth.google');




Route::view('/events', 'events.index')->name('events.index');
Route::view('/events/show', 'events.show')->name('events.show');

Route::get('/bookings/create', function () {
    return view('bookings.create');
})->name('bookings.create');

Route::post('/bookings/create', function () {
    Session::put('booking_message', 'Your ticket booking has been submitted successfully.');
    Session::flash('success', 'Booking created successfully.');
    return redirect()->route('bookings.index');
})->name('bookings.store');

Route::get('/myBookings', function () {
    $bookingMessage = Session::get('booking_message');
    return view('bookings.index', compact('bookingMessage'));
})->name('bookings.index');

Route::get('/profile', function (Request $request) {
    $lastVisit = $request->cookie('last_visit');
    return view('profile.index', compact('lastVisit'));
})->name('profile.index');



// Profile pages
Route::view('/profile-detail', 'profile.detail');
Route::view('/address', 'profile.address');
Route::view('/my-bookings', 'profile.tickets');
Route::view('/purchase-history', 'profile.history');
Route::view('/change-password', 'profile.password');

// Info pages
Route::view('/terms', 'pages.terms')->name('pages.terms');
Route::view('/privacy', 'pages.privacy')->name('pages.privacy'); 
Route::view('/support', 'pages.support')->name('pages.support');
Route::view('/contact', 'pages.contact')->name('pages.contact');
Route::view('/faq', 'pages.faq')->name('pages.faq');
Route::view('/refund', 'pages.refund')->name('pages.refund');

/* Admin create and check bookings */
Route::view('/admin/events/create', 'admin.events.create')->name('admin.events.create');
Route::view('/admin/bookings', 'admin.bookings.index')->name('admin.bookings.index');