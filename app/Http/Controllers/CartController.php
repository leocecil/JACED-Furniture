<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $existingCart = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if($existingCart){

            $existingCart->quantity += $request->quantity;

            // prevent over stock
            if($existingCart->quantity > $product->stock){
                $existingCart->quantity = $product->stock;
            }

            $existingCart->save();

            $cart = $existingCart;

        } else {

            $cart = Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => min($request->quantity, $product->stock),
            ]);
        }

        // TOTAL
        $total = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get()
            ->sum(fn($item) => $item->product->price * $item->quantity);

        // COUNT
        $count = Cart::where('user_id', auth()->id())->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'quantity' => $cart->quantity,
            'total' => $total,
            'count' => $count,
        ]);
    }
    public function increase($id)
    {
        $cart = Cart::with('product')->findOrFail($id);

        if($cart->quantity < $cart->product->stock){
            $cart->quantity++;
            $cart->save();
        }

        $total = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get()
            ->sum(fn($item) => $item->product->price * $item->quantity);

        $count = Cart::where('user_id', auth()->id())->sum('quantity');

        return response()->json([
            'success' => true,
            'quantity' => $cart->quantity,
            'subtotal' => $cart->product->price * $cart->quantity,
            'total' => $total,
            'count' => $count,
        ]);
    }
    public function decrease($id)
    {
        $cart = Cart::with('product')->findOrFail($id);

        if($cart->quantity > 1){
            $cart->quantity--;
            $cart->save();
        }

        $total = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get()
            ->sum(fn($item) => $item->product->price * $item->quantity);

        $count = Cart::where('user_id', auth()->id())->sum('quantity');

        return response()->json([
            'success' => true,
            'quantity' => $cart->quantity,
            'subtotal' => $cart->product->price * $cart->quantity,
            'total' => $total,
            'count' => $count,
        ]);
    }
    public function delete($id)
    {
        $cart = Cart::findOrFail($id);

        $cart->delete();

        $total = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get()
            ->sum(fn($item) => $item->product->price * $item->quantity);

        $count = Cart::where('user_id', auth()->id())->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product removed',
            'total' => $total,
            'count' => $count,
        ]);
    }

    public function sidebarItems()
    {
        $globalCartItems = Cart::with(['product.mainImage', 'product.category'])
            ->where('user_id', auth()->id())
            ->get();

        return response()->json([
            'html' => view('partials.cart-sidebar', compact('globalCartItems'))->render(),
            'total' => $globalCartItems->sum(fn($item) => $item->product->price * $item->quantity),
            'count' => $globalCartItems->sum('quantity'),
        ]);
    }

    public function sidebar()
    {
        $globalCartItems = Cart::with(['product.mainImage', 'product.category'])
            ->where('user_id', auth()->id())
            ->get();

        $total = $globalCartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $count = $globalCartItems->sum('quantity');

        return response()->json([
            'html' => view('partials.cart-items-only', compact('globalCartItems'))->render(),
            'total' => $total,
            'count' => $count,
        ]);
    }
    public function index()
    {
        $carts = Cart::with('product')
                 ->where('user_id', Auth::id())
                     ->get();

        return response()->json($carts);
    }
}