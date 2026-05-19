<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



// LOGIN & REGISTER CUSTOMER
Route::get('/login', [AuthController::class, 'show_login_form'])
    ->name('login');

Route::post('/login_auth', [AuthController::class, 'login_auth'])
    ->name('login.auth');

Route::get('/register', [AuthController::class, 'show_register_form'])
    ->name('register.show');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register');
    
// MIDDLEWARE CUSTOMER
Route::middleware(['role:customer'])->group(function() {
    Route::get('/profile', [UserController::class, 'show_profile'])->name('profile');
    Route::get('/profile/edit/{id}', [UserController::class, 'edit_profile'])->name('profile.edit');

    Route::get('/terms-of-service', function () {
        return view('profile.tos');
    })->name('tos');
    
    Route::get('/reward', function () {
        return view('profile.reward-center.reward');
    })->name('reward');

    Route::get('/reward/voucher', function () {
        return view('profile.reward-center.voucher');
    })->name('voucher');

    Route::get('/reward', function () {
        return view('profile.reward-center.reward');
    })->name('reward');

    Route::get('/reward/voucher', function () {
        return view('profile.reward-center.voucher');
    })->name('voucher');

    Route::get('/reward/redeem-point', function () {
        return view('profile.reward-center.redeem-point');
    })->name('redeem-point');

    Route::get('/reward/point-history', function () {
        return view('profile.reward-center.point-history');
    })->name('point-history');

    Route::get('/terms-of-service', function () {
        return view('profile.tos');
    })->name('tos');
    Route::get('/terms-of-service', function () {
        return view('profile.tos');
    })->name('tos');

    Route::get('/privacy-policy', function () {
        return view('profile.privacy-policy');
    })->name('privacy');

    Route::get('/help-center', function () {
        return view('profile.help-center');
    })->name('help');

    Route::get('/product_details', function () {
        return view('store.product_details');
    })->name('product_details');

    Route::get('/cart', function () {
        return view('store.cart');
    })->name('cart');

    Route::get('/home', [ProductController::class, 'home'])->name('home');
    Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
    
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

    
    //CHECKOUT
    // 1. Rute untuk TAMPILAN CHECKOUT (GET)
    Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout.index');
    // 2. Rute untuk PROSES BAYAR ke Midtrans saat tombol diklik (POST)
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.store');
    // 3. Rute API AJAX untuk ambil data kota
    Route::get('/api/cities', [OrderController::class, 'getCities'])->name('api.cities');

    Route::get('/payment/status/{order_id}', [OrderController::class, 'payment_status'])->name('payment_status');
    Route::get('/payment/return/{order_id}', [OrderController::class, 'payment_return'])->name('payment_return');

    Route::get('/transactionhistory', [OrderController::class, 'index'])
        ->name('store.transactionhistory');

    Route::get('/transactionhistory/{id}', [OrderController::class, 'show'])
        ->name('store.transactionhistory_detail.show');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ADMIN LOGIN
Route::get('/admin/login', [AuthController::class, 'show_login_admin_form'])
        ->name('admin.login.show');

Route::post('/admin/login_auth', [AuthController::class, 'login_admin_auth'])
    ->name('admin.login.auth');
    
// MIDDLEWARE ADMIN
Route::middleware(['role:admin'])->group(function() {

    Route::get('/admin/main', [OrderController::class, 'index'])->name('orders.index');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
}); 