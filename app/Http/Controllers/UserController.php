<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show_profile() {
        // Kita buat object dummy menggunakan 'stdClass' 
        // supaya menyerupai data dari database/model
        $user = (object) [
            'name' => 'Putu Diahloka Mahaputri',
            'id' => 1,
            'email' => 'pdiahloka@student.ciputra.ac.id',
            'avatar' => null, // akan pakai placeholder di blade
            'created_at' => now(),
            'artisan_points' => 1250,
            'vouchers_count' => 3
        ];

        // Kirim variabel $user ke view profil kamu
        return view('profile.profile', compact('user')); 
    }

    public function edit_profile($id) {
    // Nanti kalau sudah ada database, kodenya: $user = User::findOrFail($id);
    
    // Sementara pakai data dummy agar View tidak error:
    $user = (object) [
        'id' => $id,
        'name' => 'Putu Diahloka',
        'email' => 'putu@jaced.com',
        'phone' => '08123456789',
        'address' => 'Jl. Jaced No. 1',
        'city' => 'Denpasar',
        'province' => 'Bali',
        'postal_code' => '80111'
    ];

    return view('profile.edit-profile', compact('user'));
    }
}
