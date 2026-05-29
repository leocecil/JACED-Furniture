<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherType extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'used_for',
        'point_cost',
        'discount_percentage',
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
