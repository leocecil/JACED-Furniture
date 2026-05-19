<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'Nora Lounge Chair',
                'description' => 'Lounge chair walnut dengan boucle cushion.',
                'length' => 80,
                'width' => 75,
                'height' => 90,
                'unit' => 'cm',
                'price' => 12500000,
                'stock' => 12,
            ],
            [
                'category_id' => 2,
                'name' => 'Oka Dining Table',
                'description' => 'Dining table oak 6 seater.',
                'length' => 180,
                'width' => 90,
                'height' => 75,
                'unit' => 'cm',
                'price' => 21000000,
                'stock' => 8,
            ],
            [
                'category_id' => 3,
                'name' => 'Mara Sofa Bed',
                'description' => 'Sofa bed ash dengan dual function.',
                'length' => 200,
                'width' => 95,
                'height' => 88,
                'unit' => 'cm',
                'price' => 18750000,
                'stock' => 6,
            ],
            [
                'category_id' => 4,
                'name' => 'Aro Bookshelf',
                'description' => 'Bookshelf 5 tingkat walnut.',
                'length' => 100,
                'width' => 35,
                'height' => 180,
                'unit' => 'cm',
                'price' => 9800000,
                'stock' => 15,
            ],
            [
                'category_id' => 1,
                'name' => 'Halden Armchair',
                'description' => 'Armchair ash dengan upholstery linen.',
                'length' => 85,
                'width' => 78,
                'height' => 92,
                'unit' => 'cm',
                'price' => 8400000,
                'stock' => 18,
            ],
            [
                'category_id' => 2,
                'name' => 'Lina Coffee Table',
                'description' => 'Coffee table bulat walnut diameter 90cm.',
                'length' => 90,
                'width' => 90,
                'height' => 45,
                'unit' => 'cm',
                'price' => 6900000,
                'stock' => 11,
            ],
            [
                'category_id' => 5,
                'name' => 'Eden Bed Frame',
                'description' => 'Bed frame queen walnut headboard rendah.',
                'length' => 200,
                'width' => 160,
                'height' => 110,
                'unit' => 'cm',
                'price' => 16200000,
                'stock' => 4,
            ],
            [
                'category_id' => 6,
                'name' => 'Vico Floor Lamp',
                'description' => 'Floor lamp tripod oak.',
                'length' => 45,
                'width' => 45,
                'height' => 160,
                'unit' => 'cm',
                'price' => 3400000,
                'stock' => 22,
            ],
            [
                'category_id' => 4,
                'name' => 'Ria Sideboard',
                'description' => 'Sideboard oak 4 pintu.',
                'length' => 180,
                'width' => 45,
                'height' => 80,
                'unit' => 'cm',
                'price' => 14600000,
                'stock' => 7,
            ],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }
    }
}