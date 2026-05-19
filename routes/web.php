<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', function () {
    return View::make('welcome');
});

// Route Diah
Route::get('/checkout', [OrderController::class, 'checkout'])->name('store.checkout');
Route::get('/transactionhistory', [OrderController::class, 'index'])->name('store.transactionhistory');
Route::get('/transactionhistory/{id}', [OrderController::class, 'show'])->name('store.transactionhistory_detail.show');
Route::get('/profile', [UserController::class, 'show_profile'])->name('profile');
Route::get('/profile/edit/{id}', [UserController::class, 'edit_profile'])->name('profile.edit');
Route::get('/terms-of-service', function () {return view('profile.tos');})->name('tos');
Route::get('/privacy-policy', function () {return view('profile.privacy-policy');})->name('privacy');
Route::get('/help-center', function () {return view('profile.help-center');})->name('help');

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
Route::get('/admin/login', [AuthController::class, 'show_login_admin_form'])->name('admin.login.show');

Route::post('/admin/login_auth', [AuthController::class, 'login_admin_auth'])->name('admin.login.auth');

Route::get('/order', [OrderController::class, 'index1'])->name('orders.index1');

Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');
