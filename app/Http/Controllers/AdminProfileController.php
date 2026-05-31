<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile page.
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.admin_profile', compact('user'));
    }

    /**
     * Update personal information (name, email, phone).
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['required', 'string', 'max:20'],
        ]);

        $user->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return back()->with('info_success', 'Profile information updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('password_success', 'Password updated successfully.');
    }

    /**
     * Upload / update avatar.
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = Auth::user();

        // Delete old avatar if it exists (and is not the default)
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        // Mark avatar_rewarded if not yet done
        if (!$user->avatar_rewarded) {
            $user->update(['avatar_rewarded' => true]);
        }

        return back()->with('avatar_success', 'Avatar updated successfully.');
    }
}