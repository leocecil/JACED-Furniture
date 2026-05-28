<?php

namespace App\Http\Controllers;

use App\Models\Stage; // Tambahkan import model Stage di atas
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\Province;

class UserController extends Controller
{
    public function index()
    {
        return view('profile.profile', [
            'user' => auth()->user()
        ]);
    }

    public function show_profile()
    {
        $user = Auth::user();
        $user->loadCount('shippingAddresses');

        return view('profile.profile', compact('user'));
    }

    // UPDATE: Ambil stage asli user agar visualisasi tier di edit profile sinkron
    public function edit_profile($id)
    {
        $user = Auth::user();

        // Cari stage yang aktif berdasarkan accumulative points miliknya
        $stageModel = Stage::where('min_points_accumulative', '<=', $user->accumulated_points ?? 0)
                            ->orderBy('min_points_accumulative', 'desc')
                            ->first();
        
        $stage = $stageModel ? $stageModel->name : 'Bronze';

        return view('profile.edit-profile', compact('user', 'stage'));
    }

    // UPDATE: Menyelaraskan penamaan 'avatar' menjadi 'profile_picture' sesuai form HTML blade
    public function update_profile(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'phone'           => 'nullable|string|max:20',
            'password'        => 'nullable|min:8|confirmed',
            'avatar'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Diubah dari 'avatar'
        ]);

        $user->name        = $request->name;
        $user->email       = $request->email;
        $user->phone_number = $request->phone;

        // Proses penyimpanan file gambar ke database menggunakan kolom 'avatar'
        if ($request->hasFile('avatar')) {
            if ($user->avatar !== 'image/avatars/default_avatar.png') {
                if (file_exists(public_path($user->avatar))) {
                    $oldPhoto = $user->photo;

                    if ($oldPhoto && $oldPhoto !== 'default.jpg') {
                        $oldPhotoPath = public_path($oldPhoto);
                        
                        if (file_exists($oldPhotoPath) && is_file($oldPhotoPath)) {
                            unlink($oldPhotoPath);
                        }
                    }
                }
            }

            $filename = time() . '.' . $request->file('avatar')->extension();
            $request->file('avatar')->move(public_path('image/avatars'), $filename);
            $user->avatar = 'image/avatars/' . $filename;
        }

        $passwordChanged = false;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $passwordChanged = true;
        }

        $user->save();

        // Fitur Tambahan: Jika ganti password, otomatis logout & suruh login ulang demi keamanan
        if ($passwordChanged) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('success', 'Password changed successfully. Please log in with your new credentials.');
        }

        return redirect()->route('profile.edit', $user->id)->with('success', 'Profile updated successfully.');
    }

    public function delete_avatar($id)
    {
        $user = Auth::user();

        if ($user->avatar !== 'image/avatars/default_avatar.png') {
            if (file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }
            $user->avatar = 'image/avatars/default_avatar.png';
            $user->save();
        }

        return redirect()->route('profile.edit', $user->id)->with('success', 'Photo removed.');
    }

    // --- MANAJEMEN ALAMAT (TETAP AMAN & TIDAK BERUBAH) ---
    public function addresses()
    {
        $user      = Auth::user();
        $addresses = $user->shippingAddresses()
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        $provinces = Province::orderBy('name')->get();

        return view('profile.addresses', compact('addresses', 'provinces'));
    }

    public function storeAddress(Request $request)
    {
        $user = Auth::user();
        $isFirst = $user->shippingAddresses()->count() === 0;

        $user->shippingAddresses()->create([
            'receiver_name'  => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'address_line1'  => $request->address_line1,
            'province_code'  => $request->province_code,
            'province_name'  => $request->province_name,
            'city_code'      => $request->city_code,
            'city_name'      => $request->city_name,
            'district_code'  => $request->district_code ?? '',
            'district_name'  => $request->district_name ?? '',
            'village_code'   => $request->village_code ?? '',
            'village_name'   => $request->village_name ?? '',
            'postal_code'    => $request->postal_code,
            'is_default'     => $isFirst,
        ]);

        return redirect()->route('profile.addresses')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function updateAddress(Request $request, $id)
    {
        $address = Auth::user()->shippingAddresses()->findOrFail($id);

        $address->update([
            'receiver_name'  => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'address_line1'  => $request->address_line1,
            'province_code'  => $request->province_code,
            'province_name'  => $request->province_name,
            'city_code'      => $request->city_code,
            'city_name'      => $request->city_name,
            'district_code'  => $request->district_code ?? '',
            'district_name'  => $request->district_name ?? '',
            'village_code'   => $request->village_code ?? '',
            'village_name'   => $request->village_name ?? '',
            'postal_code'    => $request->postal_code,
        ]);

        return redirect()->route('profile.addresses')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroyAddress($id)
    {
        $address    = Auth::user()->shippingAddresses()->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $next = Auth::user()->shippingAddresses()->first();
            if ($next) $next->update(['is_default' => true]);
        }

        return redirect()->route('profile.addresses')->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefaultAddress($id)
    {
        $user = Auth::user();
        $user->shippingAddresses()->update(['is_default' => false]);
        $user->shippingAddresses()->findOrFail($id)->update(['is_default' => true]);

        return redirect()->route('profile.addresses')->with('success', 'Alamat utama berhasil diubah.');
    }
}