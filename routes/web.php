<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherManagementController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

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
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/api/products/batch', [ProductController::class, 'batchProducts']);
Route::get('/wishlist', function () { return view('store.wishlist'); })->name('wishlist');

// Chatbot
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->middleware('throttle:30,1');

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
    Route::post('/profile/{id}/password', [UserController::class, 'update_password'])->name('profile.password.update');
    Route::delete('/profile/{id}/delete', [UserController::class, 'delete_account'])->name('profile.delete');

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
    Route::patch('/cart/{id}/increase', [CartController::class, 'increase'])->name('cart.increase');
    Route::patch('/cart/{id}/decrease', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::delete('/cart/{id}', [CartController::class, 'delete'])->name('cart.delete');
    Route::get('/cart/sidebar', [CartController::class, 'sidebar'])->name('cart.sidebar');

    // WISHLIST (DB-based)
    Route::get('/wishlist/items', [WishlistController::class, 'items']);
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::delete('/wishlist-clear', [WishlistController::class, 'clear'])->name('wishlist.clear');

    // Checkout & Courier Shipping API
    Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout.index');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.store');

    Route::get('/api/cities', [OrderController::class, 'getCities'])->name('api.cities');
    Route::get('/api/districts', [OrderController::class, 'getDistricts'])->name('api.districts');
    Route::get('/api/villages', [OrderController::class, 'getVillages'])->name('api.villages');
    Route::get('/api/postal-code', [OrderController::class, 'getPostalCode']);
    Route::get('/api/shipping-cost', [OrderController::class, 'getShippingCost']);

    // Payment Gateway Status Callback
    Route::get('/payment/status/{order_id}', [OrderController::class, 'payment_status'])->name('payment_status');
    Route::get('/payment/return/{order_id}', [OrderController::class, 'payment_return'])->name('payment_return');

    // Midtrans Webhook for Payment Notifications
    Route::post('/midtrans/notification', [OrderController::class, 'handleNotification'])->name('midtrans.notification');

    // Customer Purchase History
    Route::get('/orderhistory', [OrderHistoryController::class, 'index'])->name('store.orderhistory');
    Route::get('/orderhistory/{id}', [OrderHistoryController::class, 'show'])->name('store.orderhistory_detail.show');
    Route::get('/orderhistory/{id}/invoice', [OrderHistoryController::class, 'invoice'])->name('store.orderhistory.invoice');
    Route::post('/orderhistory/{id}/invoice/send', [OrderHistoryController::class, 'sendInvoice'])->name('store.orderhistory.invoice.send');

    // Order actions - customer
    Route::patch('/orderhistory/{id}/received', [OrderHistoryController::class, 'markReceived'])->name('store.orderhistory.received');
    Route::post('/orderhistory/{id}/complaint', [OrderHistoryController::class, 'submitComplaint'])->name('store.orderhistory.complaint');
    Route::patch('/orderhistory/{id}/cancel', [OrderHistoryController::class, 'cancelOrder'])->name('store.orderhistory.cancel');
    Route::get('/orderhistory/{id}/pay', [OrderHistoryController::class, 'repay'])->name('store.orderhistory.repay');

    // Transaction History alias
    Route::get('/transaction-history', [OrderHistoryController::class, 'index'])->name('store.transactionhistory');
});

// ADMIN LOGIN
Route::get('/admin/login', [AuthController::class, 'show_login_admin_form'])->name('admin.login.show');
Route::post('/admin/login_auth', [AuthController::class, 'login_admin_auth'])->name('admin.login.auth');
Route::post('/admin/logout', [AuthController::class, 'logout_admin'])->name('admin.logout');

