<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function show_login_form()
    {
        return view('auth.login');
    }

    public function login_auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function show_login_admin_form()
    {
        return view('auth.login_admin');
    }

    public function login_admin_auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $isAdmin = $user->roles->pluck('role')->contains('admin');

            if ($isAdmin) {
                $request->session()->regenerate();
                return redirect()->route('dashboard');
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun ini bukan admin.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function show_register_form()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $credentials = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password'     => 'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'name'         => $credentials['name'],
            'email'        => $credentials['email'],
            'phone_number' => $credentials['phone_number'],
            'password'     => Hash::make($credentials['password']),
        ]);

        $user->roles()->create(['role' => 'customer']);

        Auth::login($user);
        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}