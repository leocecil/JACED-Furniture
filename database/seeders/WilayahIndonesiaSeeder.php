<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WilayahIndonesiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Urutan sangat penting karena ada relasi Foreign Key
        $tables = [
            'provinces.sql',
            'cities.sql',
            'districts.sql',
            'villages.sql',
        ];

        foreach ($tables as $table) {
            $path = database_path('seeders/data/' . $table);
            
            // Cek apakah file SQL ada
            if (File::exists($path)) {
                $sql = File::get($path);
                DB::unprepared($sql);
                $this->command->info("Seeding {$table} berhasil!");
            } else {
                $this->command->error("File {$table} tidak ditemukan di {$path}");
            }
        }
    }
}