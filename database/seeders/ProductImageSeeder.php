<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = [

            'sienna-sofa' => [
                'product_id' => 1,
                'count' => 4,
                'extension' => 'webp',
            ],

            'belvedere-sofa' => [
                'product_id' => 2,
                'count' => 2,
                'extension' => 'jpg',
            ],

            'capri-chair' => [
                'product_id' => 3,
                'count' => 4,
                'extension' => 'webp',
            ],

            'justine-chair' => [
                'product_id' => 4,
                'count' => 4,
                'extension' => 'webp',
            ],

            'avalon-chair' => [
                'product_id' => 5,
                'count' => 4,
                'extension' => 'webp',
            ],

            'wonban-dining-table' => [
                'product_id' => 6,
                'count' => 3,
                'extension' => 'jpg',
            ],

            'louise-cabinet' => [
                'product_id' => 7,
                'count' => 4,
                'extension' => 'webp',
            ],

            'steel-top-dining-table' => [
                'product_id' => 8,
                'count' => 3,
                'extension' => 'jpg',
            ],

            'oval-stone-dining-table' => [
                'product_id' => 9,
                'count' => 3,
                'extension' => 'png',
            ],

            'ground-tea-table' => [
                'product_id' => 10,
                'count' => 4,
                'extension' => 'jpg',
            ],

            'braiden-cabinet' => [
                'product_id' => 11,
                'count' => 4,
                'extension' => 'webp',
            ],

            'lucentia-lamp' => [
                'product_id' => 12,
                'count' => 3,
                'extension' => 'png',
            ],

            'elysian-lamp' => [
                'product_id' => 13,
                'count' => 4,
                'extension' => 'png',
            ],

            'solari-lamp' => [
                'product_id' => 14,
                'count' => 4,
                'extension' => 'png',
            ],

            'midnight-bed' => [
                'product_id' => 15,
                'count' => 4,
                'extension' => 'webp',
            ],

            'laurent-cabinet' => [
                'product_id' => 16,
                'count' => 3,
                'extension' => 'webp',
            ],

            'loungescape-bed' => [
                'product_id' => 17,
                'count' => 4,
                'extension' => 'webp',
            ],

            'gregory-bed' => [
                'product_id' => 18,
                'count' => 4,
                'extension' => 'webp',
            ],

            'asolo-bed' => [
                'product_id' => 19,
                'count' => 3,
                'extension' => 'webp',
            ],

            'rafael-cabinet' => [
                'product_id' => 20,
                'count' => 3,
                'extension' => 'webp',
            ],

        ];

        foreach ($products as $folder => $product) {

            for ($i = 1; $i <= $product['count']; $i++) {

                ProductImage::create([
                    'product_id' => $product['product_id'],
                    'image_path' => "image/{$folder}/{$i}.{$product['extension']}",
                    'is_main' => $i === 1,
                    'sort_order' => $i,
                ]);
            }
        }
    }
}