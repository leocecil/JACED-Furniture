<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    private function dummyProducts(): array
    {
        return [
            [
                'id' => 1, 'slug' => 'nora-lounge-chair', 'name' => 'Nora Lounge Chair',
                'category' => ['name' => 'Seating', 'slug' => 'seating'],
                'material' => ['name' => 'Walnut', 'slug' => 'walnut'],
                'price' => 12500000, 'old_price' => 14800000, 'badge' => 'new',
                'main_image' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=800&h=800&fit=crop',
                'is_recommended' => true,
                'variants' => [
                    ['color_name' => 'Caramel', 'color_hex' => '#896540', 'is_default' => true],
                    ['color_name' => 'Brown', 'color_hex' => '#5A4D47', 'is_default' => false],
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => false],
                ],
            ],
            [
                'id' => 2, 'slug' => 'oka-dining-table', 'name' => 'Oka Dining Table',
                'category' => ['name' => 'Tables', 'slug' => 'tables'],
                'material' => ['name' => 'Oak', 'slug' => 'oak'],
                'price' => 21000000, 'old_price' => null, 'badge' => null,
                'main_image' => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=800&h=800&fit=crop',
                'is_recommended' => true,
                'variants' => [
                    ['color_name' => 'Tan', 'color_hex' => '#C99A6B', 'is_default' => true],
                    ['color_name' => 'Caramel', 'color_hex' => '#896540', 'is_default' => false],
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => false],
                ],
            ],
            [
                'id' => 3, 'slug' => 'mara-sofa-bed', 'name' => 'Mara Sofa Bed',
                'category' => ['name' => 'Sofas', 'slug' => 'sofas'],
                'material' => ['name' => 'Ash', 'slug' => 'ash'],
                'price' => 18750000, 'old_price' => 22000000, 'badge' => 'bestseller',
                'main_image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&h=800&fit=crop',
                'is_recommended' => true,
                'variants' => [
                    ['color_name' => 'Cream', 'color_hex' => '#EDE8E3', 'is_default' => true],
                    ['color_name' => 'Tan', 'color_hex' => '#C99A6B', 'is_default' => false],
                    ['color_name' => 'Sage', 'color_hex' => '#5F7568', 'is_default' => false],
                ],
            ],
            [
                'id' => 4, 'slug' => 'aro-bookshelf', 'name' => 'Aro Bookshelf',
                'category' => ['name' => 'Storage', 'slug' => 'storage'],
                'material' => ['name' => 'Walnut', 'slug' => 'walnut'],
                'price' => 9800000, 'old_price' => null, 'badge' => null,
                'main_image' => 'https://images.unsplash.com/photo-1594620302200-9a762244a156?w=800&h=800&fit=crop',
                'is_recommended' => true,
                'variants' => [
                    ['color_name' => 'Caramel', 'color_hex' => '#896540', 'is_default' => true],
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => false],
                ],
            ],
            [
                'id' => 5, 'slug' => 'halden-armchair', 'name' => 'Halden Armchair',
                'category' => ['name' => 'Seating', 'slug' => 'seating'],
                'material' => ['name' => 'Ash', 'slug' => 'ash'],
                'price' => 8400000, 'old_price' => null, 'badge' => null,
                'main_image' => 'https://images.unsplash.com/photo-1506439773649-6e0eb8cfb237?w=800&h=800&fit=crop',
                'is_recommended' => false,
                'variants' => [
                    ['color_name' => 'Brown', 'color_hex' => '#5A4D47', 'is_default' => true],
                    ['color_name' => 'Cream', 'color_hex' => '#EDE8E3', 'is_default' => false],
                    ['color_name' => 'Sage', 'color_hex' => '#5F7568', 'is_default' => false],
                ],
            ],
            [
                'id' => 6, 'slug' => 'lina-coffee-table', 'name' => 'Lina Coffee Table',
                'category' => ['name' => 'Tables', 'slug' => 'tables'],
                'material' => ['name' => 'Walnut', 'slug' => 'walnut'],
                'price' => 6900000, 'old_price' => 8200000, 'badge' => 'sale',
                'main_image' => 'https://images.unsplash.com/photo-1530018607912-eff2daa1bac4?w=800&h=800&fit=crop',
                'is_recommended' => false,
                'variants' => [
                    ['color_name' => 'Caramel', 'color_hex' => '#896540', 'is_default' => true],
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => false],
                    ['color_name' => 'Tan', 'color_hex' => '#C99A6B', 'is_default' => false],
                ],
            ],
            [
                'id' => 7, 'slug' => 'eden-bed-frame', 'name' => 'Eden Bed Frame',
                'category' => ['name' => 'Beds', 'slug' => 'beds'],
                'material' => ['name' => 'Walnut', 'slug' => 'walnut'],
                'price' => 16200000, 'old_price' => null, 'badge' => 'preorder',
                'main_image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&h=800&fit=crop',
                'is_recommended' => false,
                'variants' => [
                    ['color_name' => 'Brown', 'color_hex' => '#5A4D47', 'is_default' => true],
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => false],
                ],
            ],
            [
                'id' => 8, 'slug' => 'vico-floor-lamp', 'name' => 'Vico Floor Lamp',
                'category' => ['name' => 'Lighting', 'slug' => 'lighting'],
                'material' => ['name' => 'Oak', 'slug' => 'oak'],
                'price' => 3400000, 'old_price' => null, 'badge' => null,
                'main_image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=800&h=800&fit=crop',
                'is_recommended' => false,
                'variants' => [
                    ['color_name' => 'Dark', 'color_hex' => '#272E1D', 'is_default' => true],
                    ['color_name' => 'Tan', 'color_hex' => '#C99A6B', 'is_default' => false],
                ],
            ],
            [
                'id' => 9, 'slug' => 'ria-sideboard', 'name' => 'Ria Sideboard',
                'category' => ['name' => 'Storage', 'slug' => 'storage'],
                'material' => ['name' => 'Oak', 'slug' => 'oak'],
                'price' => 14600000, 'old_price' => 16400000, 'badge' => 'sale',
                'main_image' => 'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?w=800&h=800&fit=crop',
                'is_recommended' => false,
                'variants' => [
                    ['color_name' => 'Caramel', 'color_hex' => '#896540', 'is_default' => true],
                    ['color_name' => 'Brown', 'color_hex' => '#5A4D47', 'is_default' => false],
                    ['color_name' => 'Cream', 'color_hex' => '#EDE8E3', 'is_default' => false],
                ],
            ],
        ];
    }

    private function toObject(array $arr): object
    {
        $obj = new \stdClass();
        foreach ($arr as $k => $v) {
            if (is_array($v) && array_keys($v) !== range(0, count($v) - 1)) {
                $obj->$k = $this->toObject($v);
            } elseif (is_array($v)) {
                $obj->$k = collect(array_map(fn($item) => is_array($item) ? $this->toObject($item) : $item, $v));
            } else {
                $obj->$k = $v;
            }
        }
        return $obj;
    }

    public function home()
    {
        $all = collect($this->dummyProducts())
            ->filter(fn($p) => $p['is_recommended'])
            ->take(4)
            ->map(fn($p) => $this->toObject($p))
            ->values();

        $recommended = $all;
        return view('store.home', compact('recommended'));
    }

    public function shop(Request $request)
    {
        $all = collect($this->dummyProducts());

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $all = $all->filter(fn($p) => str_contains(strtolower($p['name']), $search));
        }

        if ($request->filled('category')) {
            $cats = (array) $request->input('category');
            $all = $all->filter(fn($p) => in_array($p['category']['slug'], $cats));
        }

        if ($request->filled('material')) {
            $mats = (array) $request->input('material');
            $all = $all->filter(fn($p) => in_array($p['material']['slug'], $mats));
        }
        if ($request->filled('color')) {
            $colors = (array) $request->input('color');
            $all = $all->filter(function ($p) use ($colors) {
                $productColors = collect($p['variants'])->pluck('color_hex')->toArray();
                return count(array_intersect($productColors, $colors)) > 0;
            });
        }

        if ($request->filled('min_price')) {
            $all = $all->filter(fn($p) => $p['price'] >= $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $all = $all->filter(fn($p) => $p['price'] <= $request->input('max_price'));
        }

        switch ($request->input('sort')) {
            case 'price_asc':
                $all = $all->sortBy('price');
                break;
            case 'price_desc':
                $all = $all->sortByDesc('price');
                break;
            case 'bestseller':
                $all = $all->filter(fn($p) => $p['badge'] === 'bestseller');
                break;
            case 'newest':
            default:
                $all = $all->sortByDesc('id');
        }

        $all = $all->values();
        $page = $request->input('page', 1);
        $perPage = 9;
        $items = $all->forPage($page, $perPage)->map(fn($p) => $this->toObject($p))->values();

        $products = new LengthAwarePaginator(
            $items,
            $all->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $rawProducts = collect($this->dummyProducts());
        $categories = $rawProducts->groupBy(fn($p) => $p['category']['slug'])->map(function ($items, $slug) {
            return (object) [
                'slug' => $slug,
                'name' => $items->first()['category']['name'],
                'products_count' => $items->count(),
            ];
        })->sortBy('name')->values();

        $materials = $rawProducts->groupBy(fn($p) => $p['material']['slug'])->map(function ($items, $slug) {
            return (object) [
                'slug' => $slug,
                'name' => $items->first()['material']['name'],
                'products_count' => $items->count(),
            ];
        })->sortBy('name')->values();

        $totalProducts = $rawProducts->count();

        return view('store.shop', compact('products', 'categories', 'materials', 'totalProducts'));
    }

    public function show($slug)
    {
        $product = collect($this->dummyProducts())->firstWhere('slug', $slug);
        if (!$product) {
            abort(404);
        }

        $related = collect($this->dummyProducts())
            ->filter(fn($p) => $p['category']['slug'] === $product['category']['slug'] && $p['slug'] !== $slug)
            ->take(4)
            ->map(fn($p) => $this->toObject($p))
            ->values();

        $product = $this->toObject($product);

        return view('store.product_details', compact('product', 'related'));
    }
}