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
        $tables = [
            'provinces.sql',
            'cities.sql',
            'districts.sql',
            'villages.sql',
        ];

        foreach ($tables as $table) {
            $path = database_path('seeders/data/' . $table);
            
            if (File::exists($path)) {
                $sql = File::get($path);
                DB::unprepared($sql);
                $this->command->info("Seeding {$table} berhasil!");
            } else {
                $this->command->error("File {$table} tidak ditemukan di {$path}");
            }
        }

        $postalPath = database_path('seeders/data/postal_codes.json');

        if (File::exists($postalPath)) {
            $this->command->info("Sedang menyuntikkan data kode pos ke tabel villages, mohon tunggu...");
            
            $json = File::get($postalPath);
            $postalData = json_decode($json, true);

            // Kita lakukan update per batch (per 1000 data) agar server lokal tidak crash/lelet
            $chunks = array_chunk($postalData, 1000);

            foreach ($chunks as $chunk) {
                foreach ($chunk as $item) {
                    // Kita cari record berdasarkan 'code' kelurahan, lalu kita isi kolom 'postal_code'
                    // Catatan: Jika di file json milikmu namanya bukan 'village_code' atau 'postal_code', sesuaikan dengan key JSON kamu ya!
                    DB::table('indonesia_villages')
                        ->where('code', $item['village_code']) 
                        ->update(['postal_code' => $item['postal_code']]);
                }
            }
            $this->command->info("Suntik kode pos ke tabel villages sukses besar!");
        } else {
            $this->command->error("File postal_codes.json tidak ditemukan! Proses suntik kode pos dilewati.");
        }
    }
}