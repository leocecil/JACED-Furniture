<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
// use session;

/*
|--------------------------------------------------------------------------
| Web Routes — Jaced Furniture
|--------------------------------------------------------------------------
*/

// ── LOGIN & REGISTER CUSTOMER ──
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
Route::get('/login', [AuthController::class, 'show_login_form'])->name('login');
Route::post('/login_auth', [AuthController::class, 'login_auth'])->name('login.auth');
Route::get('/register', [AuthController::class, 'show_register_form'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── PUBLIC VIEW SHOP ──
Route::get('/', [ProductController::class, 'landing'])->name('landing');
Route::get('/home', [ProductController::class, 'home'])->name('home');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/wishlist', function () { return view('store.wishlist'); })->name('wishlist');

// ── MIDDLEWARE CUSTOMER ROLE ──
Route::middleware(['role:customer'])->group(function() {
    // Profile Management
    Route::get('/profile', [UserController::class, 'show_profile'])->name('profile');
    Route::get('/profile/edit/{id}', [UserController::class, 'edit_profile'])->name('profile.edit');
    Route::post('/profile/edit/{id}', [UserController::class, 'update_profile'])->name('profile.update');
    Route::delete('/profile/avatar/{id}', [UserController::class, 'delete_avatar'])->name('profile.avatar.delete');
    Route::get('/profile/addresses', [UserController::class, 'addresses'])->name('profile.addresses');
    Route::post('/profile/addresses', [UserController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::put('/profile/addresses/{id}', [UserController::class, 'updateAddress'])->name('profile.addresses.update');
    Route::delete('/profile/addresses/{id}', [UserController::class, 'destroyAddress'])->name('profile.addresses.destroy');
    Route::patch('/profile/addresses/{id}/default', [UserController::class, 'setDefaultAddress'])->name('profile.addresses.default');

    // Informational Static Pages
    Route::get('/terms-of-service', function () { return view('profile.tos'); })->name('tos');
    Route::get('/privacy-policy', function () { return view('profile.privacy-policy'); })->name('privacy');
    Route::get('/help-center', function () { return view('profile.help-center'); })->name('help');

    // Reward & Loyalty Points Center
    Route::get('/reward', [RewardController::class, 'index'])->name('reward');
    Route::post('/reward/redeem', [RewardController::class, 'redeem'])->name('reward.redeem');
    Route::get('/reward/redeem-point', [RewardController::class, 'redeemPage'])->name('redeem-point');
    Route::get('/reward/point-history', [RewardController::class, 'pointHistory'])->name('point-history');
    Route::get('/reward/voucher', [RewardController::class, 'voucherPage'])->name('voucher');
    Route::post('/reward/use-voucher', [RewardController::class, 'useVoucher'])->name('reward.use-voucher');
    Route::post('/voucher/clear-session', function() {
        session()->forget('pending_voucher_id');
        return response()->json(['ok' => true]);
    })->name('voucher.clear-session');

    // CART
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}/increase', [CartController::class, 'increase'])
        ->name('cart.increase');
    Route::patch('/cart/{id}/decrease', [CartController::class, 'decrease'])
        ->name('cart.decrease');
    Route::delete('/cart/{id}', [CartController::class, 'delete'])
        ->name('cart.delete');

    Route::get('/api/cart', [CartController::class, 'index']);
    Route::post('/api/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/api/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/api/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout & Courier Shipping API
    Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout.index');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.store');

    Route::get('/api/cities', [OrderController::class, 'getCities'])->name('api.cities');
    Route::get('/api/districts', [OrderController::class, 'getDistricts'])->name('api.districts');
    Route::get('/api/villages', [OrderController::class, 'getVillages'])->name('api.villages');
    Route::get('/api/shipping-cost', [OrderController::class, 'getShippingCost']);

    // Payment Gateway Status Callback
    Route::get('/payment/status/{order_id}', [OrderController::class, 'payment_status'])->name('payment_status');
    Route::get('/payment/return/{order_id}', [OrderController::class, 'payment_return'])->name('payment_return');

    // Customer Purchase History
    Route::get('/orderhistory', [OrderHistoryController::class, 'index'])->name('store.orderhistory');
    Route::get('/orderhistory/{id}', [OrderHistoryController::class, 'show'])->name('store.orderhistory_detail.show');

    // Order History - Invoice
    Route::get('/orderhistory/{id}/invoice', [OrderHistoryController::class, 'invoice'])->name('store.orderhistory.invoice');
});

// ADMIN LOGIN
Route::get('/admin/login', [AuthController::class, 'show_login_admin_form'])->name('admin.login.show');
Route::post('/admin/login_auth', [AuthController::class, 'login_admin_auth'])->name('admin.login.auth');
Route::post('/admin/logout', [AuthController::class, 'logout_admin'])->name('admin.logout');
    
// ── MIDDLEWARE ADMIN ROLE ──
Route::middleware(['role:admin'])->group(function() {
    // Admin Dashboard Summary
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/sales-chart', [DashboardController::class, 'salesChart'])->name('admin.dashboard.salesChart');
    Route::post('/admin/dashboard/set-target', [DashboardController::class, 'setTarget'])->name('admin.dashboard.setTarget');

    // Order Overview & Operational Management
    Route::get('/admin/main', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/admin/order_management', [OrderManagementController::class, 'index'])->name('order_management');
    Route::get('/admin/order_management/search', [OrderManagementController::class, 'search'])->name('admin.order_management.search');
    Route::post('/admin/orders/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // CORE CUSTOMER ANALYTICS SYSTEMS (Sudah Sinkron & Dinamis)
    Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('analytics.customers');
    Route::get('/admin/analytics/customers/all', [AnalyticsController::class, 'allCustomers'])->name('analytics.customers.all');

    // Inventory Stock Control
    Route::get('/admin/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/admin/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::put('/admin/inventory/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/admin/inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::delete('/admin/inventory/image/{image}', [InventoryController::class, 'destroyImage'])->name('inventory.image.destroy');

    // Product Category Manager
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});
