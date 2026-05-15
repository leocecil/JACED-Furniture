<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users')->insert([
            [
                'name' => 'diah',
                'email' => 'pdiahloka@ciputra.ac.id',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'cecil',
                'email' => 'cagustaleo@ciputra.ac.id',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'jolie',
                'email' => 'jocelynjolie01@student.ciputra.ac.id',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'audric',
                'email' => 'iaudricwijaya@student.ciputra.ac.id',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ekkin',
                'email' => 'ekenneth01@student.ciputra.ac.id',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customer',
                'email' => 'customer@gmail.com',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}