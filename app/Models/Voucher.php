<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    public function voucherType()
    {
        return $this->belongsTo(VoucherType::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
