<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $filters = ['All', 'Unpaid', 'Packed', 'Delivered', 'Arrived', 'Cancelled'];
        $activeFilter = $request->get('filter', 'All');

        $query = Order::with(['orderDetails.product'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($activeFilter !== 'All') {
            $query->where('status', strtolower($activeFilter));
        }

        $orders = $query->get();

        return view('store.order-history', compact('orders', 'filters', 'activeFilter'));
    }

    public function show($id)
    {
        $order = Order::with([
            'orderDetails.product.images',
            'shippingAddress',
            'paymentMethod',
            'voucher',
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

        return view('store.order-history-detail', compact('order'));
    }

    public function invoice($id)
    {
        $order = \App\Models\Order::with([
            'orderDetails.product.images',
            'shippingAddress',
            'paymentMethod',
            'user',
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

        return view('store.invoice', compact('order'));
    }
}
