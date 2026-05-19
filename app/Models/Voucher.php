<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'voucher_type_id',
        'user_id',
        'expiry_date',
        'redeemed_at',
        'is_active',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'redeemed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function voucherType()
    {
        return $this->belongsTo(VoucherType::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
