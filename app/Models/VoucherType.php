<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'used_for',
        'point_cost',
        'max_discount',
    ];

    protected $casts = [
        'max_discount' => 'decimal:2',
    ];
    
    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }
}
