<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('user_roles')->insert([
            [
                'user_id' => 1, // Assuming user with ID 1 is user1
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2, // Assuming user with ID 2 is user2
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3, // Assuming user with ID 3 is admin and owner
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4, // Assuming user with ID 4 is admin and owner
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5, // Assuming user with ID 5 is admin and owner
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6, // Assuming user with ID 6 is customer
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}