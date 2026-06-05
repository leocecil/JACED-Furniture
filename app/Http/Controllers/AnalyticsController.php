<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    // ── GET /admin/customer-analytics (Halaman Index Dashboard Analitik)
    public function index()
    {
        // 0. Ambil angka riil pesanan masuk berstatus 'pending' untuk indikator badge sidebar
        $orderCount = Order::whereIn('status', ['on_process','packed'])->count();
        $tiers = User::where('is_admin', false)
            ->select(DB::raw("
                COUNT(CASE WHEN accumulated_points < 500 THEN 1 END) as bronze,
                COUNT(CASE WHEN accumulated_points >= 500 AND accumulated_points < 1500 THEN 1 END) as silver,
                COUNT(CASE WHEN accumulated_points >= 1500 AND accumulated_points < 3500 THEN 1 END) as gold,
                COUNT(CASE WHEN accumulated_points >= 3500 THEN 1 END) as platinum
            "))->first();

        $spenders = Order::whereIn('status', ['completed', 'arrived'])
            ->select('user_id', DB::raw('SUM(total_price) as total_spend'))
            ->groupBy('user_id')
            ->orderBy('total_spend', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                
                $user = User::find($order->user_id);
                if (!$user || $user->is_admin) {
                    return null;
                }
                $user->total_spend = $order->total_spend;

                if ($user->accumulated_points < 500) {
                    $user->tier = 'BRONZE';
                    $user->badge = '#CD7F32';
                } elseif ($user->accumulated_points < 1500) {
                    $user->tier = 'SILVER';
                    $user->badge = '#A6A6A6';
                } elseif ($user->accumulated_points < 3500) {
                    $user->tier = 'GOLD';
                    $user->badge = '#D4AF37';
                } else {
                    $user->tier = 'PLATINUM';
                    $user->badge = '#708090';
                }
                return $user;
            })
            ->filter() 
            ->values();

        $regionsQuery = Order::whereIn('orders.status', ['completed', 'arrived'])
            ->join('shipping_address', 'orders.shipping_address_id', '=', 'shipping_address.id')
            ->select('shipping_address.province_name', DB::raw('COUNT(orders.id) as total_orders'))
            ->groupBy('shipping_address.province_name')
            ->orderBy('total_orders', 'desc')
            ->take(3)
            ->get();

        $top3Provinces = $regionsQuery->pluck('province_name')->toArray();
        
        $otherProvincesCount = Order::whereIn('orders.status', ['completed', 'arrived'])
            ->join('shipping_address', 'orders.shipping_address_id', '=', 'shipping_address.id')
            ->whereNotIn('shipping_address.province_name', $top3Provinces)
            ->count();

        $regionsLabels = $regionsQuery->pluck('province_name')->toArray();
        $regionsData = $regionsQuery->pluck('total_orders')->toArray();

        if ($otherProvincesCount > 0) {
            $regionsLabels[] = 'OTHERS';
            $regionsData[] = $otherProvincesCount;
        }

        $revenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenueTrend[$month->format('M')] = [
                'revenue' => 0,
                'order_count' => 0
            ];
        }

        $monthlyData = Order::whereIn('status', ['completed', 'arrived'])
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%b') as month_name"),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('COUNT(id) as total_transactions')
            )
            ->groupBy('month_name')
            ->get();

        foreach ($monthlyData as $data) {
            if (isset($revenueTrend[$data->month_name])) {
                $revenueTrend[$data->month_name]['revenue'] = (float)$data->total_revenue;
                $revenueTrend[$data->month_name]['order_count'] = (int)$data->total_transactions;
            }
        }

        $allCustomers = User::where('is_admin', false)
            ->withCount('orders')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'avatar']);

        // Kirim seluruh variabel bersih ke file blade index
        return view('pages.analytics.index', [
            'orderCount' => $orderCount,
            'tiers' => $tiers,
            'spenders' => $spenders,
            'allCustomers' => $allCustomers,
            'regionsLabels' => $regionsLabels,
            'regionsData' => $regionsData,
            'trendLabels' => array_keys($revenueTrend),
            'trendRevenue' => array_column($revenueTrend, 'revenue'),
            'trendOrders' => array_column($revenueTrend, 'order_count'),
        ]);
    }

    /**
     * Menampilkan Halaman Detail Profil & Log Riwayat Belanja Customer Spesifik
     */
    public function show($id)
    {
        // 1. Ambil data pesanan masuk untuk sidebar badge agar layout master tidak error
        $orderCount = Order::whereIn('status', ['on_process','packed'])->count();

        // 2. Ambil data kustomer beserta log seluruh riwayat transaksinya
        $customer = User::where('is_admin', false)
            ->with(['orders' => function($query) {
                $query->orderByDesc('created_at');
            }])
            ->findOrFail($id);

        // 3. Hitung akumulasi belanja bersih kustomer untuk display resume ringkasan profil
        $totalSpend = $customer->orders->whereIn('status', ['completed', 'arrived'])->sum('total_price');

        // 4. Hitung tiering kustomer secara dinamis berdasarkan poin akumulasi riilnya
        if ($customer->accumulated_points < 500) {
            $customer->tier_name = 'BRONZE Member';
            $customer->tier_badge = '#CD7F32';
        } elseif ($customer->accumulated_points < 1500) {
            $customer->tier_name = 'SILVER Member';
            $customer->tier_badge = '#A6A6A6';
        } elseif ($customer->accumulated_points < 3500) {
            $customer->tier_name = 'GOLD VIP Member';
            $customer->tier_badge = '#D4AF37';
        } else {
            $customer->tier_name = 'PLATINUM VVIP Member';
            $customer->tier_badge = '#708090';
        }

        return view('pages.analytics.customer_detail', compact('customer', 'totalSpend', 'orderCount'));
    }

    /**
     * Menampilkan Halaman Khusus data tabel master kustomer (customer.blade.php)
     */
    public function customerAnalyticsPage()
    {
        // Ambil angka riil pesanan masuk untuk indikator badge sidebar layout master Jaced
        $orderCount = Order::whereIn('status', ['on_process','packed'])->count();

        // Ambil data nilai tiering dashboard card atas (Tetap hanya menghitung customer biasa)
        $tiers = User::where('is_admin', false)
            ->select(DB::raw("
                COUNT(CASE WHEN accumulated_points < 500 THEN 1 END) as bronze,
                COUNT(CASE WHEN accumulated_points >= 500 AND accumulated_points < 1500 THEN 1 END) as silver,
                COUNT(CASE WHEN accumulated_points >= 1500 AND accumulated_points < 3500 THEN 1 END) as gold,
                COUNT(CASE WHEN accumulated_points >= 3500 THEN 1 END) as platinum
            "))->first();

        // Ambil data Top 5 VIP Spenders untuk tabel sebelah kiri
        $spenders = Order::whereIn('status', ['completed', 'arrived'])
            ->select('user_id', DB::raw('SUM(total_price) as total_spend'))
            ->groupBy('user_id')
            ->orderBy('total_spend', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                $user = User::find($order->user_id);
                if (!$user || $user->is_admin) return null;
                $user->total_spend = $order->total_spend;

                if ($user->accumulated_points < 500) { $user->tier = 'BRONZE'; $user->badge = '#CD7F32'; }
                elseif ($user->accumulated_points < 1500) { $user->tier = 'SILVER'; $user->badge = '#A6A6A6'; }
                elseif ($user->accumulated_points < 3500) { $user->tier = 'GOLD'; $user->badge = '#D4AF37'; }
                else { $user->tier = 'PLATINUM'; $user->badge = '#708090'; }
                return $user;
            })->filter()->values();

        // Ambil data Donut Chart Region
        $regionsQuery = Order::whereIn('orders.status', ['completed', 'arrived'])
            ->join('shipping_address', 'orders.shipping_address_id', '=', 'shipping_address.id')
            ->select('shipping_address.province_name', DB::raw('COUNT(orders.id) as total_orders'))
            ->groupBy('shipping_address.province_name')
            ->orderBy('total_orders', 'desc')->take(3)->get();

        $top3Provinces = $regionsQuery->pluck('province_name')->toArray();
        $otherProvincesCount = Order::whereIn('orders.status', ['completed', 'arrived'])
            ->join('shipping_address', 'orders.shipping_address_id', '=', 'shipping_address.id')
            ->whereNotIn('shipping_address.province_name', $top3Provinces)->count();

        $regionsLabels = $regionsQuery->pluck('province_name')->toArray();
        $regionsData = $regionsQuery->pluck('total_orders')->toArray();
        if ($otherProvincesCount > 0) { $regionsLabels[] = 'Others'; $regionsData[] = $otherProvincesCount; }

        // Ambil data Trend Penjualan Gabungan
        $revenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            $revenueTrend[$month->format('M')] = ['revenue' => 0, 'order_count' => 0];
        }
        $monthlyData = Order::whereIn('status', ['completed', 'arrived'])
            ->where('created_at', '>=', \Carbon\Carbon::now()->subMonths(5)->startOfMonth())
            ->select(DB::raw("DATE_FORMAT(created_at, '%b') as month_name"), DB::raw('SUM(total_price) as total_revenue'), DB::raw('COUNT(id) as total_transactions'))
            ->groupBy('month_name')->get();

        foreach ($monthlyData as $data) {
            if (isset($revenueTrend[$data->month_name])) {
                $revenueTrend[$data->month_name]['revenue'] = (float)$data->total_revenue;
                $revenueTrend[$data->month_name]['order_count'] = (int)$data->total_transactions;
            }
        }

        // ── 🌟 SEKARANG DIUPDATE: Mengambil semua pengguna (Customer + Admin) untuk Master Table ──
        $allCustomers = User::leftJoin('orders', function($join) {
                $join->on('users.id', '=', 'orders.user_id')
                     ->whereIn('orders.status', ['completed', 'arrived']);
            })
            ->select(
                'users.id', 
                'users.name', 
                'users.email', 
                'users.avatar', 
                'users.accumulated_points', 
                'users.is_admin', // 🌟 BARU: Wajib diambil agar JavaScript di Blade bisa membedakan peran
                DB::raw('IFNULL(SUM(orders.total_price), 0) as total_spend') 
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.avatar', 'users.accumulated_points', 'users.is_admin')
            ->orderBy('users.name', 'asc')
            ->get();

        // Return langsung ke file master view table customer kamu
        return view('pages.analytics.customer', [
            'orderCount' => $orderCount,
            'tiers' => $tiers,
            'spenders' => $spenders,
            'allCustomers' => $allCustomers,
            'regionsLabels' => $regionsLabels,
            'regionsData' => $regionsData,
            'trendLabels' => array_keys($revenueTrend),
            'trendRevenue' => array_column($revenueTrend, 'revenue'),
            'trendOrders' => array_column($revenueTrend, 'order_count'),
        ]);
    }
    public function export()
    {
        // Ambil semua customer beserta total belanja
        $customers = User::where('is_admin', false)
            ->withCount('orders')
            ->withSum(['orders' => function ($q) {
                $q->whereIn('status', ['completed', 'arrived']);
            }], 'total_price')
            ->orderBy('name', 'asc')
            ->get();
 
        // Nama file CSV
        $filename = 'customer-analytics-' . now()->format('Y-m-d') . '.csv';
 
        // Header response agar browser download file secara bersih
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];
 
        // Build CSV secara streaming
        $callback = function () use ($customers) {
            $handle = fopen('php://output', 'w');
 
            // 🌟 FIX 1: Kirim BOM UTF-8 yang bersih agar Excel Windows & Mac langsung mengenali format karakter
            fwrite($handle, "\xEF\xBB\xBF");
 
            // ── Header kolom
            // 🌟 FIX 2: Gunakan pemisah koma (',') standar industri agar fputcsv bekerja natural tanpa merusak teks
            fputcsv($handle, [
                'No',
                'Name',
                'Email',
                'Membership Tier',
                'Accumulated Points',
                'Total Orders',
                'Total Spend (Rp)',
                'Registered At',
            ], ','); 
 
            // ── Data rows
            foreach ($customers as $index => $customer) {
                $pts = $customer->accumulated_points ?? 0;
 
                if ($pts < 500)      $tier = 'BRONZE';
                elseif ($pts < 1500) $tier = 'SILVER';
                elseif ($pts < 3500) $tier = 'GOLD';
                else                 $tier = 'PLATINUM';
 
                // 🌟 FIX 3: Jangan gunakan number_format() dengan koma desimal di dalam CSV 
                // karena akan merusak pembagian kolom (koma desimal dibaca sebagai kolom baru oleh Excel).
                // Biarkan berupa angka mentah agar Admin bisa menjumlahkannya langsung (Auto-Sum) di Excel.
                fputcsv($handle, [
                    $index + 1,
                    $customer->name,
                    $customer->email,
                    $tier,
                    $pts,
                    $customer->orders_count ?? 0,
                    $customer->orders_sum_total_price ?? 0,
                    $customer->created_at?->format('d/m/Y') ?? '-',
                ], ',');
            }
 
            fclose($handle);
        };
 
        return response()->stream($callback, 200, $headers);
    }
}
