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
    /**
     * Menampilkan halaman Customer Analytics (Bebas Error & Sinkron dengan DB).
     */
    public function index()
    {
        // 0. Ambil angka riil pesanan masuk berstatus 'pending' untuk indikator badge sidebar
        $orderCount = Order::where('status', 'pending')->count();

        // ════════════════════════════════════════════════════════════════════
        // 1. DISTRIBUSI MEMBER PER TIER (Dihitung dari accumulated_points)
        // Batas poin: Bronze (<500), Silver (500-1499), Gold (1500-3499), Platinum (>=3500)
        // ════════════════════════════════════════════════════════════════════
        $tiers = User::where('is_admin', false)
            ->select(DB::raw("
                COUNT(CASE WHEN accumulated_points < 500 THEN 1 END) as bronze,
                COUNT(CASE WHEN accumulated_points >= 500 AND accumulated_points < 1500 THEN 1 END) as silver,
                COUNT(CASE WHEN accumulated_points >= 1500 AND accumulated_points < 3500 THEN 1 END) as gold,
                COUNT(CASE WHEN accumulated_points >= 3500 THEN 1 END) as platinum
            "))->first();


        // ════════════════════════════════════════════════════════════════════
        // 2. TOP 5 VIP SPENDERS (Menghitung total belanja pesanan sukses)
        // ════════════════════════════════════════════════════════════════════
        $spenders = Order::whereIn('status', ['completed', 'arrived'])
            ->select('user_id', DB::raw('SUM(total_price) as total_spend'))
            ->groupBy('user_id')
            ->orderBy('total_spend', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                // Ambil data user terkait secara aman menggunakan Lazy Loading / Find
                $user = User::find($order->user_id);
                
                // Jika user tidak ditemukan atau ternyata admin, kita skip atau beri data default
                if (!$user || $user->is_admin) {
                    return null;
                }

                // Masukkan properti spend ke dalam object user
                $user->total_spend = $order->total_spend;

                // Tentukan tingkatan lencana (tier badge) berdasarkan accumulated_points user
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
            ->filter() // Membersihkan data null jika ada admin yang tidak sengaja masuk daftar
            ->values();


        // ════════════════════════════════════════════════════════════════════
        // 3. PROVINSI TERBANYAK BERBELANJA (Sinkronisasi dengan tabel tunggal: shipping_address)
        // ════════════════════════════════════════════════════════════════════
        $regionsQuery = Order::whereIn('orders.status', ['completed', 'arrived'])
            ->join('shipping_address', 'orders.shipping_address_id', '=', 'shipping_address.id')
            ->select('shipping_address.province_name', DB::raw('COUNT(orders.id) as total_orders'))
            ->groupBy('shipping_address.province_name')
            ->orderBy('total_orders', 'desc')
            ->take(3)
            ->get();

        $top3Provinces = $regionsQuery->pluck('province_name')->toArray();
        
        // Ambil akumulasi data order di luar top 3 untuk boks kategori "Lainnya"
        $otherProvincesCount = Order::whereIn('orders.status', ['completed', 'arrived'])
            ->join('shipping_address', 'orders.shipping_address_id', '=', 'shipping_address.id')
            ->whereNotIn('shipping_address.province_name', $top3Provinces)
            ->count();

        $regionsLabels = $regionsQuery->pluck('province_name')->toArray();
        $regionsData = $regionsQuery->pluck('total_orders')->toArray();

        if ($otherProvincesCount > 0) {
            $regionsLabels[] = 'Lainnya';
            $regionsData[] = $otherProvincesCount;
        }


        // ════════════════════════════════════════════════════════════════════
        // 4. MONTHLY REVENUE & TRANSACTIONS TREND (Garis Waktu 6 Bulan Terakhir)
        // ════════════════════════════════════════════════════════════════════
        $revenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenueTrend[$month->format('M')] = [
                'revenue' => 0,
                'order_count' => 0
            ];
        }

        // PERBAIKAN: Memperbaiki tanda kurung penutup DB::raw dan merapikan rantai method Eloquent
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

        // Kirim seluruh variabel bersih ke file blade index
        return view('pages.analytics.index', [
            'orderCount' => $orderCount,
            'tiers' => $tiers,
            'spenders' => $spenders,
            'regionsLabels' => $regionsLabels,
            'regionsData' => $regionsData,
            'trendLabels' => array_keys($revenueTrend),
            'trendRevenue' => array_column($revenueTrend, 'revenue'),
            'trendOrders' => array_column($revenueTrend, 'order_count'),
        ]);
    }
}