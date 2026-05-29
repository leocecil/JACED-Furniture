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
    // PERBAIKAN: Menggunakan Route Model Binding (ProductCategory $category) secara konsisten
    public function destroy(ProductCategory $category)
    {
        // 1. Ambil jumlah produk aktif atau terikat (termasuk relasi database)
        $count = $category->products()->count();
        
        // 2. Cegah hapus jika masih ada produk di dalamnya
        if ($count > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete — Category \"{$category->name}\" still has {$count} product(s) inside. Empty the products first."
            ], 422); // Mengembalikan status 422 Unprocessable Entity agar dibaca blok .catch/.then JS
        }

        // 3. Eksekusi hapus jika dipastikan tidak ada produk sama sekali
        $category->delete();
        
        // 4. Mengembalikan success true agar chip kategori di boks modal langsung hilang otomatis
        return response()->json([
            'success' => true,
            'message' => 'Category successfully removed.'
        ]);
    }
}