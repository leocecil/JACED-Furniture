<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        //
        DB::table('users')->insert([
            [
                'name' => 'diah',
                'email' => 'pdiahloka@student.ciputra.ac.id',
                'phone_number' => '081234567891',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'cecil',
                'email' => 'cagustaleo@student.ciputra.ac.id',
                'phone_number' => '081234567892',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'jolie',
                'email' => 'jocelynjolie01@student.ciputra.ac.id',
                'phone_number' => '081234567893',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'audric',
                'email' => 'iaudricwijaya@student.ciputra.ac.id',
                'phone_number' => '081234567894',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ekkin',
                'email' => 'ekenneth01@student.ciputra.ac.id',
                'phone_number' => '081234567895',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customer',
                'email' => 'customer@gmail.com',
                'phone_number' => '081234567896',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}