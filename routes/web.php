<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'home'])->name('home');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/checkout', function () {
    return view('store.checkout');
})->name('checkout');

Route::get('/login', function () {
    return 'Login page belum dibuat. Bikin nanti pake Laravel Breeze atau auth scaffold lo sendiri.';
})->name('login');

Route::get('/profile', function () {
    return 'Profile page belum dibuat.';
})->name('profile');