<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $months = (int) $request->get('months', 6);
        $months = max(1, min(12, $months));
        $orderCount = Order::whereIn('status', ['on_process','packed'])->count();        // ── Stat Cards ───────────────────────────────────────────────
        $totalRevenue = DB::table('orders')
            ->whereNotIn('status', ['cancelled', 'unpaid'])
            ->selectRaw('SUM(total_price - revenue_deduction) as revenue')
            ->value('revenue') ?? 0;

        $totalOrders = DB::table('orders')->count();

        $inDelivery = DB::table('orders')
            ->where('status', 'delivered')
            ->count();

        $lowStockCount = DB::table('products')
            ->whereRaw('stock <= low_stock')
            ->count();

        // ── Sales Analytics ──────────────────────────────────────────
        $salesData = $this->getSalesData($months);

        // ── Monthly Target (from settings table) ─────────────────────
        $monthlyTarget = (float) DB::table('settings')
            ->where('key', 'monthly_target')
            ->value('value') ?? 50000000;

        $currentRevenue = DB::table('orders')
            ->whereNotIn('status', ['cancelled', 'unpaid'])
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('SUM(total_price - revenue_deduction) as revenue')
            ->value('revenue') ?? 0;

        $pct       = $monthlyTarget > 0 ? min(100, round(($currentRevenue / $monthlyTarget) * 100)) : 0;
        $remaining = max(0, $monthlyTarget - $currentRevenue);

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
            'orderCount',
            'totalRevenue',
            'totalOrders',
            'inDelivery',
            'lowStockCount',
            'salesData',
            'bestCategories',
            'recentOrders',
            'months',
            'monthlyTarget',
            'currentRevenue',
            'pct',
            'remaining'
        ));
    }

    // ── AJAX endpoint for Sales Analytics chart ──────────────────────
    public function salesChart(Request $request)
    {
        $months = (int) $request->get('months', 6);
        $months = max(1, min(12, $months));

        return response()->json($this->getSalesData($months));
    }

    // ── Set monthly target (persisted to DB) ─────────────────────────
    public function setTarget(Request $request)
    {
        $request->validate([
            'monthly_target' => 'required|numeric|min:1000000',
        ]);

        DB::table('settings')->updateOrInsert(
            ['key' => 'monthly_target'],
            ['value' => $request->monthly_target, 'updated_at' => now()]
        );

        return back()->with('success', 'Monthly target updated.');
    }

    // ── Shared helper ─────────────────────────────────────────────────
    private function getSalesData(int $months): array
    {
        $labels = [];
        $data   = [];

        $now = Carbon::now()->startOfMonth();
        for ($i = $months - 1; $i >= 0; $i--) {
            $date  = $now->copy()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end   = $date->copy()->endOfMonth();

        $revenue = DB::table('orders')
            ->whereNotIn('status', ['cancelled', 'unpaid'])
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('SUM(total_price - revenue_deduction) as revenue')
            ->value('revenue') ?? 0;

            $labels[] = $date->format('M Y');
            $data[]   = (float) $revenue;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function statCards(Request $request)
    {
        $range = $request->get('range', 'all');

        $query = DB::table('orders')->whereNotIn('status', ['cancelled', 'unpaid']);
        $countQuery = DB::table('orders');

        switch ($range) {
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $countQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month);
                $countQuery->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month);
                break;
            case '3m':
                $query->where('created_at', '>=', Carbon::now()->subMonths(3)->startOfDay());
                $countQuery->where('created_at', '>=', Carbon::now()->subMonths(3)->startOfDay());
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                $countQuery->whereYear('created_at', now()->year);
                break;
            // 'all' — no filter
        }

        return response()->json([
            'totalRevenue' => (float) $query->selectRaw('SUM(total_price - revenue_deduction) as revenue')->value('revenue') ?? 0,
            'totalOrders'  => (int)   $countQuery->count(),
        ]);
    }
}