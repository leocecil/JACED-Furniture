<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display the About Us page.
     * Accessible in Laravel 13 using standard routing.
     */
    public function index()
    {
        return view('store.about');
    }
}
