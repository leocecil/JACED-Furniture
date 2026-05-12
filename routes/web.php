<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', function () {
    return View::make('welcome');
});

Route::get('/about', function () {
    return view('store.checkout');
});

Route::get('/transactionhistory', function () {
    return view('store.transaction_history');
});

Route::get('/login', [AuthController::class, 'show_login_form'])->name('login.show')->middleware('guest');

Route::post('/login_auth', [AuthController::class, 'login_auth'])->name('login.auth');

Route::get('/register', [AuthController::class, 'show_register_form'])->name('register.show')->middleware('guest');

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
