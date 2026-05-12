<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Seating', 'slug' => 'seating', 'description' => 'Kursi, armchair, lounge chair.'],
            ['name' => 'Tables', 'slug' => 'tables', 'description' => 'Dining table, coffee table, side table.'],
            ['name' => 'Sofas', 'slug' => 'sofas', 'description' => 'Sofa dan sofa bed kayu solid.'],
            ['name' => 'Storage', 'slug' => 'storage', 'description' => 'Bookshelf, sideboard, lemari.'],
            ['name' => 'Beds', 'slug' => 'beds', 'description' => 'Bed frame kayu solid.'],
            ['name' => 'Lighting', 'slug' => 'lighting', 'description' => 'Floor lamp, table lamp.'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}