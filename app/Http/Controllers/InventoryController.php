<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    // ── GET /inventory
    public function index(Request $request)
{
    // PERBAIKAN: Tambahkan withTrashed() agar produk yang terhapus tetap ikut terbaca oleh query filter & sort
    $query = Product::withTrashed()->with(['category', 'images']);

    // Filter by category
    if ($request->filled('category_id')) {
        $query->where('category_id', (int) $request->category_id);
    }

    // Sort
    match ($request->get('sort', 'newest')) {
        'oldest'     => $query->oldest(),
        'price_high' => $query->orderByDesc('price'),
        'price_low'  => $query->orderBy('price'),
        'stock_low'  => $query->orderBy('stock'),
        default      => $query->latest(),
    };

    // Pagination tetap berjalan aman dengan mempertahankan query parameter di URL (?category_id=X&sort=Y)
    $products   = $query->paginate(6)->withQueryString();
    $categories = ProductCategory::orderBy('name')->get();

    return view('pages.inventory.index', compact('products', 'categories'));
}
    // ── POST /inventory
    public function store(StoreProductRequest $request)
    {
        DB::transaction(function () use ($request) {

            // Buat product sesuai fillable model terbaru
            $product = Product::create([
                'name'           => $request->name,
                'slug'           => $request->slug ?? Str::slug($request->name),
                'description'    => $request->description,
                'length'         => $request->length,
                'width'          => $request->width,
                'height'         => $request->height,
                'unit'           => $request->unit,
                'price'          => $request->price,
                'old_price'      => $request->old_price,
                'stock'          => $request->stock,
                'label'          => $request->label,
                'badge'          => $request->badge,
                'is_active'      => $request->boolean('is_active', true),
                'is_recommended' => $request->boolean('is_recommended', false),
                'category_id'    => $request->category_id,
            ]);

            // Upload images[] → ProductImage
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_main'    => $index === 0, // gambar pertama jadi main
                        'sort_order' => $index,
                    ]);
                }
            }
        });

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Product "' . $request->name . '" added successfully.');
    }

    // ── PUT /inventory/{inventory}
    public function update(StoreProductRequest $request, Product $inventory)
    {
        // Override rule slug agar ignore ID produk ini sendiri
        $request->validate([
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $inventory->id,
        ]);

        DB::transaction(function () use ($request, $inventory) {

            $inventory->update([
                'name'           => $request->name,
                'slug'           => $request->slug ?? Str::slug($request->name),
                'description'    => $request->description,
                'length'         => $request->length,
                'width'          => $request->width,
                'height'         => $request->height,
                'unit'           => $request->unit,
                'price'          => $request->price,
                'old_price'      => $request->old_price,
                'stock'          => $request->stock,
                'label'          => $request->label,
                'badge'          => $request->badge,
                'is_active'      => $request->boolean('is_active', true),
                'is_recommended' => $request->boolean('is_recommended', false),
                'category_id'    => $request->category_id,
            ]);

            // Tambah gambar baru jika ada
            if ($request->hasFile('images')) {
                $lastOrder = $inventory->images()->max('sort_order') ?? -1;
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $inventory->id,
                        'image_path' => $path,
                        'is_main'    => false,
                        'sort_order' => $lastOrder + $index + 1,
                    ]);
                }
            }
        });

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Product "' . $request->name . '" updated.');
    }

    // ── DELETE /inventory/{inventory} — soft delete
   public function destroy(Product $inventory)
{
    // 1. Paksa tipe data stock menjadi Integer agar akurat secara matematika
    $stokAktif = (int) $inventory->stock;

    // 2. ATURAN BISNIS: Periksa apakah stok produk masih ada
    if ($stokAktif > 0) {
        return redirect()
            ->back()
            ->with('error', 'Gagal menghapus! Produk "' . $inventory->name . '" tidak bisa dihapus karena masih memiliki sisa stok (' . $stokAktif . ' unit). Kosongkan stok terlebih dahulu.');
    }

    // 3. SOFT DELETE BAWAAN: Mengisi kolom deleted_at secara otomatis
    $name = $inventory->name;
    $inventory->delete(); 

    return redirect()
        ->route('inventory.index')
        ->with('success', 'Product "' . $name . '" berhasil dinonaktifkan.');
}
    // ── DELETE /inventory/image/{image} — hapus 1 gambar (AJAX)
    public function destroyImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return response()->json(['success' => true]);
    }
    public function restore($id)
{
    // Menggunakan withTrashed() karena data yang sudah di-soft delete disembunyikan oleh Laravel
    $product = Product::withTrashed()->findOrFail($id);

    // Mengembalikan data (mengubah deleted_at menjadi NULL kembali)
    $product->restore();

    return redirect()
        ->route('inventory.index')
        ->with('success', 'Product "' . $product->name . '" berhasil dikembalikan ke katalog.');
}
}