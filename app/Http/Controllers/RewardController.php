<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data poin dari user yang sedang login
        $currentPoints = $user->current_points ?? 0;
        $accumulatedPoints = $user->accumulated_points ?? 0;

        // Tentukan Stage secara dinamis berdasarkan Akumulasi Poin
        $stage = 'Bronze';
        if ($accumulatedPoints >= 5000) {
            $stage = 'Gold';
        } elseif ($accumulatedPoints >= 2500) {
            $stage = 'Silver';
        }

        // Dummy data untuk History (Nanti bisa kamu ganti dari DB tabel point_histories jika ada)
        $pointHistoryItems = [
            ['points' => '200 Points', 'source' => 'Workshop Attendance', 'date' => '15 May 2026', 'type' => 'earned'],
            ['points' => '450 Points', 'source' => 'Redeem Candle', 'date' => '14 May 2026', 'type' => 'redeemed'],
        ];

        // Daftar hadiah yang bisa ditukarkan
        $redeemGoals = [
            ['id' => 1, 'name' => 'Artisan Scented Candle', 'image' => 'https://images.unsplash.com/photo-1603905600016-2f0a09924a49?w=400&h=300&fit=crop', 'goal' => 450],
            ['id' => 2, 'name' => 'Asymmetric Vase', 'image' => 'https://images.unsplash.com/photo-1612196808214-b8e1d6145a8c?w=400&h=300&fit=crop', 'goal' => 850],
            ['id' => 3, 'name' => 'Handwoven Wool Throw', 'image' => 'https://images.unsplash.com/photo-1580301762395-21ce84d00bc6?w=400&h=300&fit=crop', 'goal' => 2000],
        ];

        // Kirim semua variabel ke view blade kamu
        return view('loyalty', compact('currentPoints', 'accumulatedPoints', 'stage', 'pointHistoryItems', 'redeemGoals'));
    }

    /**
     * Fungsi ketika user klik tombol "Redeem Now" di kartu hadiah
     */
    public function redeem(Request $request)
    {
        $user = Auth::user();
        
        // Contoh harga hadiah fix berdasarkan input / request dari form penukaran
        $rewardCost = $request->input('points_cost'); 
        $rewardName = $request->input('reward_name');

        // Validasi: Apakah poin yang bisa digunakan cukup?
        if ($user->current_points < $rewardCost) {
            return redirect()->back()->with('error', 'Poin kamu tidak mencukupi untuk menukar hadiah ini.');
        }

        // PROSES UTAMA: Kurangi poin belanja user
        // Poin akumulasi seumur hidup (accumulated_points) SENGAJA TIDAK DIKURANGI agar level/stage tidak turun
        $user->decrement('current_points', $rewardCost);

        // Tambahan Logika Kelompokmu: 
        // 1. Simpan hadiah ke tabel kupon/voucher user
        // 2. Catat transaksi ke tabel riwayat poin

        return redirect()->back()->with('success', "Berhasil menukarkan {$rewardName}! Silakan cek menu My Vouchers.");
    }
}
