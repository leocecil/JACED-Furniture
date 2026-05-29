<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostalCodeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('postal_codes')->truncate();

        $insertData = [];

        DB::table('indonesia_villages')
            ->select('id', 'meta')
            ->orderBy('id')
            ->chunk(2000, function ($villages) use (&$insertData) {

                $batch = [];

                foreach ($villages as $village) {

                    $meta = json_decode($village->meta, true);

                    if (!isset($meta['pos'])) {
                        continue;
                    }

                    $batch[] = [
                        'village_id'  => $village->id,
                        'postal_code' => $meta['pos'],
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }

                if (!empty($batch)) {
                    DB::table('postal_codes')->insert($batch);
                }
            });

        $this->command->info('Postal codes seeded successfully!');
    }
}