<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        // Cek role dari tabel user_roles
        $isAdmin = $user->roles()->where('role', 'admin')->exists();
        // atau kalau pakai kolom is_admin di tabel users:
        // $isAdmin = $user->is_admin;
        
        $role = $isAdmin ? 'admin' : 'customer';

        $token = Password::createToken($user);
        $user->notify(new \App\Notifications\CustomResetPassword($token, $role));

        return back()->with('success', 'A password reset link has been sent to your email!');
    }
}