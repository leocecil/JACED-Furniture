<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', function () {
    return View::make('welcome');
});

Route::get('/about', function () {
    return view('store.checkout');
});
