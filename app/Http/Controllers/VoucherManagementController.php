<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherManagementController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        // ── Stat cards ───────────────────────────────────────────────
        $totalTypes   = DB::table('voucher_types')->count();
        $activeTypes  = DB::table('voucher_types')->where('is_active', true)->count();
        $totalRedeemed = DB::table('vouchers')->whereNotNull('redeemed_at')->count();
        $orderCount = Order::where('status', ['pending','packed'])->count();
        $totalDiscount = DB::table('vouchers')
            ->join('voucher_types', 'vouchers.voucher_type_id', '=', 'voucher_types.id')
            ->whereNotNull('vouchers.redeemed_at')
            ->sum('voucher_types.max_discount');

        // ── Voucher types query ──────────────────────────────────────
        $query = DB::table('voucher_types')
            ->select(
                'voucher_types.id',
                'voucher_types.name',
                'voucher_types.description',
                'voucher_types.used_for',
                'voucher_types.point_cost',
                'voucher_types.discount_percentage',
                'voucher_types.max_discount',
                'voucher_types.is_active',
                'voucher_types.created_at',
                // Count how many voucher_type rows share the same name+max_discount
                DB::raw('(
                    SELECT COUNT(*) FROM voucher_types vt2
                    WHERE vt2.name = voucher_types.name
                    AND vt2.max_discount = voucher_types.max_discount
                ) as total_quantity'),
                // Count how many have been redeemed (vouchers with redeemed_at not null)
                DB::raw('(
                    SELECT COUNT(*) FROM vouchers v
                    JOIN voucher_types vt3 ON v.voucher_type_id = vt3.id
                    WHERE vt3.name = voucher_types.name
                    AND vt3.max_discount = voucher_types.max_discount
                    AND v.redeemed_at IS NOT NULL
                ) as redeemed_count'),
                // Count available (not yet redeemed)
                DB::raw('(
                    SELECT COUNT(*) FROM voucher_types vt4
                    WHERE vt4.name = voucher_types.name
                    AND vt4.max_discount = voucher_types.max_discount
                    AND vt4.is_active = 1
                ) as available_count')
            )
            // Show one row per unique name+max_discount combination
            ->groupBy(
                'voucher_types.name',
                'voucher_types.max_discount',
                'voucher_types.id',
                'voucher_types.description',
                'voucher_types.used_for',
                'voucher_types.point_cost',
                'voucher_types.discount_percentage',
                'voucher_types.is_active',
                'voucher_types.created_at'
            )
            ->orderByRaw("MIN(voucher_types.id)");

        // ── Filters ──────────────────────────────────────────────────
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('voucher_types.used_for', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('voucher_types.is_active', $request->status === 'active' ? 1 : 0);
        }

        if ($request->filled('min_discount')) {
            $query->where('voucher_types.max_discount', '>=', $request->min_discount);
        }

        if ($request->filled('max_discount')) {
            $query->where('voucher_types.max_discount', '<=', $request->max_discount);
        }

        // Deduplicate: only show first row per unique name+max_discount group
        $allRows = $query->get();
        $seen    = [];
        $voucherTypes = $allRows->filter(function ($row) use (&$seen) {
            $key = $row->name . '||' . $row->max_discount;
            if (in_array($key, $seen)) return false;
            $seen[] = $key;
            return true;
        })->values();

        // Manual pagination
        $perPage     = 10;
        $currentPage = (int) $request->get('page', 1);
        $total       = $voucherTypes->count();
        $paged       = $voucherTypes->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $lastPage    = (int) ceil($total / $perPage);

        return view('admin.voucher_management', compact('orderCount', 'paged', 'total', 'currentPage', 'lastPage', 'perPage', 'totalTypes', 'activeTypes', 'totalRedeemed', 'totalDiscount'));
    }

    // ── Store new voucher type(s) ─────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'description'          => 'required|string',
            'used_for'             => 'required|in:product,delivery',
            'discount_percentage'  => 'required|integer|min:1|max:100',
            'max_discount'         => 'required|numeric|min:1000',
            'quantity'             => 'required|integer|min:1|max:100',
        ]);

        // Auto-calculate point cost
        $pointCost = (int) round($request->max_discount / 250);

        // Build prefix and base number from max_discount
        $prefix    = $request->used_for === 'product' ? 'VP' : 'VD';
        $baseNum   = (int) ($request->max_discount / 1000); // e.g. 150000 → 150

        // Find existing count of vouchers with this exact name + max_discount
        $existing = DB::table('voucher_types')
            ->where('name', $request->name)
            ->where('max_discount', $request->max_discount)
            ->count();

        $rows = [];
        for ($i = 1; $i <= $request->quantity; $i++) {
            $seq      = str_pad($existing + $i, 2, '0', STR_PAD_LEFT);
            $id       = $prefix . $baseNum . $seq; // e.g. VP15003

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

    // ── Toggle active/inactive (updates ALL vouchers in the same group) ─
    public function toggle(Request $request, string $id)
    {
        $voucher = DB::table('voucher_types')->where('id', $id)->first();

        if (!$voucher) {
            return response()->json(['error' => 'Voucher not found.'], 404);
        }

        $newState = !$voucher->is_active;

        // Update ALL rows with the same name + max_discount (the whole group)
        $affected = DB::table('voucher_types')
            ->where('name', $voucher->name)
            ->where('max_discount', $voucher->max_discount)
            ->update(['is_active' => $newState, 'updated_at' => now()]);

        return response()->json([
            'success'   => true,
            'is_active' => $newState,
            'affected'  => $affected,
            'message'   => $affected . ' voucher(s) ' . ($newState ? 'activated' : 'deactivated') . ' successfully.',
        ]);
    }

    // ── Delete (only if none redeemed in this group) ──────────────────
    public function destroy(string $id)
    {
        $voucher = DB::table('voucher_types')->where('id', $id)->first();

        if (!$voucher) {
            return response()->json(['error' => 'Voucher not found.'], 404);
        }

        // Check if any voucher of this type has been redeemed
        $redeemed = DB::table('vouchers')
            ->join('voucher_types', 'vouchers.voucher_type_id', '=', 'voucher_types.id')
            ->where('voucher_types.name', $voucher->name)
            ->where('voucher_types.max_discount', $voucher->max_discount)
            ->whereNotNull('vouchers.redeemed_at')
            ->count();

        if ($redeemed > 0) {
            return response()->json([
                'error' => 'Cannot delete — ' . $redeemed . ' voucher(s) of this type have already been redeemed.',
            ], 422);
        }

        // Delete all voucher_type rows with same name + max_discount
        DB::table('voucher_types')
            ->where('name', $voucher->name)
            ->where('max_discount', $voucher->max_discount)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voucher type deleted successfully.',
        ]);
    }

    // ── Stats (AJAX) ──────────────────────────────────────────────────
    public function stats()
    {
        $totalTypes    = DB::table('voucher_types')->count();
        $activeTypes   = DB::table('voucher_types')->where('is_active', true)->count();
        $totalRedeemed = DB::table('vouchers')->whereNotNull('redeemed_at')->count();
        $totalDiscount = DB::table('vouchers')
            ->join('voucher_types', 'vouchers.voucher_type_id', '=', 'voucher_types.id')
            ->whereNotNull('vouchers.redeemed_at')
            ->sum('voucher_types.max_discount');

        return response()->json([
            'totalTypes'    => number_format($totalTypes),
            'activeTypes'   => number_format($activeTypes),
            'inactiveTypes' => number_format($totalTypes - $activeTypes),
            'totalRedeemed' => number_format($totalRedeemed),
            'totalDiscount' => 'Rp ' . number_format($totalDiscount / 1000000, 1) . 'M',
            'totalDiscountFull' => 'Rp ' . number_format($totalDiscount, 0, ',', '.'),
        ]);
    }
}