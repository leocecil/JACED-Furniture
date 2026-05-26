<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $provinces = DB::table('indonesia_provinces')->orderBy('name', 'asc')->get(); 

        return view('profile.addresses', compact('addresses', 'provinces'));
    }
}
