<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    // ── GET /admin/inventory
    public function index(Request $request)
    {
        $orderCount = Order::whereIn('status', ['pending', 'packed'])->count();

        $query = Product::withTrashed()->with(['category', 'images']);

        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->category_id);
        }

        match ($request->get('sort', 'newest')) {
            'oldest'     => $query->oldest(),
            'price_high' => $query->orderByDesc('price'),
            'price_low'  => $query->orderBy('price'),
            'stock_low'  => $query->orderBy('stock'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(6)->withQueryString();
        $categories = ProductCategory::orderBy('name')->get();

        return view('pages.inventory.index', compact('orderCount', 'products', 'categories'));
    }

    // ── POST /admin/inventory (Mendukung upload langsung ke public/image/nama-produk/)
   public function store(StoreProductRequest $request)
    {
        // ── PENCEGAHAN DUPLIKASI BARANG ──
        // Cek apakah ada produk dengan nama yang sama (termasuk yang ada di dalam trash/soft-deleted)
        $namaSama = Product::withTrashed()
            ->where('name', trim($request->name))
            ->exists();

        if ($namaSama) {
            return redirect()
                ->back()
                ->withInput() // Mempertahankan isi form yang sudah diketik admin agar tidak hilang
                ->with('error', 'Gagal menambahkan! Produk dengan nama "' . $request->name . '" sudah terdaftar di dalam sistem Jaced Furniture. Gunakan nama lain atau edit produk yang sudah ada.');
        }

        DB::transaction(function () use ($request) {
            $product = Product::create([
                'name'        => trim($request->name),
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

            if ($request->hasFile('images')) {
                $folderName = Str::slug($product->name); 
                
                foreach ($request->file('images') as $index => $image) {
                    $increment = $index + 1;
                    $extension = $image->getClientOriginalExtension();
                    $fileName  = "{$increment}.{$extension}";

                    $image->move(public_path("image/{$folderName}"), $fileName);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => "image/{$folderName}/{$fileName}",
                        'is_main'    => $index === 0,
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

            if ($request->hasFile('images')) {
                $folderName = Str::slug($inventory->name);
                $lastOrder  = $inventory->images()->max('sort_order') ?? -1;

                foreach ($request->file('images') as $index => $image) {
                    $increment = $lastOrder + $index + 2; // Melanjutkan penomoran angka berkas gambar terakhir
                    $extension = $image->getClientOriginalExtension();
                    $fileName  = "{$increment}.{$extension}";

                    $image->move(public_path("image/{$folderName}"), $fileName);

                    ProductImage::create([
                        'product_id' => $inventory->id,
                        'image_path' => "image/{$folderName}/{$fileName}",
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

    // ── DELETE /admin/inventory/{inventory}
    public function destroy(Product $inventory)
    {
        $stokAktif = (int) $inventory->stock;
        if ($stokAktif > 0) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus! Produk "' . $inventory->name . '" tidak bisa dihapus karena masih memiliki sisa stok (' . $stokAktif . ' unit). Kosongkan stok terlebih dahulu.');
        }

        $name = $inventory->name;
        $inventory->delete();

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Product "' . $name . '" berhasil dinonaktifkan.');
    }

    // ── POST /admin/inventory/{id}/restore
    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Product "' . $product->name . '" berhasil dikembalikan ke katalog.');
    }

    // ── DELETE /admin/inventory/image/{image}
    public function destroyImage(ProductImage $image)
    {
        // Hapus file fisik langsung dari folder public/image/
        $absolutePath = public_path($image->image_path);
        if (file_exists($absolutePath)) {
            @unlink($absolutePath);
        }
        
        $image->delete();
        return response()->json(['success' => true]);
    }
}