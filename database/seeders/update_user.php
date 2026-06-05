<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class update_user extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $userTotals = DB::table('orders')
        ->where('status', 'arrived')
        ->select('user_id', DB::raw('SUM(total_price) as total_spending'))
        ->groupBy('user_id')
        ->get();

        foreach ($userTotals as $userTotal) {
            DB::table('users')
                ->where('id', $userTotal->user_id)
                ->update([
                    'accumulated_points' => (int) floor($userTotal->total_spending / 10000),
                    'current_points' => (int) floor($userTotal->total_spending / 10000),
                ]);
        }
    }
}
