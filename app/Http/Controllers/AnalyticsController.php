<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Menampilkan halaman Customer Analytics.
     */
    public function index()
    {
        // Menyederhanakan pengiriman data statis (bisa dihubungkan ke DB menggunakan Eloquent nanti)
        $orderCount = 12; // Contoh variabel badge yang dibutuhkan sidebar jika ada

        return view('pages.analytics.index', compact('orderCount'));
    }
}