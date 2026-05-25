<?php

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    public function run(): void
    {
        Stage::create([
            'name' => 'Bronze',
            'min_points_accumulative' => 0, // Rp 0
            'discount_percentage' => 0,
            'additional_perks' => ['5% Birthday Discount', 'Early Access to Sales']
        ]);

        Stage::create([
            'name' => 'Silver',
            'min_points_accumulative' => 5000, // Rp 50 Juta
            'discount_percentage' => 5,
            'additional_perks' => ['5% Transaction Discount', '10% Birthday Discount']
        ]);

        Stage::create([
            'name' => 'Gold',
            'min_points_accumulative' => 20000, // Rp 200 Juta
            'discount_percentage' => 10,
            'additional_perks' => ['10% Transaction Discount', 'Free Shipping store-wide']
        ]);

        Stage::create([
            'name' => 'Platinum',
            'min_points_accumulative' => 50000, // Rp 500 Juta
            'discount_percentage' => 15,
            'additional_perks' => ['15% Transaction Discount', '1-on-1 Interior Consultation']
        ]);
    }
}
