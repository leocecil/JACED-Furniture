<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Province extends Model
{
    protected $table = 'indonesia_provinces';
    public function cities()
    {
        return $this->hasMany(City::class, 'province_code', 'code');
    }

    public function addresses()
    {
        $user      = Auth::user();
        $addresses = $user->shippingAddresses()
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        $provinces = Province::orderBy('name', 'asc')->get(); // pakai Laravolt

        return view('profile.addresses', compact('addresses', 'provinces'));
    }
}
