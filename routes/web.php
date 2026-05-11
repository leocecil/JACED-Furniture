<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', function () {
    return View::make('welcome');
});

Route::get('/about', function () {
    return view('store.checkout');
});
Route::get('/login', function () {
    return view('auth.login');
});