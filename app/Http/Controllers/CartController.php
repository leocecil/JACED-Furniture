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
        $existingCart = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if($existingCart){
            $existingCart->quantity += $request->quantity;
            $existingCart->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Product added to cart');
    }
    public function increase($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->quantity++;
        $cart->save();
        return back();
    }
    public function decrease($id)
    {
        $cart = Cart::findOrFail($id);
        if($cart->quantity > 1){
            $cart->quantity--;
            $cart->save();
        }
        return back();
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
    public function delete($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return back();
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