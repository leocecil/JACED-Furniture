<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ===== HOME, SHOP, PRODUCT (punya gue, ProductController) =====
Route::redirect('/', '/home');
Route::get('/home', [ProductController::class, 'home'])->name('home');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// ===== STATIC PAGE (punya temen) =====
Route::get('/checkout', function () {
    return view('store.checkout');
})->name('checkout');

Route::get('/transactionhistory', function () {
    return view('store.transaction_history');
})->name('transaction.history');

Route::get('/product_details', function () {
    return view('store.product_details');
})->name('product_details');

// ===== AUTH (punya temen, plus tambahan) =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show_login_form'])->name('login');
    Route::post('/login_auth', [AuthController::class, 'login_auth'])->name('login.auth');
    Route::get('/register', [AuthController::class, 'show_register_form'])->name('register.show');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::get('/admin/login', [AuthController::class, 'show_login_admin_form'])->name('admin.login.show');
    Route::post('/admin/login_auth', [AuthController::class, 'login_admin_auth'])->name('admin.login.auth');
});

// Logout pake POST biar lebih aman (sesuain header lo yg pake form POST)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== ADMIN (punya temen) =====
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');