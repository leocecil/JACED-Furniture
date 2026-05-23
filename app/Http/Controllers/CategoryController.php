<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // ── POST /categories  (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:product_categories,name',
                'regex:/^[a-zA-Z\s\-]+$/',
            ],
        ], [
            'name.required' => 'Category name is required.',
            'name.unique'   => 'This category already exists.',
            'name.regex'    => 'Only letters, spaces, and hyphens are allowed.',
        ]);

        $category = ProductCategory::create(['name' => $request->name]);

        return response()->json(['success' => true, 'category' => $category]);
    }

    // ── DELETE /categories/{category}  (AJAX)
    public function destroy(ProductCategory $category)
    {
        // Cegah hapus jika masih ada produk
        $count = $category->products()->count();
        if ($count > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete — category still has {$count} product(s).",
            ], 422);
        }

        $category->delete();
        return response()->json(['success' => true]);
    }
}