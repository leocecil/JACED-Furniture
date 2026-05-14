<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ===== HOME / SHOP =====
Route::redirect('/', '/home');

Route::get('/home', [ProductController::class, 'home'])->name('home');

Route::get('/shop', [ProductController::class, 'shop'])->name('shop');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// ===== ORDER =====
Route::get('/checkout', [OrderController::class, 'checkout'])->name('store.checkout');

Route::get('/transactionhistory', [OrderController::class, 'index'])->name('store.transactionhistory');

Route::get('/transactionhistory/{id}', [OrderController::class, 'show'])
    ->name('store.transactionhistory_detail.show');

// ===== PRODUCT DETAILS =====
Route::get('/product_details', function () {
    return view('store.product_details');
})->name('product_details');

// ===== AUTH =====
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'show_login_form'])
        ->name('login');

    Route::post('/login_auth', [AuthController::class, 'login_auth'])
        ->name('login.auth');

    Route::get('/register', [AuthController::class, 'show_register_form'])
        ->name('register.show');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::get('/admin/login', [AuthController::class, 'show_login_admin_form'])
        ->name('admin.login.show');

    Route::post('/admin/login_auth', [AuthController::class, 'login_admin_auth'])
        ->name('admin.login.auth');
});

// ===== LOGOUT =====
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// ===== ADMIN =====
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');