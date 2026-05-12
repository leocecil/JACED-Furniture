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
        $cat = Category::pluck('id', 'slug');
        $mat = Material::pluck('id', 'slug');

        $products = [
            [
                'category_id' => $cat['seating'],
                'material_id' => $mat['walnut'],
                'name' => 'Nora Lounge Chair',
                'slug' => 'nora-lounge-chair',
                'short_description' => 'Lounge chair walnut dengan boucle cushion.',
                'description' => 'Nora dirakit dari walnut solid dengan cushion boucle yang lembut. Cocok untuk pojok baca atau ruang santai.',
                'price' => 12500000,
                'old_price' => 14800000,
                'main_image' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=800&h=800&fit=crop',
                'badge' => 'new',
                'stock' => 12,
                'is_recommended' => true,
                'variants' => [
                    ['color_name' => 'Caramel', 'color_hex' => '#896540', 'is_default' => true, 'stock' => 5],
                    ['color_name' => 'Brown', 'color_hex' => '#5A4D47', 'is_default' => false, 'stock' => 4],
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => false, 'stock' => 3],
                ],
            ],
            [
                'category_id' => $cat['tables'],
                'material_id' => $mat['oak'],
                'name' => 'Oka Dining Table',
                'slug' => 'oka-dining-table',
                'short_description' => 'Dining table oak 6 seater.',
                'description' => 'Oka adalah dining table 180cm untuk 6 orang. Dibuat dari oak solid dengan finishing natural oil.',
                'price' => 21000000,
                'old_price' => null,
                'main_image' => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=800&h=800&fit=crop',
                'badge' => null,
                'stock' => 8,
                'is_recommended' => true,
                'variants' => [
                    ['color_name' => 'Tan', 'color_hex' => '#C99A6B', 'is_default' => true, 'stock' => 4],
                    ['color_name' => 'Caramel', 'color_hex' => '#896540', 'is_default' => false, 'stock' => 2],
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => false, 'stock' => 2],
                ],
            ],
            [
                'category_id' => $cat['sofas'],
                'material_id' => $mat['ash'],
                'name' => 'Mara Sofa Bed',
                'slug' => 'mara-sofa-bed',
                'short_description' => 'Sofa bed ash dengan dual function.',
                'description' => 'Mara berubah dari sofa 3 seater jadi tempat tidur queen dalam 5 detik. Frame ash, foam high density.',
                'price' => 18750000,
                'old_price' => 22000000,
                'main_image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&h=800&fit=crop',
                'badge' => 'bestseller',
                'stock' => 6,
                'is_recommended' => true,
                'variants' => [
                    ['color_name' => 'Cream', 'color_hex' => '#EDE8E3', 'is_default' => true, 'stock' => 3],
                    ['color_name' => 'Tan', 'color_hex' => '#C99A6B', 'is_default' => false, 'stock' => 2],
                    ['color_name' => 'Sage', 'color_hex' => '#5F7568', 'is_default' => false, 'stock' => 1],
                ],
            ],
            [
                'category_id' => $cat['storage'],
                'material_id' => $mat['walnut'],
                'name' => 'Aro Bookshelf',
                'slug' => 'aro-bookshelf',
                'short_description' => 'Bookshelf 5 tingkat walnut.',
                'description' => 'Aro bookshelf 5 rak dengan tinggi 180cm. Kapasitas 80 buku per rak. Walnut solid tanpa veneer.',
                'price' => 9800000,
                'old_price' => null,
                'main_image' => 'https://images.unsplash.com/photo-1594620302200-9a762244a156?w=800&h=800&fit=crop',
                'badge' => null,
                'stock' => 15,
                'is_recommended' => true,
                'variants' => [
                    ['color_name' => 'Caramel', 'color_hex' => '#896540', 'is_default' => true, 'stock' => 9],
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => false, 'stock' => 6],
                ],
            ],
            [
                'category_id' => $cat['seating'],
                'material_id' => $mat['ash'],
                'name' => 'Halden Armchair',
                'slug' => 'halden-armchair',
                'short_description' => 'Armchair ash dengan upholstery linen.',
                'description' => 'Halden adalah armchair ringan dengan frame ash dan upholstery linen breathable. Cocok di kamar atau ruang kerja.',
                'price' => 8400000,
                'old_price' => null,
                'main_image' => 'https://images.unsplash.com/photo-1506439773649-6e0eb8cfb237?w=800&h=800&fit=crop',
                'badge' => null,
                'stock' => 18,
                'is_recommended' => false,
                'variants' => [
                    ['color_name' => 'Brown', 'color_hex' => '#5A4D47', 'is_default' => true, 'stock' => 8],
                    ['color_name' => 'Cream', 'color_hex' => '#EDE8E3', 'is_default' => false, 'stock' => 6],
                    ['color_name' => 'Sage', 'color_hex' => '#5F7568', 'is_default' => false, 'stock' => 4],
                ],
            ],
            [
                'category_id' => $cat['tables'],
                'material_id' => $mat['walnut'],
                'name' => 'Lina Coffee Table',
                'slug' => 'lina-coffee-table',
                'short_description' => 'Coffee table bulat walnut diameter 90cm.',
                'description' => 'Lina coffee table bulat dengan top walnut dan kaki tirus minimalis. Pas untuk ruang tamu kompak.',
                'price' => 6900000,
                'old_price' => 8200000,
                'main_image' => 'https://images.unsplash.com/photo-1530018607912-eff2daa1bac4?w=800&h=800&fit=crop',
                'badge' => 'sale',
                'stock' => 11,
                'is_recommended' => false,
                'variants' => [
                    ['color_name' => 'Caramel', 'color_hex' => '#896540', 'is_default' => true, 'stock' => 5],
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => false, 'stock' => 3],
                    ['color_name' => 'Tan', 'color_hex' => '#C99A6B', 'is_default' => false, 'stock' => 3],
                ],
            ],
            [
                'category_id' => $cat['beds'],
                'material_id' => $mat['walnut'],
                'name' => 'Eden Bed Frame',
                'slug' => 'eden-bed-frame',
                'short_description' => 'Bed frame queen walnut headboard rendah.',
                'description' => 'Eden bed frame queen size dengan headboard rendah ala japandi. Walnut solid, slat birch.',
                'price' => 16200000,
                'old_price' => null,
                'main_image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&h=800&fit=crop',
                'badge' => 'preorder',
                'stock' => 4,
                'is_recommended' => false,
                'variants' => [
                    ['color_name' => 'Brown', 'color_hex' => '#5A4D47', 'is_default' => true, 'stock' => 2],
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => false, 'stock' => 2],
                ],
            ],
            [
                'category_id' => $cat['lighting'],
                'material_id' => $mat['oak'],
                'name' => 'Vico Floor Lamp',
                'slug' => 'vico-floor-lamp',
                'short_description' => 'Floor lamp tripod oak.',
                'description' => 'Vico floor lamp dengan kaki tripod oak dan shade linen. Tinggi 160cm, bohlam E27.',
                'price' => 3400000,
                'old_price' => null,
                'main_image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=800&h=800&fit=crop',
                'badge' => null,
                'stock' => 22,
                'is_recommended' => false,
                'variants' => [
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => true, 'stock' => 12],
                    ['color_name' => 'Tan', 'color_hex' => '#C99A6B', 'is_default' => false, 'stock' => 10],
                ],
            ],
            [
                'category_id' => $cat['storage'],
                'material_id' => $mat['oak'],
                'name' => 'Ria Sideboard',
                'slug' => 'ria-sideboard',
                'short_description' => 'Sideboard oak 4 pintu.',
                'description' => 'Ria sideboard panjang 180cm dengan 4 pintu push to open. Cocok di ruang makan atau living room.',
                'price' => 14600000,
                'old_price' => 16400000,
                'main_image' => 'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?w=800&h=800&fit=crop',
                'badge' => 'sale',
                'stock' => 7,
                'is_recommended' => false,
                'variants' => [
                    ['color_name' => 'Caramel', 'color_hex' => '#896540', 'is_default' => true, 'stock' => 3],
                    ['color_name' => 'Brown', 'color_hex' => '#5A4D47', 'is_default' => false, 'stock' => 2],
                    ['color_name' => 'Cream', 'color_hex' => '#EDE8E3', 'is_default' => false, 'stock' => 2],
                ],
            ],
        ];

        foreach ($products as $data) {
            $variants = $data['variants'];
            unset($data['variants']);

            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            $product->variants()->delete();
            foreach ($variants as $v) {
                $product->variants()->create($v);
            }
        }
    }
}