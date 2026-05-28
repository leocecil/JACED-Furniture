<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google.');
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        // Kalau belum ada akun → arahkan ke register, jangan auto-create
        if (!$user) {
            return redirect()->route('register')->with('info', 'Akun tidak ditemukan. Silakan daftar terlebih dahulu.');
        }

        // Update data Google terbaru
        $user->update([
            'google_id' => $googleUser->getId(),
            'avatar'    => $googleUser->getAvatar(),
        ]);

        Auth::login($user);

        // Cek role: kalau admin → arahkan ke admin dashboard
        $isAdmin = $user->roles->pluck('role')->contains('admin');

        if ($isAdmin) {
            return redirect()->route('admin.dashboard');
        }

        // Kalau customer → arahkan ke home
        return redirect()->intended('/home');
    }
}