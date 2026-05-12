<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function home()
    {
        $recommended = Product::with('variants', 'category')
            ->where('is_active', true)
            ->where('is_recommended', true)
            ->limit(4)
            ->get();

        return view('store.home', compact('recommended'));
    }

    public function shop(Request $request)
    {
        $query = Product::with('variants', 'category', 'material')
            ->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $categories = (array) $request->input('category');
            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('slug', $categories);
            });
        }

        if ($request->filled('material')) {
            $materials = (array) $request->input('material');
            $query->whereHas('material', function ($q) use ($materials) {
                $q->whereIn('slug', $materials);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        switch ($request->input('sort')) {
            case 'newest':
                $query->latest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'bestseller':
                $query->where('badge', 'bestseller');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('products')->orderBy('name')->get();
        $materials = Material::withCount('products')->orderBy('name')->get();
        $totalProducts = Product::where('is_active', true)->count();

        return view('store.shop', compact('products', 'categories', 'materials', 'totalProducts'));
    }

    public function show($slug)
    {
        $product = Product::with('variants', 'category', 'material')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $related = Product::with('variants')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('store.product_details', compact('product', 'related'));
    }
}