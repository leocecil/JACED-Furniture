<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    // ── GET /admin/inventory
    public function index(Request $request)
    {
        $orderCount = Order::whereIn('status', ['on_process', 'packed'])->count();

        // withTrashed() agar produk soft-deleted tetap ikut query filter & sort
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

        // withQueryString() agar parameter ?category_id=X&sort=Y tetap terbawa saat paginasi
        $products   = $query->paginate(6)->withQueryString();
        $categories = ProductCategory::orderBy('name')->get();

        return view('pages.inventory.index', compact('orderCount', 'products', 'categories'));
    }

    // ── POST /admin/inventory
    public function store(StoreProductRequest $request)
    {
        DB::transaction(function () use ($request) {

            $product = Product::create([
                'name'        => $request->name,
                'description' => $request->description,
                'length'      => $request->length,
                'width'       => $request->width,
                'height'      => $request->height,
                'unit'        => $request->unit,
                'price'       => $request->price,
                'stock'       => $request->stock,
                'low_stock'   => $request->low_stock ?? 5,
                'label'       => $request->label,
                'category_id' => $request->category_id,
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

    // ── PUT /admin/inventory/{inventory}
    public function update(StoreProductRequest $request, Product $inventory)
    {
        DB::transaction(function () use ($request, $inventory) {

            $inventory->update([
                'name'        => $request->name,
                'description' => $request->description,
                'length'      => $request->length,
                'width'       => $request->width,
                'height'      => $request->height,
                'unit'        => $request->unit,
                'price'       => $request->price,
                'stock'       => $request->stock,
                'low_stock'   => $request->low_stock ?? $inventory->low_stock,
                'label'       => $request->label,
                'category_id' => $request->category_id,
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

    // ── DELETE /admin/inventory/{inventory} — soft delete
    public function destroy(Product $inventory)
    {
        // Paksa tipe data stock menjadi integer agar akurat
        $stokAktif = (int) $inventory->stock;

        // Aturan bisnis: tidak bisa hapus jika stok masih ada
        if ($stokAktif > 0) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus! Produk "' . $inventory->name . '" tidak bisa dihapus karena masih memiliki sisa stok (' . $stokAktif . ' unit). Kosongkan stok terlebih dahulu.');
        }

        $name = $inventory->name;
        $inventory->delete(); // soft delete → deleted_at terisi otomatis

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Product "' . $name . '" berhasil dinonaktifkan.');
    }

    // ── POST /admin/inventory/{id}/restore — kembalikan soft-deleted product
    public function restore($id)
    {
        // withTrashed() wajib karena data soft-deleted disembunyikan oleh default query
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore(); // deleted_at → NULL

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Product "' . $product->name . '" berhasil dikembalikan ke katalog.');
    }

    // ── DELETE /admin/inventory/image/{image} — hapus 1 gambar (AJAX)
    public function destroyImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return response()->json(['success' => true]);
    }
}