<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', [ProductController::class, 'home'])->name('home');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/checkout', function () {
    return view('store.checkout');
});

Route::get('/transactionhistory', function () {
    return view('store.transaction_history');
});

Route::get('/profile', function () {
    return 'Profile page belum dibuat.';
})->name('profile');