<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        DB::table('orders')
            ->where('user_id', $user->id)
            ->where('status', 'delivered')
            ->whereNotNull('shipped_at')
            ->where('shipped_at', '<=', now()->subDays(7))
            ->update(['status' => 'arrived', 'arrived_at' => now()]);

        $filters = ['All', 'Unpaid', 'On Process', 'Packed', 'Delivered', 'Arrived', 'Cancelled'];
        $activeFilter = $request->get('filter', 'All');

        $query = Order::with(['orderDetails.product'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($activeFilter !== 'All') {
            $query->where('status', strtolower(str_replace(' ', '_', $activeFilter)));
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

    public function markReceived($id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'Order tidak bisa dikonfirmasi.');
        }

        $order->status = 'arrived';
        $order->arrived_at = now();
        $order->save();

        // Tambah poin saat arrived
        $poinBaru = floor($order->total_price / 10000);
        if ($poinBaru > 0) {
            $user = Auth::user();
            $user->increment('current_points', $poinBaru);
            $user->increment('accumulated_points', $poinBaru);

            DB::table('point_histories')->insert([
                'user_id'    => $user->id,
                'points'     => $poinBaru,
                'type'       => 'earned',
                'source'     => 'purchase',
                'order_id'   => $order->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Order confirmed! You earned ' . $poinBaru . ' points.');
    }

    public function submitComplaint(Request $request, $id)
    {
        $request->validate([
            'type'        => 'required|in:missing,damaged,wrong_item',
            'description' => 'required|string|max:1000',
            'photo'       => 'nullable|image|max:2048',
        ]);

        $order = Auth::user()->orders()->findOrFail($id);

        if (!in_array($order->status, ['delivered', 'arrived'])) {
            return redirect()->back()->with('error', 'Komplain hanya bisa diajukan untuk order yang sudah dikirim.');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('complaints', 'public');
        }

        DB::table('order_complaints')->insert([
            'order_id'    => $order->id,
            'user_id'     => Auth::id(),
            'type'        => $request->type,
            'description' => $request->description,
            'photo_path'  => $photoPath,
            'status'      => 'pending',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Update status order jadi disputed
        $order->status = 'disputed';
        $order->disputed_at = now();
        $order->save();

        return redirect()->back()->with('success', 'Komplain berhasil diajukan. Admin akan segera meninjau.');
    }

    public function cancelOrder($id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if ($order->status !== 'unpaid') {
            return redirect()->back()->with('error', 'Order tidak bisa dibatalkan.');
        }

        $order->status = 'cancelled';
        $order->cancelled_at = now();
        $order->save();

        return redirect()->back()->with('success', 'Order berhasil dibatalkan.');
    }
}
