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
        return $this->belongsTo(PaymentMethod::class, 'payment_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function vaBank()
    {
        return $this->belongsTo(VaBank::class);
    }

    protected $fillable = [
        'user_id',
        'payment_id',
        'va_bank_id',
        'voucher_id',
        'shipping_address_id',
        'delivery_fee',
        'service_tax',
        'discount_amount',
        'total_price',
        'status',
        'midtrans_order_id',
        'cancellation_reason',
        'on_process_at',
        'packed_at',
        'delivered_at',
        'shipped_at',
        'arrived_at',
        'cancelled_at',
        'disputed_at',
        'refund_status',
        'refund_type',
        'refund_amount',
        'tier_discount_amount',
        'stage_id',
    ];

    protected $casts = [
        'on_process_at' => 'datetime',
        'packed_at'     => 'datetime',
        'delivered_at'  => 'datetime',
        'shipped_at'    => 'datetime',
        'arrived_at'    => 'datetime',
        'cancelled_at'  => 'datetime',
        'disputed_at'   => 'datetime',
    ];
}
