<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $months = (int) $request->get('months', 6);
        $months = max(1, min(12, $months));

        // ── Stat Cards ───────────────────────────────────────────────
        $totalRevenue = DB::table('orders')
            ->whereNotIn('status', ['cancelled', 'unpaid'])
            ->sum('total_price');

        $totalOrders = DB::table('orders')->count();

        $inDelivery = DB::table('orders')
            ->where('status', 'delivered')
            ->count();

        $lowStockCount = DB::table('products')
            ->whereRaw('stock <= low_stock')
            ->count();

        // ── Sales Analytics ──────────────────────────────────────────
        $salesData = $this->getSalesData($months);

        // ── Best Selling Categories ──────────────────────────────────
        $bestCategories = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', ['cancelled', 'unpaid'])
            ->select(
                'product_categories.id',
                'product_categories.name as category_name',
                DB::raw('SUM(order_details.quantity) as total_units'),
                DB::raw('SUM(order_details.subtotal) as total_revenue')
            )
            ->groupBy('product_categories.id', 'product_categories.name')
            ->orderByDesc('total_units')
            ->limit(5)
            ->get();

        // ── Recent Orders ────────────────────────────────────────────
        $recentOrders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'orders.id',
                'orders.total_price',
                'orders.status',
                'orders.created_at',
                'users.name as customer_name'
            )
            ->orderByDesc('orders.created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'inDelivery',
            'lowStockCount',
            'salesData',
            'bestCategories',
            'recentOrders',
            'months'
        ));
    }

    // ── AJAX endpoint for Sales Analytics chart ──────────────────────
    public function salesChart(Request $request)
    {
        $months = (int) $request->get('months', 6);
        $months = max(1, min(12, $months));

        return response()->json($this->getSalesData($months));
    }

    // ── Shared helper ─────────────────────────────────────────────────
    private function getSalesData(int $months): array
    {
        $labels = [];
        $data   = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date  = Carbon::now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end   = $date->copy()->endOfMonth();

            $revenue = DB::table('orders')
                ->whereNotIn('status', ['cancelled', 'unpaid'])
                ->whereBetween('created_at', [$start, $end])
                ->sum('total_price');

            $labels[] = $date->format('M Y');
            $data[]   = (float) $revenue;
        }

        return ['labels' => $labels, 'data' => $data];
    }
}