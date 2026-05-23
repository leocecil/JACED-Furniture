<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            ['name' => 'qris',            'created_at' => now(), 'updated_at' => now()],
            ['name' => 'virtual_account', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'credit_card',     'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ovo',             'created_at' => now(), 'updated_at' => now()],
            ['name' => 'dana',            'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
