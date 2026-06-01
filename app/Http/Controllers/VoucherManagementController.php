<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\VoucherType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherManagementController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        // ── Stat cards ───────────────────────────────────────────────
        $totalTypes    = DB::table('voucher_types')->whereNull('deleted_at')->count();
        $activeTypes   = DB::table('voucher_types')->where('is_active', true)->whereNull('deleted_at')->count();
        $totalRedeemed = DB::table('vouchers')->whereNotNull('redeemed_at')->count();
        $orderCount    = Order::where('status', 'pending')->count();

        // Fix: use actual discount_amount from orders, not max_discount
        $totalDiscount = DB::table('orders')
            ->whereNotNull('voucher_id')
            ->sum('discount_amount');

        // ── Build base query ─────────────────────────────────────────
        // We group by name + max_discount to deduplicate, picking the MIN id as representative
        $query = DB::table('voucher_types as vt')
            ->whereNull('vt.deleted_at')
            ->select(
                DB::raw('MIN(vt.id) as id'),
                'vt.name',
                'vt.description',
                'vt.used_for',
                'vt.point_cost',
                'vt.discount_percentage',
                'vt.max_discount',
                // Group is active if ANY code in group is active
                DB::raw('MAX(CAST(vt.is_active AS UNSIGNED)) as is_active'),
                DB::raw('MIN(vt.created_at) as created_at'),
                // Total codes in this group (including deleted)
                DB::raw('(
                    SELECT COUNT(*) FROM voucher_types vt2
                    WHERE vt2.name = vt.name
                    AND vt2.max_discount = vt.max_discount
                    AND vt2.deleted_at IS NULL
                ) as total_quantity'),
                // Active codes in this group
                DB::raw('(
                    SELECT COUNT(*) FROM voucher_types vt3
                    WHERE vt3.name = vt.name
                    AND vt3.max_discount = vt.max_discount
                    AND vt3.deleted_at IS NULL
                    AND vt3.is_active = 1
                ) as active_count'),
                // Redeemed vouchers (via vouchers table) for this group
                DB::raw('(
                    SELECT COUNT(*) FROM vouchers v
                    JOIN voucher_types vt4 ON v.voucher_type_id = vt4.id
                    WHERE vt4.name = vt.name
                    AND vt4.max_discount = vt.max_discount
                    AND v.redeemed_at IS NOT NULL
                ) as redeemed_count'),
                // Total actual discount given for this group
                DB::raw('(
                    SELECT COALESCE(SUM(o.discount_amount), 0)
                    FROM orders o
                    JOIN vouchers v2 ON o.voucher_id = v2.id
                    JOIN voucher_types vt5 ON v2.voucher_type_id = vt5.id
                    WHERE vt5.name = vt.name
                    AND vt5.max_discount = vt.max_discount
                ) as total_discount_given')
            )
            ->groupBy(
                'vt.name',
                'vt.max_discount',
                'vt.description',
                'vt.used_for',
                'vt.point_cost',
                'vt.discount_percentage'
            )
            ->orderByRaw('MIN(vt.id)');

        // ── Filters ──────────────────────────────────────────────────
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('vt.used_for', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->havingRaw('MAX(CAST(vt.is_active AS UNSIGNED)) = 1');
            } else {
                $query->havingRaw('MAX(CAST(vt.is_active AS UNSIGNED)) = 0');
            }
        }

        if ($request->filled('min_discount')) {
            $query->where('vt.max_discount', '>=', $request->min_discount);
        }

        if ($request->filled('max_discount')) {
            $query->where('vt.max_discount', '<=', $request->max_discount);
        }

        // ── Pagination ───────────────────────────────────────────────
        $perPage     = 10;
        $currentPage = max(1, (int) $request->get('page', 1));

        // Use a subquery for clean pagination on grouped results
        $total      = DB::table(DB::raw("({$query->toSql()}) as sub"))
                        ->mergeBindings($query)
                        ->count();

        $voucherTypes = $query
            ->offset(($currentPage - 1) * $perPage)
            ->limit($perPage)
            ->get();

        $lastPage = max(1, (int) ceil($total / $perPage));

        return view('admin.voucher_management', compact(
            'orderCount', 'voucherTypes', 'total',
            'currentPage', 'lastPage', 'perPage',
            'totalTypes', 'activeTypes', 'totalRedeemed', 'totalDiscount'
        ));
    }

    // ── Store new voucher type(s) ─────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'description'         => 'required|string',
            'used_for'            => 'required|in:product,delivery',
            'discount_percentage' => 'required|integer|min:1|max:100',
            'max_discount'        => 'required|numeric|min:1000',
            'quantity'            => 'required|integer|min:1|max:100',
        ]);

        $pointCost = (int) round($request->max_discount / 250);
        $prefix    = $request->used_for === 'product' ? 'VP' : 'VD';
        $baseNum   = (int) ($request->max_discount / 1000);

        $existing = DB::table('voucher_types')
            ->where('name', $request->name)
            ->where('max_discount', $request->max_discount)
            ->count();

        $rows = [];
        for ($i = 1; $i <= $request->quantity; $i++) {
            $seq    = str_pad($existing + $i, 2, '0', STR_PAD_LEFT);
            $id     = $prefix . $baseNum . $seq;

            $rows[] = [
                'id'                  => $id,
                'name'                => $request->name,
                'description'         => $request->description,
                'used_for'            => $request->used_for,
                'point_cost'          => $pointCost,
                'discount_percentage' => $request->discount_percentage,
                'max_discount'        => $request->max_discount,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ];
        }

        DB::table('voucher_types')->insert($rows);

        return response()->json([
            'success' => true,
            'message' => $request->quantity . ' voucher(s) created successfully.',
        ]);
    }

    // ── Toggle GROUP active/inactive ──────────────────────────────────
    public function toggleGroup(Request $request, string $name)
    {
        // Find any voucher in this name group to determine current state
        $voucher = DB::table('voucher_types')
            ->where('name', $name)
            ->whereNull('deleted_at')
            ->first();

        if (!$voucher) {
            return response()->json(['error' => 'Voucher group not found.'], 404);
        }

        // Toggle: if ANY active, deactivate all; if all inactive, activate all
        $hasActive = DB::table('voucher_types')
            ->where('name', $name)
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->exists();

        $newState = !$hasActive;

        $affected = DB::table('voucher_types')
            ->where('name', $name)
            ->where('max_discount', $voucher->max_discount)
            ->whereNull('deleted_at')
            ->update(['is_active' => $newState, 'updated_at' => now()]);

        return response()->json([
            'success'   => true,
            'is_active' => $newState,
            'affected'  => $affected,
            'message'   => $affected . ' voucher(s) ' . ($newState ? 'activated' : 'deactivated') . '.',
        ]);
    }

    // ── Toggle single code active/inactive ────────────────────────────
    public function toggle(Request $request, string $id)
    {
        $voucher = DB::table('voucher_types')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$voucher) {
            return response()->json(['error' => 'Voucher not found.'], 404);
        }

        $newState = !$voucher->is_active;

        DB::table('voucher_types')
            ->where('id', $id)
            ->update(['is_active' => $newState, 'updated_at' => now()]);

        return response()->json([
            'success'   => true,
            'is_active' => $newState,
            'message'   => 'Voucher ' . $id . ' ' . ($newState ? 'activated' : 'deactivated') . '.',
        ]);
    }

    // ── Delete GROUP (only if none redeemed) ─────────────────────────
    public function destroy(string $id)
    {
        $voucher = VoucherType::find($id);

        if (!$voucher) {
            return response()->json(['error' => 'Voucher not found.'], 404);
        }

        $redeemed = DB::table('vouchers')
            ->join('voucher_types', 'vouchers.voucher_type_id', '=', 'voucher_types.id')
            ->where('voucher_types.name', $voucher->name)
            ->where('voucher_types.max_discount', $voucher->max_discount)
            ->whereNotNull('vouchers.redeemed_at')
            ->count();

        if ($redeemed > 0) {
            return response()->json([
                'error' => 'Cannot delete — ' . $redeemed . ' voucher(s) of this type have been redeemed.',
            ], 422);
        }

        VoucherType::where('name', $voucher->name)
            ->where('max_discount', $voucher->max_discount)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'All "' . $voucher->name . '" vouchers deleted.',
        ]);
    }

    // ── Delete single code ────────────────────────────────────────────
    public function destroyCode(string $id)
    {
        $voucher = VoucherType::find($id);

        if (!$voucher) {
            return response()->json(['error' => 'Voucher not found.'], 404);
        }

        // Check if this specific code has been redeemed
        $redeemed = DB::table('vouchers')
            ->where('voucher_type_id', $id)
            ->whereNotNull('redeemed_at')
            ->count();

        if ($redeemed > 0) {
            return response()->json([
                'error' => 'Cannot delete — this code has already been redeemed.',
            ], 422);
        }

        $voucher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Code ' . $id . ' deleted.',
        ]);
    }

    // ── Stats (AJAX) ──────────────────────────────────────────────────
    public function stats()
    {
        $totalTypes    = DB::table('voucher_types')->whereNull('deleted_at')->count();
        $activeTypes   = DB::table('voucher_types')->where('is_active', true)->whereNull('deleted_at')->count();
        $totalRedeemed = DB::table('vouchers')->whereNotNull('redeemed_at')->count();

        // Fix: use actual discount_amount
        $totalDiscount = DB::table('orders')
            ->whereNotNull('voucher_id')
            ->sum('discount_amount');

        return response()->json([
            'totalTypes'         => number_format($totalTypes),
            'activeTypes'        => number_format($activeTypes),
            'inactiveTypes'      => number_format($totalTypes - $activeTypes) . ' inactive',
            'totalRedeemed'      => number_format($totalRedeemed),
            'totalDiscount'      => 'Rp ' . number_format($totalDiscount / 1000000, 1) . 'M',
            'totalDiscountFull'  => 'Rp ' . number_format($totalDiscount, 0, ',', '.'),
        ]);
    }

    // ── Detail panel data ─────────────────────────────────────────────
    // Returns voucher group info + individual codes + recent orders
    public function detail(string $id)
    {
        $voucherType = DB::table('voucher_types')->where('id', $id)->whereNull('deleted_at')->first();

        if (!$voucherType) {
            return response()->json(['success' => false, 'error' => 'Voucher not found.'], 404);
        }

        // All codes in this group
        $codes = DB::table('voucher_types')
            ->where('name', $voucherType->name)
            ->where('max_discount', $voucherType->max_discount)
            ->whereNull('deleted_at')
            ->select('id', 'is_active', 'created_at')
            ->orderBy('id')
            ->get()
            ->map(function ($code) {
                // Check if this code has been assigned (has voucher records)
                $assigned = DB::table('vouchers')->where('voucher_type_id', $code->id)->count();
                $redeemed = DB::table('vouchers')->where('voucher_type_id', $code->id)->whereNotNull('redeemed_at')->count();
                $code->assigned = $assigned;
                $code->redeemed = $redeemed;
                return $code;
            });

        // Recent orders using ANY code in this group (fix: query by group)
        $orders = DB::table('orders')
            ->join('vouchers', 'orders.voucher_id', '=', 'vouchers.id')
            ->join('voucher_types', 'vouchers.voucher_type_id', '=', 'voucher_types.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('voucher_types.name', $voucherType->name)
            ->where('voucher_types.max_discount', $voucherType->max_discount)
            ->select(
                'orders.id',
                'orders.created_at',
                'orders.status',
                'orders.total_price',
                'orders.discount_amount',
                'voucher_types.id as voucher_code',
                'users.first_name',
                'users.last_name'
            )
            ->latest('orders.created_at')
            ->limit(20)
            ->get();

        // Group-level stats
        $totalUsed = $orders->count();
        $totalDiscountGiven = $orders->sum('discount_amount');

        return response()->json([
            'success'             => true,
            'name'                => $voucherType->name,
            'description'         => $voucherType->description,
            'used_for'            => $voucherType->used_for,
            'discount_percentage' => $voucherType->discount_percentage,
            'max_discount'        => $voucherType->max_discount,
            'point_cost'          => $voucherType->point_cost,
            'total_used'          => $totalUsed,
            'total_discount_given'=> $totalDiscountGiven,
            'codes'               => $codes,
            'orders'              => $orders,
        ]);
    }

    // ── Legacy: used orders (kept for compatibility) ───────────────────
    public function usedOrders(string $id)
    {
        return $this->detail($id);
    }
}