// ── MIDDLEWARE ADMIN ROLE ──
Route::middleware(['role:admin'])->group(function() {
    // Admin Profile Management
    Route::get('/admin/profile',            [AdminProfileController::class, 'index'])->name('admin.profile');
    Route::put('/admin/profile/info',       [AdminProfileController::class, 'updateInfo'])->name('admin.profile.info');
    Route::put('/admin/profile/password',   [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');
    Route::post('/admin/profile/avatar',    [AdminProfileController::class, 'uploadAvatar'])->name('admin.profile.avatar');
    Route::post('/profile/verify-password', [AdminProfileController::class, 'verifyPassword'])->name('admin.profile.verify-password');

    // Admin Dashboard Summary
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/sales-chart', [DashboardController::class, 'salesChart'])->name('admin.dashboard.salesChart');
    Route::post('/admin/dashboard/set-target', [DashboardController::class, 'setTarget'])->name('admin.dashboard.setTarget');
    Route::get('/admin/dashboard/stat-cards', [DashboardController::class, 'statCards'])->name('admin.dashboard.statCards');

    // Order Overview & Operational Management
    Route::get('/admin/order_management', [OrderManagementController::class, 'index'])->name('order_management');
    Route::get('/admin/order_management/search', [OrderManagementController::class, 'search'])->name('admin.order_management.search');
    Route::post('/admin/orders/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::post('/admin/disputes/{id}/resolve',  [OrderManagementController::class, 'resolveDispute'])->name('admin.disputes.resolve');
    Route::post('/admin/disputes/{id}/resolved', [OrderManagementController::class, 'markDisputeResolved'])->name('admin.disputes.resolved');
    Route::post('/admin/disputes/{id}/tracking', [OrderManagementController::class, 'updateTracking'])->name('admin.disputes.tracking');

    // Complaint management - admin
    Route::get('/admin/complaints', [OrderManagementController::class, 'complaints'])->name('admin.complaints');
    Route::post('/admin/complaints/{id}/resolve', [OrderManagementController::class, 'resolveComplaint'])->name('admin.complaints.resolve');

    // Core Customer Analytics Systems
    Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('analytics.customers');
    Route::get('/admin/analytics/customers/all', [AnalyticsController::class, 'allCustomers'])->name('analytics.customers.all');
    Route::get('/admin/customer-analytics', [AnalyticsController::class, 'customerAnalyticsPage'])->name('customer.analytics.page');
    Route::get('/admin/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');

    // Inventory Stock Control
    Route::get('/admin/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/admin/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::put('/admin/inventory/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/admin/inventory/image/{image}', [InventoryController::class, 'destroyImage'])->name('inventory.image.destroy');
    Route::delete('/admin/inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::post('/admin/inventory/{id}/restore', [InventoryController::class, 'restore'])->name('inventory.restore');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    
     // Voucher Operational Management Systems
    Route::get('/admin/vouchers',[VoucherManagementController::class, 'index'])->name('admin.vouchers');
    Route::post('/admin/vouchers',[VoucherManagementController::class, 'store'])->name('admin.vouchers.store');
    Route::get('/admin/vouchers/stats',[VoucherManagementController::class, 'stats'])->name('admin.vouchers.stats');
    Route::post('/admin/vouchers/group/toggle',[VoucherManagementController::class, 'toggleGroup'])->name('admin.vouchers.group.toggle');
    // Per-voucher-type routes (by id)
    Route::get('/admin/vouchers/{id}/detail',[VoucherManagementController::class, 'detail'])->name('admin.vouchers.detail');
    Route::post('/admin/vouchers/{id}/toggle',[VoucherManagementController::class, 'toggle'])->name('admin.vouchers.toggle');       // single code toggle
    Route::delete('/admin/vouchers/{id}',[VoucherManagementController::class, 'destroy'])->name('admin.vouchers.destroy');      // delete whole group
    Route::delete('/admin/vouchers/{id}/code',[VoucherManagementController::class, 'destroyCode'])->name('admin.vouchers.code.destroy'); // delete single code

    // Product Category Manager
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])->name('password.email');
Route::get('/reset-password', [ResetPasswordController::class, 'showForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
