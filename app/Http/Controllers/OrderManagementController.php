<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderManagementController extends Controller
{
    // ── Valid forward-only status transitions (admin only) ───────────
    private array $transitions = [
        'on_process' => 'packed',
        'packed'     => 'delivered',
        'delivered'  => 'shipped',
    ];

    // ── Index ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $this->autoCancelUnpaidOrders();
        $this->autoArriveOrders();

        $orders = $this->getOrders($request);
        $stats  = $this->getStats();

        return view('admin.order_management', compact('orders', 'stats'));
    }

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

    // ── Update order status ───────────────────────────────────────────
    public function updateStatus(Request $request, int $id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        if ($order->status === 'unpaid') {
            return response()->json(['error' => 'Cannot update unpaid orders. Waiting for customer payment.'], 422);
        }

        $nextStatus = $this->transitions[$order->status] ?? null;

        if (!$nextStatus) {
            return response()->json(['error' => 'No further status update available.'], 422);
        }

        $update = ['status' => $nextStatus, 'updated_at' => now()];
        if ($nextStatus === 'packed')    $update['packed_at']    = now();
        if ($nextStatus === 'delivered') $update['delivered_at'] = now();
        if ($nextStatus === 'shipped')   $update['shipped_at']   = now();

        DB::table('orders')->where('id', $id)->update($update);

        return response()->json([
            'success'    => true,
            'new_status' => $nextStatus,
            'message'    => 'Order #' . str_pad($id, 4, '0', STR_PAD_LEFT) . ' marked as ' . ucfirst(str_replace('_', ' ', $nextStatus)) . '.',
        ]);
    }

    // ── Resolve dispute ───────────────────────────────────────────────
    public function resolveDispute(Request $request, int $id)
    {
        $request->validate([
            'action'                      => 'required|in:refund,exchange,reject',
            'admin_note'                  => 'required|string|max:1000',
            'replacement_tracking_number' => 'nullable|string|max:255',
        ]);

        $dispute = DB::table('order_disputes')->where('id', $id)->first();
        if (!$dispute) {
            return response()->json(['error' => 'Dispute not found.'], 404);
        }

        if ($request->action === 'reject') {
            DB::table('order_disputes')->where('id', $id)->update([
                'status'          => 'rejected',
                'resolution_type' => null,
                'description'     => $dispute->description,
                'admin_note'      => $request->admin_note,
                'resolved_at'     => now(),
                'updated_at'      => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dispute rejected.',
            ]);
        }

        if ($request->action === 'refund') {
            DB::table('order_disputes')->where('id', $id)->update([
                'status'          => 'negotiating',
                'resolution_type' => 'refund',
                'admin_note'      => $request->admin_note,
                'updated_at'      => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund approved. Mark as resolved when refund is done.',
            ]);
        }

        if ($request->action === 'exchange') {
            $update = [
                'status'          => 'negotiating',
                'resolution_type' => 'exchange',
                'admin_note'      => $request->admin_note,
                'updated_at'      => now(),
            ];

            if ($request->filled('replacement_tracking_number')) {
                $update['replacement_tracking_number'] = $request->replacement_tracking_number;
                $update['replacement_shipped_at']      = now();
            }

            DB::table('order_disputes')->where('id', $id)->update($update);

            return response()->json([
                'success' => true,
                'message' => 'Exchange approved. Fill in tracking number when item is shipped.',
            ]);
        }
    }

    // ── Mark dispute as resolved (refund done / replacement arrived) ──
    public function markDisputeResolved(Request $request, int $id)
    {
        $dispute = DB::table('order_disputes')->where('id', $id)->first();
        if (!$dispute) {
            return response()->json(['error' => 'Dispute not found.'], 404);
        }

        $update = [
            'status'      => 'resolved',
            'resolved_at' => now(),
            'updated_at'  => now(),
        ];

        if ($dispute->resolution_type === 'exchange') {
            $update['replacement_arrived_at'] = now();
        }

        DB::table('order_disputes')->where('id', $id)->update($update);

        return response()->json([
            'success' => true,
            'message' => 'Dispute marked as resolved.',
        ]);
    }

    // ── Update exchange tracking number ───────────────────────────────
    public function updateTracking(Request $request, int $id)
    {
        $request->validate([
            'replacement_tracking_number' => 'required|string|max:255',
        ]);

        $dispute = DB::table('order_disputes')->where('id', $id)->first();
        if (!$dispute) {
            return response()->json(['error' => 'Dispute not found.'], 404);
        }

        DB::table('order_disputes')->where('id', $id)->update([
            'replacement_tracking_number' => $request->replacement_tracking_number,
            'replacement_shipped_at'      => now(),
            'updated_at'                  => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tracking number updated.',
        ]);
    }

    // ── Auto-cancel unpaid orders older than 24h ──────────────────────
    private function autoCancelUnpaidOrders(): void
    {
        DB::table('orders')
            ->where('status', 'unpaid')
            ->where('created_at', '<=', Carbon::now()->subHours(24))
            ->update([
                'status'              => 'cancelled',
                'cancelled_at'        => now(),
                'cancellation_reason' => 'Payment timeout — order automatically cancelled after 24 hours.',
                'updated_at'          => now(),
            ]);
    }

    // ── Auto-arrive shipped orders older than 7 days ──────────────────
    private function autoArriveOrders(): void
    {
        $autoArrived = DB::table('orders')
            ->where('status', 'shipped')
            ->whereNotNull('shipped_at')
            ->where('shipped_at', '<=', Carbon::now()->subDays(7))
            ->get();

        foreach ($autoArrived as $order) {
            DB::table('orders')->where('id', $order->id)->update([
                'status'     => 'arrived',
                'arrived_at' => now(),
                'updated_at' => now(),
            ]);

            $pointsEarned = (int) floor($order->total_price / 10000);
            if ($pointsEarned > 0) {
                DB::table('users')->where('id', $order->user_id)->increment('current_points', $pointsEarned);
                DB::table('users')->where('id', $order->user_id)->increment('accumulated_points', $pointsEarned);
                DB::table('point_histories')->insert([
                    'user_id'    => $order->user_id,
                    'points'     => $pointsEarned,
                    'type'       => 'earned',
                    'source'     => 'purchase',
                    'order_id'   => $order->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    // ── Stat cards ────────────────────────────────────────────────────
    private function getStats(): array
    {
        return [
            'unpaid'          => DB::table('orders')->where('status', 'unpaid')->count(),
            'delivered'       => DB::table('orders')->where('status', 'delivered')->count(),
            'open_disputes'   => DB::table('order_disputes')->where('status', 'open')->count(),
            'weekly_revenue'  => DB::table('orders')
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
            ->leftJoin('order_disputes', 'order_disputes.order_id', '=', 'orders.id')
            ->select(
                'orders.id',
                'orders.status',
                'orders.total_price',
                'orders.delivery_fee',
                'orders.service_tax',
                'orders.discount_amount',
                'orders.created_at',
                'orders.on_process_at',
                'orders.packed_at',
                'orders.delivered_at',
                'orders.shipped_at',
                'orders.arrived_at',
                'orders.cancelled_at',
                'orders.cancellation_reason',
                'users.name as customer_name',
                'users.email as customer_email',
                'users.phone_number as customer_phone',
                'payment_methods.name as payment_method',
                'shipping_address.address_line1',
                'shipping_address.city_name',
                'shipping_address.province_name',
                'shipping_address.postal_code',
                'shipping_address.receiver_name',
                'shipping_address.receiver_phone',
                // Dispute info joined directly
                'order_disputes.id as dispute_id',
                'order_disputes.reason as dispute_reason',
                'order_disputes.description as dispute_description',
                'order_disputes.status as dispute_status',
                'order_disputes.resolution_type as dispute_resolution_type',
                'order_disputes.photo_path as dispute_photo',
                'order_disputes.admin_note as dispute_admin_note',
                'order_disputes.return_tracking_number as dispute_return_tracking',
                'order_disputes.replacement_tracking_number as dispute_replacement_tracking',
                'order_disputes.replacement_shipped_at as dispute_replacement_shipped_at',
                'order_disputes.replacement_arrived_at as dispute_replacement_arrived_at',
                'order_disputes.resolved_at as dispute_resolved_at'
            );

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('users.name', 'like', "%{$s}%")
                  ->orWhereRaw("LPAD(orders.id, 4, '0') like ?", ["%{$s}%"]);
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'disputed') {
                $query->whereNotNull('order_disputes.id');
            } else {
                $query->where('orders.status', $request->status);
            }
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