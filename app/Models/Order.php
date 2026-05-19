<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    protected $fillable = [
        'user_id',
        'payment_id',
        'voucher_id',
        'shipping_address_id',
        'delivery_fee',
        'service_tax',
        'discount_amount',
        'total_price',
        'status',
        // 'packed_at',
        // 'delivered_at',
        // 'arrived_at',
        // 'cancelled_at',
    ];
    // protected $casts = [
    //     'delivery_fee' => 'decimal:2',
    //     'service_tax' => 'decimal:2',
    //     'discount_amount' => 'decimal:2',
    //     'total_price' => 'decimal:2',

    //     'packed_at' => 'datetime',
    //     'delivered_at' => 'datetime',
    //     'arrived_at' => 'datetime',
    //     'cancelled_at' => 'datetime',
    // ];
}
