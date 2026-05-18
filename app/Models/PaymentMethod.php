<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function vaBanks()
    {
        return $this->hasMany(VaBank::class);
    }   
}
