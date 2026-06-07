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
     * AJAX: verify current password before allowing change.
     * Returns JSON { valid: bool, message?: string }
     */
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
        ]);

        $valid = Hash::check($request->current_password, Auth::user()->password);

        return response()->json([
            'valid'   => $valid,
            'message' => $valid ? null : 'The current password is incorrect.',
        ]);
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
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
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

        // Hapus avatar lama jika ada
        if ($user->avatar && file_exists(public_path($user->avatar))) {
            unlink(public_path($user->avatar));
        }

        // Buat folder jika belum ada
        if (!file_exists(public_path('images/avatars'))) {
            mkdir(public_path('images/avatars'), 0755, true);
        }

        $file     = $request->file('avatar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/avatars'), $filename);

        $path = 'images/avatars/' . $filename; // ← path konsisten

        $user->update(['avatar' => $path]);

        if (!$user->avatar_rewarded) {
            $user->update(['avatar_rewarded' => true]);
        }

        return back()->with('avatar_success', 'Avatar updated successfully.');
    }
}