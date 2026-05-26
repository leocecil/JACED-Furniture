<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $currentPoints     = $user->current_points ?? 0;
        $accumulatedPoints = $user->accumulated_points ?? 0;

        // Stage berdasarkan total spending order paid
        $stageModel = DB::table('stages')
                ->where('min_points_accumulative', '<=', $accumulatedPoints)
                ->orderBy('min_points_accumulative', 'desc')
                ->first();
        $stage = $stageModel ? $stageModel->name : 'Bronze';

        // Point history dari DB
        $pointHistoryItems = DB::table('point_histories')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'source' => match($item->source) {
                    'purchase'       => 'Purchase Reward',
                    'redeem_voucher' => 'Voucher Redeemed',
                    'redeem'         => 'Points Redeemed',
                    default          => ucfirst($item->source),
                },
                'date'   => \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                'points' => $item->points,
                'type'   => $item->type,
            ]);

        // Redeem goals dari voucher_types
        $redeemGoals = DB::table('voucher_types')->limit(2)->get();

        $stages = Stage::orderBy('min_points_accumulative', 'asc')->get();

        return view('profile.reward-center.reward', compact(
            'stages',
            'stage',
            'currentPoints',
            'accumulatedPoints',
            'pointHistoryItems',
            'redeemGoals'
        ));
    }

    /**
     * Fungsi ketika user klik tombol "Redeem Now" di kartu hadiah
     */
    public function redeem(Request $request)
    {
        $user          = Auth::user();
        $voucherTypeId = $request->input('voucher_type_id');

        $voucherType = DB::table('voucher_types')->find($voucherTypeId);

        if (!$voucherType) {
            return redirect()->back()->with('error', 'Voucher tidak ditemukan.');
        }

        if ($user->current_points < $voucherType->point_cost) {
            return redirect()->back()->with('error', 'Poin kamu tidak mencukupi.');
        }

        // Kurangi poin, accumulated tidak berkurang
        $user->decrement('current_points', $voucherType->point_cost);

        // Buat voucher untuk user
        DB::table('vouchers')->insert([
            'voucher_type_id' => $voucherType->id,
            'user_id'         => $user->id,
            'expiry_date'     => now()->addDays(30),
            'is_active'       => true,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Catat ke point_histories
        DB::table('point_histories')->insert([
            'user_id'    => $user->id,
            'points'     => -$voucherType->point_cost, // negatif karena dipakai
            'type'       => 'redeemed',
            'source'     => 'redeem_voucher',
            'order_id'   => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', "Berhasil menukar {$voucherType->name}! Cek My Vouchers.");
    }


    public function redeemPage()
    {
        $user = Auth::user();
        $currentPoints = $user->current_points ?? 0;
        $redeemGoals = DB::table('voucher_types')->get();

        return view('profile.reward-center.redeem-point', compact(
            'currentPoints', 'redeemGoals'
        ));
    }

    public function pointHistory()
    {
        $user = Auth::user();
        $currentPoints = $user->current_points ?? 0;

        $histories = DB::table('point_histories')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total earned dan redeemed tahun ini
        $currentYear = now()->year;
        $earnedThisYear = DB::table('point_histories')
            ->where('user_id', $user->id)
            ->where('type', 'earned')
            ->whereYear('created_at', $currentYear)
            ->sum('points');

        // Ambil tahun-tahun yang ada di history untuk dropdown
        $availableYears = DB::table('point_histories')
            ->where('user_id', $user->id)
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('profile.reward-center.point-history', compact(
            'currentPoints', 'histories',
            'earnedThisYear', 'availableYears', 'currentYear'
        ));
    }

    public function useVoucher(Request $request)
    {
        $voucherId = $request->input('voucher_id');
        
        // Validasi voucher milik user dan masih aktif
        $voucher = DB::table('vouchers')
            ->where('id', $voucherId)
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->whereNull('redeemed_at')
            ->first();

        if (!$voucher) {
            return redirect()->back()->with('error', 'Voucher tidak valid.');
        }

        // Simpan ke session
        session(['pending_voucher_id' => $voucherId]);

        return redirect()->route('checkout.index');
    }

    public function voucherPage()
    {
        $user = Auth::user();

        $activeVouchers = DB::table('vouchers')
            ->join('voucher_types', 'vouchers.voucher_type_id', '=', 'voucher_types.id')
            ->where('vouchers.user_id', $user->id)
            ->where('vouchers.is_active', true)
            ->whereNull('vouchers.redeemed_at')
            ->where('vouchers.expiry_date', '>', now())
            ->select('vouchers.*', 'voucher_types.name', 'voucher_types.used_for', 'voucher_types.discount_percentage', 'voucher_types.max_discount')
            ->get();

        $historyVouchers = DB::table('vouchers')
            ->join('voucher_types', 'vouchers.voucher_type_id', '=', 'voucher_types.id')
            ->where('vouchers.user_id', $user->id)
            ->where(function($q) {
                $q->where('vouchers.is_active', false)
                ->orWhereNotNull('vouchers.redeemed_at')
                ->orWhere('vouchers.expiry_date', '<=', now());
            })
            ->select('vouchers.*', 'voucher_types.name', 'voucher_types.used_for', 'voucher_types.discount_percentage', 'voucher_types.max_discount')
            ->get();

        return view('profile.reward-center.voucher', compact('activeVouchers', 'historyVouchers'));
    }

}
