<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    // ─── HELPER: normalize buat shop/home card (stdClass) ────────────────────

    private function normalize(Product $product): object
    {
        $mainImageUrl = $product->main_image_url;

        $allImages = $product->images->map(function ($img) {
            $path = str_replace('image/products/', 'image/', $img->image_path);
            return (object) [
                'url'        => asset($path),
                'is_main'    => $img->is_main,
                'sort_order' => $img->sort_order,
            ];
        })->values();

        // Badge dari label
        $labelRaw = strtolower($product->label ?? '');
        $badge = null;
        if (str_contains($labelRaw, 'best seller') || str_contains($labelRaw, 'bestseller')) {
            $badge = 'bestseller';
        } elseif (str_contains($labelRaw, 'new') || str_contains($labelRaw, 'premium')) {
            $badge = 'new';
        } elseif (str_contains($labelRaw, 'preorder') || str_contains($labelRaw, 'pre-order')) {
            $badge = 'preorder';
        }

        $catName = $product->category->name ?? 'furniture';
        $catSlug = Str::slug($catName);

        $length = $product->length ?? 0;
        $width  = $product->width  ?? 0;
        $height = $product->height ?? 0;
        $unit   = $product->unit   ?? 'cm';

        $maxDim = max($length, $width, $height);
        $size = 'medium';
        if ($maxDim > 200) $size = 'large';
        elseif ($maxDim < 80) $size = 'small';

        return (object) [
            'id'             => $product->id,
            'slug'           => $product->slug,
            'name'           => $product->name,
            'description'    => $product->description ?? '',
            'price'          => (float) $product->price,
            'old_price'      => null,
            'stock'          => $product->stock ?? 0,
            'low_stock'      => $product->low_stock ?? 3,
            'badge'          => $badge,
            'label'          => $product->label,
            'is_recommended' => $product->stock > 3,
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
            'material' => (object) ['name' => 'Solid Wood', 'slug' => 'solid-wood'],
            'room'     => (object) ['name' => 'Living Room', 'slug' => 'living-room'],
            'variants' => [],
        ];
    }

    // ─── LANDING ──────────────────────────────────────────────────────────────

    public function landing()
    {
        return view('store.landing');
    }

    // ─── HOME ─────────────────────────────────────────────────────────────────

    public function home()
    {
        $products = Product::with(['images', 'category'])
            ->where('stock', '>', 0)
            ->orderByDesc('stock')
            ->take(4)
            ->get();

        $recommended = $products->map(fn($p) => $this->normalize($p))->values();

        $categories = ProductCategory::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get()
            ->map(function ($cat) {
                return (object) [
                    'slug'  => Str::slug($cat->name),
                    'name'  => ucfirst($cat->name),
                    'count' => $cat->products_count,
                ];
            });

        return view('store.home', compact('recommended', 'categories'));
    }

    // ─── SHOP ─────────────────────────────────────────────────────────────────

    public function shop(Request $request)
    {
        $query = Product::with(['images', 'category', 'orderDetails'])
            ->where('stock', '>=', 0);

        // $query = Product::with(['images', 'category'])
        //     ->withSum('orderDetails as total_sold', 'quantity')
        //     ->where('stock', '>=', 0);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('category')) {
            $cats = (array) $request->input('category');
            $query->whereHas('category', function ($q) use ($cats) {
                $q->whereIn(DB::raw('LOWER(name)'), $cats);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        if ($request->filled('size')) {
            $sizes = (array) $request->input('size');
            $query->where(function ($q) use ($sizes) {
                foreach ($sizes as $size) {
                    if ($size === 'small') {
                        $q->orWhere(function ($q2) {
                            $q2->where('length', '<', 80)->where('width', '<', 80)->where('height', '<', 80);
                        });
                    } elseif ($size === 'large') {
                        $q->orWhere('length', '>', 200)->orWhere('width', '>', 200)->orWhere('height', '>', 200);
                    } else {
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

        switch ($request->input('sort')) {
            case 'price_asc':  $query->orderBy('price', 'asc'); break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'bestseller': $query->where('label', 'like', '%best%')->orderByDesc('id'); break;
            default:           $query->orderByDesc('id');
        }

        $totalProducts = Product::count();
        $paginated = $query->paginate(9)->withQueryString();
        // $items = $paginated->getCollection()->map(fn($p) => $this->normalize($p));
        $items = $paginated->getCollection()->map(function ($p) {
            $normalized = $this->normalize($p);
            $normalized->total_sold = $p->orderDetails->sum('quantity');
            return $normalized;
        });
        $paginated->setCollection($items);
        $products = $paginated;

        $categories = ProductCategory::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get()
            ->map(fn($cat) => (object) [
                'slug'           => Str::slug($cat->name),
                'name'           => ucfirst($cat->name),
                'products_count' => $cat->products_count,
            ]);

        $materials = collect([
            (object) ['slug' => 'solid-wood', 'name' => 'Solid Wood', 'products_count' => $totalProducts],
            (object) ['slug' => 'fabric',     'name' => 'Fabric',     'products_count' => 8],
            (object) ['slug' => 'leather',    'name' => 'Leather',    'products_count' => 3],
            (object) ['slug' => 'stone',      'name' => 'Stone',      'products_count' => 2],
            (object) ['slug' => 'metal',      'name' => 'Metal',      'products_count' => 3],
        ]);

        $rooms = collect([
            (object) ['slug' => 'living-room', 'name' => 'Living Room',  'products_count' => 8],
            (object) ['slug' => 'bedroom',     'name' => 'Bedroom',      'products_count' => 5],
            (object) ['slug' => 'dining-room', 'name' => 'Dining Room',  'products_count' => 4],
            (object) ['slug' => 'office',      'name' => 'Office',       'products_count' => 3],
        ]);



        return view('store.shop', compact('products', 'categories', 'materials', 'rooms', 'totalProducts'));
    }


    public function show($slug)
    {
        $product = Product::with([
            'images',
            'category',
            'mainImage',
            'orderDetails',
            'wishlists',
        ])->get()->firstWhere('slug', $slug);

        if (!$product) {
            abort(404);
        }

        // TOTAL SOLD
        $totalSold = $product->orderDetails->sum('quantity');

        // RELATED PRODUCTS
        $related = Product::with([
            'images',
            'category',
            'mainImage'
        ])
        ->where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('stock', '>', 0)
        ->take(4)
        ->get();

        $isWishlisted = false;

        if(auth()->check()){
            $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('store.product_details', compact(
            'product', 'related', 'totalSold', 'isWishlisted'
        ));
    }

    // ─── BATCH (wishlist) 

    public function batchProducts(Request $request)
    {
        $ids = array_filter(array_map('intval', explode(',', $request->input('ids', ''))));
        if (empty($ids)) return response()->json([]);

        $products = Product::with(['images', 'category'])
            ->whereIn('id', $ids)
            ->get()
            ->map(fn($p) => [
                'id'              => $p->id,
                'slug'            => $p->slug,
                'main_image'      => $p->main_image_url,
                'price_formatted' => number_format($p->price, 0, ',', '.'),
                'category'        => ucfirst($p->category->name ?? 'Furniture'),
            ]);

        return response()->json($products);
    }
}