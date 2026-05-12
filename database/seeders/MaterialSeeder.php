<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            ['name' => 'Walnut', 'slug' => 'walnut', 'description' => 'Coklat tua iconic, menua jadi madu seiring waktu.'],
            ['name' => 'Ash', 'slug' => 'ash', 'description' => 'Pucat dengan serat tegas, karakter kuat tapi terang.'],
            ['name' => 'Oak', 'slug' => 'oak', 'description' => 'Klasik untuk semua ruang, hangat dan tahan banting.'],
            ['name' => 'Ebony', 'slug' => 'ebony', 'description' => 'Hitam pekat untuk statement piece yang berani.'],
        ];

        foreach ($materials as $mat) {
            Material::updateOrCreate(['slug' => $mat['slug']], $mat);
        }
    }
}