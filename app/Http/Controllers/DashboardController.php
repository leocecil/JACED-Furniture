<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // 1. Monthly Target Setup (Fallback to 125000 if not set)
        $monthlyTarget = $request->get('target', 125000);

        // 2. Metrics Calculations
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');
        $totalOrdersCount = Order::count();
        $inDeliveryCount = Order::whereIn('status', ['delivered'])->count(); // Adjust strings based on your actual lifecycle statuses
        
        // Low Stock Count based on custom column check (stock <= low_stock)
        $lowStockCount = Product::whereRaw('stock <= low_stock')->count();

        // 3. Monthly Target Donut Progress
        $currentMonthRevenue = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        $targetPercentage = $monthlyTarget > 0 ? round(($currentMonthRevenue / $monthlyTarget) * 100) : 0;
        $remainingTarget = max(0, $monthlyTarget - $currentMonthRevenue);

        // 4. Best Selling Categories Breakdown (Instead of Traffic)
        $totalItemsOrdered = OrderDetail::sum('quantity') ?: 1; // avoid division by zero
        
        $bestSellingCategories = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->select('product_categories.name', DB::raw('SUM(order_details.quantity) as total_qty'))
            ->groupBy('product_categories.id', 'product_categories.name')
            ->orderByDesc('total_qty')
            ->take(4) // matches dashboard layout slots
            ->get()
            ->map(function ($item) use ($totalItemsOrdered) {
                $item->percentage = round(($item->total_qty / $totalItemsOrdered) * 100);
                return $item;
            });

        // 5. Best Selling Products (Row 3)
        $bestSellingProducts = Product::select('products.*')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->selectRaw('SUM(order_details.quantity) as units_sold')
            ->groupBy('products.id')
            ->orderByDesc('units_sold')
            ->take(3)
            ->get();

        // 6. Recent Orders (Row 4 - Capped precisely at 5 rows)
        $recentOrdersQuery = Order::with(['user'])->orderByDesc('created_at');
        
        if ($request->has('status_filter') && $request->status_filter != 'all') {
            $recentOrdersQuery->where('status', $request->status_filter);
        }
        $recentOrders = $recentOrdersQuery->take(5)->get();

        // 7. Sales Analytics Toggle Logic (1 Month vs 1 Year)
        $range = $request->get('range', '1m'); 
        $salesLabels = [];
        $salesData = [];

        if ($range === '1y') {
            // Group by months for the past year
            $analyticsData = Order::where('status', '!=', 'cancelled')
                ->where('created_at', '>=', Carbon::now()->subYear())
                ->selectRaw('YEAR(created_at) year, MONTH(created_at) month, SUM(total_price) total')
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            foreach ($analyticsData as $data) {
                $salesLabels[] = Carbon::create($data->year, $data->month, 1)->format('M Y');
                $salesData[] = (float)$data->total;
            }
        } else {
            // Default 1 Month: Break down last 30 days into daily or week intervals. 
            // For neatness matching your UI, let's grab last 6 individual weeks/days or standard past 6 months trend
            $analyticsData = Order::where('status', '!=', 'cancelled')
                ->where('created_at', '>=', Carbon::now()->subMonths(5))
                ->selectRaw('YEAR(created_at) year, MONTH(created_at) month, SUM(total_price) total')
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            foreach ($analyticsData as $data) {
                $salesLabels[] = Carbon::create($data->year, $data->month, 1)->format('M');
                $salesData[] = (float)$data->total;
            }
        }

        // If it's an AJAX call from the switch UI dropdown, return JSON for Chart.js
        if ($request->ajax()) {
            return response()->json([
                'labels' => $salesLabels,
                'data' => $salesData
            ]);
        }

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrdersCount', 'inDeliveryCount', 'lowStockCount',
            'monthlyTarget', 'currentMonthRevenue', 'targetPercentage', 'remainingTarget',
            'bestSellingCategories', 'bestSellingProducts', 'recentOrders',
            'salesLabels', 'salesData', 'range'
        ));
    }
}