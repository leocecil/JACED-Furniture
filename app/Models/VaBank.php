<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaBank extends Model
{
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
