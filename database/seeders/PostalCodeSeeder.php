<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostalCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $json = file_get_contents(database_path('seeders/data/postal_codes.json'));
        $data = json_decode($json, true);

        // Masukkan datanya per batch biar cepat
        foreach (array_chunk($data, 1000) as $chunk) {
            DB::table('postal_codes')->insert($chunk);
        }
    }
}
