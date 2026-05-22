<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Tambah produk ke cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                    ->first();

        if ($cart) {
            // Kalau sudah ada, update quantity
            $cart->increment('quantity', $request->quantity);
        } else {
            // Kalau belum ada, buat baru
            Cart::create([
                'user_id'    => Auth::id(),
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Produk ditambahkan ke cart']);
    }

    // Update quantity
    public function update(Request $request, $id)
    {
        $cart = Cart::where('id', $id)
                ->where('user_id', Auth::id())
                    ->firstOrFail();

        $cart->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart diupdate']);
    }

    // Hapus item dari cart
    public function remove($id)
    {
        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json(['message' => 'Item dihapus dari cart']);
    }

    // Ambil semua item cart user
    public function index()
    {
        $carts = Cart::with('product')
                 ->where('user_id', Auth::id())
                     ->get();

        return response()->json($carts);
    }
}