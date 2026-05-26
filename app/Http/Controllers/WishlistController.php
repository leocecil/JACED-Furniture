<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // SHOW PAGE
    public function index()
    {
        $wishlists = Wishlist::with([
            'product.images',
            'product.category',
            'product.mainImage'
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

        return view('store.wishlist', compact('wishlists'));
    }

    // TOGGLE WISHLIST
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        // REMOVE IF EXISTS
        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'status' => 'removed'
            ]);
        }

        // ADD IF NOT EXISTS
        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => 'added'
        ]);
    }

    // REMOVE SINGLE
    public function remove($id)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $id)
            ->delete();

        return response()->json([
            'success' => true
        ]);
    }

    // CLEAR ALL
    public function clear()
    {
        Wishlist::where('user_id', auth()->id())
            ->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function items()
    {
        $wishlists = Wishlist::with([
            'product.images',
            'product.category',
            'product.mainImage'
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();
        return response()->json($wishlists);
    }
}