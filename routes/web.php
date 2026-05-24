<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



// LOGIN & REGISTER CUSTOMER
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

Route::get('/login', [AuthController::class, 'show_login_form'])
    ->name('login');

Route::post('/login_auth', [AuthController::class, 'login_auth'])
    ->name('login.auth');

Route::get('/register', [AuthController::class, 'show_register_form'])
    ->name('register.show');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

Route::get('/', [ProductController::class, 'landing'])->name('landing');
Route::get('/home', [ProductController::class, 'home'])->name('home');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');

// MIDDLEWARE CUSTOMER
Route::middleware(['role:customer'])->group(function() {
    Route::get('/profile', [UserController::class, 'show_profile'])->name('profile');
    Route::get('/profile/edit/{id}', [UserController::class, 'edit_profile'])->name('profile.edit');

    Route::get('/terms-of-service', function () {
        return view('profile.tos');
    })->name('tos');
    
    Route::get('/reward', [RewardController::class, 'index'])->name('reward');

    Route::get('/reward/voucher', function () {
        return view('profile.reward-center.voucher');
    })->name('voucher');

    Route::post('/reward/redeem', [RewardController::class, 'redeem'])->name('reward.redeem');
    Route::get('/reward/redeem-point', function () {
        return view('profile.reward-center.redeem-point');
    })->name('redeem-point');
    Route::get('/reward/redeem-point', [RewardController::class, 'redeemPage'])->name('redeem-point');

    Route::get('/reward/point-history', [RewardController::class, 'pointHistory'])->name('point-history');
    Route::post('/reward/use-voucher', [RewardController::class, 'useVoucher'])->name('reward.use-voucher');
    Route::post('/voucher/clear-session', function() {
        session()->forget('pending_voucher_id');
        return response()->json(['ok' => true]);
    })->name('voucher.clear-session');
    Route::get('/reward/voucher', [RewardController::class, 'voucherPage'])->name('voucher');

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

    Route::get('/product_details/{id}', [ProductController::class, 'show'])
    ->name('product_details');

    // Route::get('/product_details', function () {
    //     return view('store.product_details');
    // })->name('product_details');

    // CART
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart');
    Route::patch('/cart/{id}/increase', [CartController::class, 'increase'])
        ->name('cart.increase');
    Route::patch('/cart/{id}/decrease', [CartController::class, 'decrease'])
        ->name('cart.decrease');
    Route::delete('/cart/{id}', [CartController::class, 'delete'])
        ->name('cart.delete');

    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

    // CART
    Route::get('/api/cart', [CartController::class, 'index']);
    Route::post('/api/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/api/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/api/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    
    //CHECKOUT
    Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout.index');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.store');
    Route::get('/api/cities', [OrderController::class, 'getCities'])->name('api.cities');
    Route::get('/api/districts', [OrderController::class, 'getDistricts'])->name('api.districts');
    Route::get('/api/villages', [OrderController::class, 'getVillages'])->name('api.villages');
    Route::get('/api/shipping-cost', [OrderController::class, 'getShippingCost']);

    // CART
    Route::get('/api/cart', [CartController::class, 'index']);
    Route::post('/api/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/api/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/api/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // SHIPPING API
    Route::get('/api/districts', [OrderController::class, 'getDistricts'])->name('api.districts');
    Route::get('/api/villages', [OrderController::class, 'getVillages'])->name('api.villages');
    Route::get('/api/shipping-cost', [OrderController::class, 'getShippingCost']);

    Route::get('/payment/status/{order_id}', [OrderController::class, 'payment_status'])->name('payment_status');
    Route::get('/payment/return/{order_id}', [OrderController::class, 'payment_return'])->name('payment_return');

    Route::get('/orderhistory', [OrderHistoryController::class, 'index'])
        ->name('store.orderhistory');

    Route::get('/transactionhistory/{id}', [OrderController::class, 'show'])
        ->name('store.transactionhistory_detail.show');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ADMIN LOGIN
Route::get('/admin/login', [AuthController::class, 'show_login_admin_form'])
        ->name('admin.login.show');

Route::post('/admin/login_auth', [AuthController::class, 'login_admin_auth'])
    ->name('admin.login.auth');

Route::post('/admin/logout', [AuthController::class, 'logout_admin'])
    ->name('admin.logout');
    
// MIDDLEWARE ADMIN
Route::middleware(['role:admin'])->group(function() {

    Route::get('/admin/main', [OrderController::class, 'index'])->name('orders.index');

Route::get('/admin/inventory', [InventoryController::class, 'index'])->name('inventory.index');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard/sales-chart', [DashboardController::class, 'salesChart'])->name('admin.dashboard.salesChart');
Route::post('/admin/dashboard/set-target', [DashboardController::class, 'setTarget'])->name('admin.dashboard.setTarget');

Route::get('/admin/order_management', [OrderManagementController::class, 'index'])->name('order_management');
Route::get('/admin/order_management/search', [OrderManagementController::class, 'search'])->name('admin.order_management.search');
Route::post('/admin/orders/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
}); 
Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('analytics.customers');
Route::get('/admin/inventory', [InventoryController::class, 'index'])->name('inventory.index');

