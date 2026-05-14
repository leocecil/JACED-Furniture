<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', function () {
    return View::make('welcome');
});

// Route Diah
Route::get('/checkout', [OrderController::class, 'checkout'])->name('store.checkout');
Route::get('/transactionhistory', [OrderController::class, 'index'])->name('store.transactionhistory');
Route::get('/transactionhistory/{id}', [OrderController::class, 'show'])->name('store.transactionhistory_detail.show');
// -- sampe sini --

// Route::get('/checkout', [ProductController::class, 'checkout'])->name('checkout');


Route::get('/login', [AuthController::class, 'show_login_form'])->name('login.show')->middleware('guest');

Route::post('/login_auth', [AuthController::class, 'login_auth'])->name('login.auth');

Route::get('/register', [AuthController::class, 'show_register_form'])->name('register.show')->middleware('guest');

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/product_details', function() {
            return view('store.product_details');
        })->name('product_details');

// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\ProductController;
// use Illuminate\Support\Facades\Route;

// Route::get('/', [ProductController::class, 'home'])->name('home');
// Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
// Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');





// Route::get('/login', [AuthController::class, 'show_login_form'])
//     ->name('login')
//     ->middleware('guest');

// Route::get('/profile', function () {
//     return 'Profile page belum dibuat.';
// })->name('profile');

// Route::get('/home', [ProductController::class, 'home']); -->
