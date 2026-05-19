<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $categories = [
            // Seating
            ['name' => 'Sofa'],
            ['name' => 'Armchair'],
            ['name' => 'Dining Chair'],
            ['name' => 'Office Chair'],
            ['name' => 'Stool'],
            ['name' => 'Bench'],

            // Table
            ['name' => 'Dining Table'],
            ['name' => 'Coffee Table'],
            ['name' => 'Office Desk'],
            ['name' => 'Side Table'],
            ['name' => 'Console Table'],

            // Storage
            ['name' => 'Wardrobe'],
            ['name' => 'Bookshelf'],
            ['name' => 'Cabinet'],
            ['name' => 'Sideboard'],
            ['name' => 'TV Stand'],
            ['name' => 'Storage Rack'],

            // Bedroom
            ['name' => 'Bed Frame'],
            ['name' => 'Nightstand'],
            ['name' => 'Dresser'],

            // Outdoor
            ['name' => 'Outdoor Table'],
            ['name' => 'Outdoor Chair'],
            ['name' => 'Garden Bench'],
        ];

        $categories = array_map(fn($cat) => array_merge($cat, [
            'created_at' => $now,
            'updated_at' => $now,
        ]), $categories);

        DB::table('product_categories')->insert($categories);
    }
}