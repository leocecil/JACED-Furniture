<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    public function showForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->query('token'),
            'email' => $request->query('email'),
            'role'  => $request->query('role')
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => bcrypt($password)])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $role = $request->input('role', 'customer');

            return $role === 'admin'
                ? redirect()->route('admin.login.show')->with('success', 'Your password has been reset successfully. Please sign in with your new password.')
                : redirect()->route('login')->with('success', 'Your password has been reset successfully. Please sign in with your new password.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}