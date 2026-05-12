<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@jaced.test'],
            [
                'name' => 'Admin Jaced',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Surabaya, Jawa Timur',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@jaced.test'],
            [
                'name' => 'Ekk Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081298765432',
                'address' => 'Kapasari, Surabaya',
                'email_verified_at' => now(),
            ]
        );
    }
}