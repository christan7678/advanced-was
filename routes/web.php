<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;

Route::resource('categories', CategoryController::class);
//Route::resource('events', EventController::class);
Route::get('/categories/{category}/events', [CategoryController::class, 'events'])->name('categories.events');
Route::resource('bookings', BookingController::class)->except(['create']);
Route::get('/myBookings', [BookingController::class, 'index'])->name('bookings.index');

// Admin Auth routes
/*Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('register', [AdminController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AdminController::class, 'register']);

    Route::get('login',  [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login']);

    Route::get('logout', [AdminController::class, 'logout'])->name('logout');
}); */

/***** JING YIN EDIT FOR ADMIN!!!!!!!!!!!!! ***********/
/***************** Admin ******************************/
Route::view('/admin/login', 'admin.login')->name('admin.login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');

    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('/events', 'admin.events.index')->name('events.index');
    Route::view('/events/category', 'admin.events.category')->name('events.category');

    
    Route::view('/bookings', 'admin.bookings.index')->name('bookings.index');
    Route::view('/bookings/show', 'admin.bookings.show')->name('bookings.show');
    
    Route::view('/users', 'admin.users.index')->name('users.index');
    Route::view('/users/show', 'admin.users.show')->name('users.show');
    
    Route::view('/categories', 'admin.categories.index')->name('categories.index');
});

Route::prefix('events')->group(function () {
    Route::view('/events', 'events.index')->name('events.index');
    Route::view('/events/show', 'events.show')->name('events.show');
});


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');






/*** me edit part */

// 暂时的， 后续会改成controller的方式
Route::view('/forgotPassword', 'auth.forgotPassword')->name('password.request');

Route::get('/auth/google', function () {
    return 'Google login page';
})->name('auth.google');


/**Route::prefix('events')->group(function () {
    Route::view('/events', 'events.index')->name('events.index');
    Route::view('/events/show', 'events.show')->name('events.show');
});
*/

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
    // function used for testing only, later will be changed to controller method
    Route::get('/bookings/create', function () {
        return view('bookings.create');
    })->name('bookings.create');



    // funtion can be added inside controller later
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

    //Route::view('/admin/events/create', 'admin.events.create')->name('admin.events.create');
    //Route::view('/admin/bookings', 'admin.bookings.index')->name('admin.bookings.index');

});

// check auth status route for testing
Route::get('/test-user', function () {
    return auth('web')->check() ? '已登录' : '未登录';
});

Route::get('/test-admin', function () {
    return auth('admin')->check() ? 'Admin已登录' : 'Admin未登录';
});



/*** end of me edit part */

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
