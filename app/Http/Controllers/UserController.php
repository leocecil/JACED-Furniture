<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

    public function edit_profile($id)
    {
        $user = Auth::user();
        return view('profile.edit-profile', compact('user'));
    }

    public function update_profile(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone_number = $request->phone;

        if ($request->hasFile('avatar')) {
            // Hapus foto lama kalau ada
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit', $user->id)->with('success', 'Profile updated successfully.');
    }

    // ADDRESSES
    public function addresses()
    {
        $user      = Auth::user();
        $addresses = $user->shippingAddresses()
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        $provinces = \App\Models\Province::orderBy('name')->get();

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