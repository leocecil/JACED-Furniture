<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = ['name', 'min_points_accumulative', 'discount_percentage', 'additional_perks'];

    protected $casts = [
        'additional_perks' => 'array',
    ];
}
