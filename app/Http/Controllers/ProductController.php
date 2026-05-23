<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    // ─── HELPER: normalize product object buat view ──────────────────────────

    private function normalize(Product $product): object
    {
        // Ambil main image dari relasi ProductImage
        $mainImg = $product->images
            ->where('is_main', true)
            ->sortBy('sort_order')
            ->first();

        // Fallback ke image pertama kalo is_main ga ada
        if (!$mainImg) {
            $mainImg = $product->images->sortBy('sort_order')->first();
        }

        // Fix path: strip "products/" prefix kalo ada (mismatch seeder vs folder)
        $imagePath = $mainImg
            ? str_replace('image/products/', 'image/', $mainImg->image_path)
            : null;

        $mainImageUrl = $imagePath
            ? asset($imagePath)
            : 'https://placehold.co/800x800/f2ede6/272e1d?text=' . urlencode($product->name);

        // Semua gambar produk buat product_details
        $allImages = $product->images->sortBy('sort_order')->map(function ($img) {
            $path = str_replace('image/products/', 'image/', $img->image_path);
            return (object) [
                'url'      => asset($path),
                'is_main'  => $img->is_main,
                'sort_order' => $img->sort_order,
            ];
        })->values();

        // Badge dari label field
        $labelRaw = strtolower($product->label ?? '');
        $badge = null;
        if (str_contains($labelRaw, 'best seller') || str_contains($labelRaw, 'bestseller')) {
            $badge = 'bestseller';
        } elseif (str_contains($labelRaw, 'new') || str_contains($labelRaw, 'premium')) {
            $badge = 'new';
        } elseif (str_contains($labelRaw, 'preorder') || str_contains($labelRaw, 'pre-order')) {
            $badge = 'preorder';
        }

        // Category slug dari nama (karena ProductCategory ga punya slug column)
        $catName = $product->category->name ?? 'furniture';
        $catSlug = \Illuminate\Support\Str::slug($catName);

        // Dimensions buat display
        $length = $product->length ?? 0;
        $width  = $product->width  ?? 0;
        $height = $product->height ?? 0;
        $unit   = $product->unit   ?? 'cm';

        // Size estimate dari dimensi
        $maxDim = max($length, $width, $height);
        $size = 'medium';
        if ($maxDim > 200) $size = 'large';
        elseif ($maxDim < 80) $size = 'small';

        // is_recommended: pakai field di DB kalo ada, fallback ke stock > 3
        $isRecommended = $product->is_recommended ?? ($product->stock > 3);

        return (object) [
            'id'             => $product->id,
            'slug'           => $product->slug ?? \Illuminate\Support\Str::slug($product->name),
            'name'           => $product->name,
            'description'    => $product->description ?? '',
            'price'          => (float) $product->price,
            'old_price'      => $product->old_price ? (float) $product->old_price : null,
            'stock'          => $product->stock ?? 0,
            'badge'          => $badge,
            'label'          => $product->label,
            'is_recommended' => $isRecommended,
            'length'         => $length,
            'width'          => $width,
            'height'         => $height,
            'unit'           => $unit,
            'size'           => $size,
            'main_image'     => $mainImageUrl,
            'all_images'     => $allImages,
            'category'       => (object) [
                'id'   => $product->category->id ?? null,
                'name' => ucfirst($catName),
                'slug' => $catSlug,
            ],
            // Material + room ga ada di DB, fallback static
            'material'       => (object) [
                'name' => 'Solid Wood',
                'slug' => 'solid-wood',
            ],
            'room'           => (object) [
                'name' => 'Living Room',
                'slug' => 'living-room',
            ],
            // Variants: kosong, bisa di-extend nanti
            'variants'       => [],
        ];
    }

    // ─── HOME ─────────────────────────────────────────────────────────────────

    public function home()
    {
        $products = Product::with(['images', 'category'])
            ->where('stock', '>', 0)
            ->orderByDesc('id')
            ->take(4)
            ->get();

        if ($products->where('is_recommended', true)->count() >= 1) {
            $products = Product::with(['images', 'category'])
                ->where('is_recommended', true)
                ->where('stock', '>', 0)
                ->take(4)
                ->get();
        }

        $recommended = $products->map(fn($p) => $this->normalize($p))->values();

        $categories = ProductCategory::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get()
            ->map(function ($cat) {
                return (object) [
                    'slug'   => \Illuminate\Support\Str::slug($cat->name),
                    'name'   => ucfirst($cat->name),
                    'count'  => $cat->products_count,
                ];
            });

        return view('store.home', compact('recommended', 'categories'));
    }

    // ─── SHOP ─────────────────────────────────────────────────────────────────

    public function shop(Request $request)
    {
        $query = Product::with(['images', 'category'])
            ->where('stock', '>=', 0);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by category
        if ($request->filled('category')) {
            $cats = (array) $request->input('category');
            $query->whereHas('category', function ($q) use ($cats) {
                $q->whereIn(\Illuminate\Support\Facades\DB::raw('LOWER(name)'), $cats);
            });
        }

        // Filter by price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Filter by size (estimasi dari dimensi)
        if ($request->filled('size')) {
            $sizes = (array) $request->input('size');
            $query->where(function ($q) use ($sizes) {
                foreach ($sizes as $size) {
                    if ($size === 'small') {
                        $q->orWhere(function ($q2) {
                            $q2->where('length', '<', 80)
                               ->where('width', '<', 80)
                               ->where('height', '<', 80);
                        });
                    } elseif ($size === 'large') {
                        $q->orWhere('length', '>', 200)
                          ->orWhere('width', '>', 200)
                          ->orWhere('height', '>', 200);
                    } else {
                        // medium = default, include semua yang ga masuk small/large
                        $q->orWhere(function ($q2) {
                            $q2->where(function ($q3) {
                                $q3->where('length', '>=', 80)->where('length', '<=', 200);
                            })->orWhere(function ($q3) {
                                $q3->where('width', '>=', 80)->where('width', '<=', 200);
                            });
                        });
                    }
                }
            });
        }

        // Sort
        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'bestseller':
                $query->where('label', 'like', '%best%')->orderByDesc('id');
                break;
            case 'newest':
            default:
                $query->orderByDesc('id');
        }

        $totalProducts = Product::count();

        // Paginate
        $paginated = $query->paginate(9)->withQueryString();

        // Normalize tiap produk
        $items = $paginated->getCollection()->map(fn($p) => $this->normalize($p));
        $paginated->setCollection($items);
        $products = $paginated;

        // Categories buat sidebar
        $categories = ProductCategory::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get()
            ->map(function ($cat) {
                return (object) [
                    'slug'           => \Illuminate\Support\Str::slug($cat->name),
                    'name'           => ucfirst($cat->name),
                    'products_count' => $cat->products_count,
                ];
            });

        // Materials: static list karena ga ada table materials di DB
        $materials = collect([
            (object) ['slug' => 'solid-wood', 'name' => 'Solid Wood', 'products_count' => $totalProducts],
            (object) ['slug' => 'fabric',     'name' => 'Fabric',     'products_count' => 8],
            (object) ['slug' => 'leather',    'name' => 'Leather',    'products_count' => 3],
            (object) ['slug' => 'stone',      'name' => 'Stone',      'products_count' => 2],
            (object) ['slug' => 'metal',      'name' => 'Metal',      'products_count' => 3],
        ]);

        // Rooms: static list
        $rooms = collect([
            (object) ['slug' => 'living-room',  'name' => 'Living Room',  'products_count' => 8],
            (object) ['slug' => 'bedroom',       'name' => 'Bedroom',      'products_count' => 5],
            (object) ['slug' => 'dining-room',   'name' => 'Dining Room',  'products_count' => 4],
            (object) ['slug' => 'office',        'name' => 'Office',       'products_count' => 3],
        ]);

        return view('store.shop', compact(
            'products', 'categories', 'materials', 'rooms', 'totalProducts'
        ));
    }

    // ─── SHOW (product detail) ────────────────────────────────────────────────

    public function show($slug)
    {
        // Cari by slug, fallback cari by name-slug
        $product = Product::with(['images', 'category'])
            ->where('slug', $slug)
            ->first();

        // Fallback: slug dari nama
        if (!$product) {
            $products = Product::with(['images', 'category'])->get();
            $product = $products->first(function ($p) use ($slug) {
                return \Illuminate\Support\Str::slug($p->name) === $slug;
            });
        }

        if (!$product) {
            abort(404);
        }

        $normalized = $this->normalize($product);

        // Related: category yang sama, exclude produk ini
        $related = Product::with(['images', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->take(4)
            ->get()
            ->map(fn($p) => $this->normalize($p))
            ->values();

        $product = $normalized;

        return view('store.product_details', compact('product', 'related'));
    }
}