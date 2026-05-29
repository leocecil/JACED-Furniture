<?php

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    public function run(): void
    {
        Stage::create(['name' => 'Bronze',   'min_points_accumulative' => 0,     'discount_percentage' => 0]);
        Stage::create(['name' => 'Silver',   'min_points_accumulative' => 5000,  'discount_percentage' => 5]);
        Stage::create(['name' => 'Gold',     'min_points_accumulative' => 20000, 'discount_percentage' => 10]);
        Stage::create(['name' => 'Platinum', 'min_points_accumulative' => 50000, 'discount_percentage' => 15]);
    }
}
