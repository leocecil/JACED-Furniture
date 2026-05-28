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
            return redirect()->route('login')->with('error', 'Failed to sign in with Google.');
        }

        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if (!$user) {
            session([
                'google_data' => [
                    'name'  => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                ]
            ]);

            return redirect()->route('register')->with('info', 'No account found. Please complete your registration below.');
        }

        $user->update([
            'google_id' => $googleUser->getId(),
            'avatar'    => $googleUser->getAvatar(),
        ]);

        Auth::login($user);

        $isAdmin = $user->roles->pluck('role')->contains('admin');

        if ($isAdmin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended('/home');
    }
}