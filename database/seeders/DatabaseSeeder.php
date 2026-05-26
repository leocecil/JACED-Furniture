<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            UserRoleSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            ProductImageSeeder::class,
            WilayahIndonesiaSeeder::class,
            PaymentMethodSeeder::class,
            VaBankSeeder::class,
            CustomerUserSeeder::class,
            ShippingAddressSeeder::class,
            VoucherTypeSeeder::class,
            OrderSeeder::class,
            OrderDetailSeeder::class,
            StageSeeder::class,
        ]);
    }
}