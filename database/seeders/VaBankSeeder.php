<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VaBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('va_banks')->insert([
            ['name' => 'bca',     'created_at' => now(), 'updated_at' => now()],
            ['name' => 'mandiri', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'bni',     'created_at' => now(), 'updated_at' => now()],
            ['name' => 'bri',     'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
