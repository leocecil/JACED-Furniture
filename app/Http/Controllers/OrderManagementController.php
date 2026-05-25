<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderManagementController extends Controller
{
    //
    // ── Valid forward-only status transitions (admin only) ───────────
    private array $transitions = [
        'unpaid' => 'packed',
        'packed' => 'delivered',
        // delivered → arrived: customer action or auto after 1 week
    ];
 
    // ── Index (full page load) ────────────────────────────────────────
    public function index(Request $request)
    {
        // Run auto-arrive check on every page load
        $this->autoArriveOrders();
 
        $orders = $this->getOrders($request);
        $stats  = $this->getStats();
 
        return view('admin.order_management', compact('orders', 'stats'));
    }
 
    // ── AJAX search + filter ──────────────────────────────────────────
    // ── AJAX search + filter ──────────────────────────────────────────
    public function search(Request $request)
    {
        $orders = $this->getOrders($request);

        return response()->json([
            'html'       => view('admin.partials.order_management_rows', compact('orders'))->render(),
            'pagination' => $orders->onEachSide(1)->links('pagination::bootstrap-5')->render(),
            'total'      => $orders->total(),
            'from'       => $orders->firstItem() ?? 0,
            'to'         => $orders->lastItem()   ?? 0,
        ]);
    }
 
    // ── Update status (forward only) ──────────────────────────────────
    public function updateStatus(Request $request, int $id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
 
        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }
 
        $nextStatus = $this->transitions[$order->status] ?? null;
 
        if (!$nextStatus) {
            return response()->json(['error' => 'No further status update available.'], 422);
        }
 
        $update = ['status' => $nextStatus, 'updated_at' => now()];
        if ($nextStatus === 'packed')    $update['packed_at']    = now();
        if ($nextStatus === 'delivered') $update['delivered_at'] = now();
 
        DB::table('orders')->where('id', $id)->update($update);
 
        return response()->json([
            'success'    => true,
            'new_status' => $nextStatus,
            'message'    => 'Order #' . str_pad($id, 4, '0', STR_PAD_LEFT) . ' marked as ' . ucfirst($nextStatus) . '.',
        ]);
    }
 
    // ── Auto-arrive: delivered orders older than 1 week ───────────────
    private function autoArriveOrders(): void
    {
        DB::table('orders')
            ->where('status', 'delivered')
            ->where('delivered_at', '<=', Carbon::now()->subWeek())
            ->update([
                'status'     => 'arrived',
                'arrived_at' => now(),
                'updated_at' => now(),
            ]);
    }
 
    // ── Stat cards ────────────────────────────────────────────────────
    private function getStats(): array
    {
        return [
            'unpaid'         => DB::table('orders')->where('status', 'unpaid')->count(),
            'delivered'      => DB::table('orders')->where('status', 'delivered')->count(),
            'weekly_revenue' => DB::table('orders')
                ->whereNotIn('status', ['cancelled', 'unpaid'])
                ->where('created_at', '>=', Carbon::now()->startOfWeek())
                ->sum('total_price'),
        ];
    }
 
    // ── Shared query builder ──────────────────────────────────────────
    private function getOrders(Request $request)
    {
        $query = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('payment_methods', 'orders.payment_id', '=', 'payment_methods.id')
            ->join('shipping_address', 'orders.shipping_address_id', '=', 'shipping_address.id')
            ->select(
                'orders.id',
                'orders.status',
                'orders.total_price',
                'orders.delivery_fee',
                'orders.service_tax',
                'orders.discount_amount',
                'orders.created_at',
                'orders.packed_at',
                'orders.delivered_at',
                'orders.arrived_at',
                'orders.cancelled_at',
                'users.name as customer_name',
                'users.email as customer_email',
                'users.phone_number as customer_phone',
                'payment_methods.name as payment_method',
                'shipping_address.address_line1',
                'shipping_address.city_name',
                'shipping_address.province_name',
                'shipping_address.postal_code',
                'shipping_address.receiver_name',
                'shipping_address.receiver_phone'
            );
 
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('users.name', 'like', "%{$s}%")
                  ->orWhereRaw("LPAD(orders.id, 4, '0') like ?", ["%{$s}%"]);
            });
        }
 
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('orders.status', $request->status);
        }
 
        if ($request->filled('payment') && $request->payment !== 'all') {
            $query->where('payment_methods.name', $request->payment);
        }
 
        if ($request->filled('date_from')) {
            $query->whereDate('orders.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('orders.created_at', '<=', $request->date_to);
        }
 
        return $query->orderByDesc('orders.created_at')->paginate(10);
    }
}
