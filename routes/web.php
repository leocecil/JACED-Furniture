<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'home'])->name('home');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/checkout', function () {
    return view('store.checkout');
});

Route::get('/transactionhistory', function () {
    return view('store.transaction_history');
});

Route::get('/login', [AuthController::class, 'show_login_form'])
    ->name('login')
    ->middleware('guest');

Route::get('/profile', function () {
    return 'Profile page belum dibuat.';
})->name('profile');

Route::get('/home', [ProductController::class, 'home']);