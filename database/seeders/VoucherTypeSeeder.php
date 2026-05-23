<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoucherTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Point cost formula: max_discount / 250
        // Product vouchers: VP + max_discount integer (e.g. VP8001, VP8002, ...)
        // Delivery vouchers: VD + max_discount integer (e.g. VD4501, VD4502, ...)
        // Suffix _01, _02 etc. for multiple instances of the same type

        $voucherTypes = [
            // --- PRODUCT DISCOUNT VOUCHERS ---
            [
                'id'                  => 'VP8001',
                'name'                => 'Hemat 10% - Produk s.d. Rp80.000',
                'description'         => 'Dapatkan diskon 10% untuk pembelian produk dengan maksimal potongan harga sebesar Rp80.000. Voucher ini dapat digunakan pada semua kategori produk yang tersedia.',
                'used_for'            => 'product',
                'point_cost'          => 80000 / 250, // 320
                'discount_percentage' => 10,
                'max_discount'        => 80000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VP8002',
                'name'                => 'Hemat 10% - Produk s.d. Rp80.000 (2)',
                'description'         => 'Dapatkan diskon 10% untuk pembelian produk dengan maksimal potongan harga sebesar Rp80.000. Voucher ini dapat digunakan pada semua kategori produk yang tersedia.',
                'used_for'            => 'product',
                'point_cost'          => 80000 / 250,
                'discount_percentage' => 10,
                'max_discount'        => 80000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VP8003',
                'name'                => 'Hemat 10% - Produk s.d. Rp80.000 (3)',
                'description'         => 'Dapatkan diskon 10% untuk pembelian produk dengan maksimal potongan harga sebesar Rp80.000. Voucher ini dapat digunakan pada semua kategori produk yang tersedia.',
                'used_for'            => 'product',
                'point_cost'          => 80000 / 250,
                'discount_percentage' => 10,
                'max_discount'        => 80000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VP10001',
                'name'                => 'Diskon 12% - Produk s.d. Rp100.000',
                'description'         => 'Nikmati diskon 12% untuk setiap pembelian produk dengan maksimal potongan harga sebesar Rp100.000. Berlaku untuk semua produk pilihan di toko kami.',
                'used_for'            => 'product',
                'point_cost'          => 100000 / 250, // 400
                'discount_percentage' => 12,
                'max_discount'        => 100000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VP10002',
                'name'                => 'Diskon 12% - Produk s.d. Rp100.000 (2)',
                'description'         => 'Nikmati diskon 12% untuk setiap pembelian produk dengan maksimal potongan harga sebesar Rp100.000. Berlaku untuk semua produk pilihan di toko kami.',
                'used_for'            => 'product',
                'point_cost'          => 100000 / 250,
                'discount_percentage' => 12,
                'max_discount'        => 100000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VP10003',
                'name'                => 'Diskon 12% - Produk s.d. Rp100.000 (3)',
                'description'         => 'Nikmati diskon 12% untuk setiap pembelian produk dengan maksimal potongan harga sebesar Rp100.000. Berlaku untuk semua produk pilihan di toko kami.',
                'used_for'            => 'product',
                'point_cost'          => 100000 / 250,
                'discount_percentage' => 12,
                'max_discount'        => 100000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VP15001',
                'name'                => 'Diskon 15% - Produk s.d. Rp150.000',
                'description'         => 'Hemat lebih banyak dengan diskon 15% untuk pembelian produk, dengan maksimal potongan harga sebesar Rp150.000. Gunakan voucher ini untuk mendapatkan penghematan terbaik.',
                'used_for'            => 'product',
                'point_cost'          => 150000 / 250, // 600
                'discount_percentage' => 15,
                'max_discount'        => 150000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VP15002',
                'name'                => 'Diskon 15% - Produk s.d. Rp150.000 (2)',
                'description'         => 'Hemat lebih banyak dengan diskon 15% untuk pembelian produk, dengan maksimal potongan harga sebesar Rp150.000. Gunakan voucher ini untuk mendapatkan penghematan terbaik.',
                'used_for'            => 'product',
                'point_cost'          => 150000 / 250,
                'discount_percentage' => 15,
                'max_discount'        => 150000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VP15003',
                'name'                => 'Diskon 15% - Produk s.d. Rp150.000 (3)',
                'description'         => 'Hemat lebih banyak dengan diskon 15% untuk pembelian produk, dengan maksimal potongan harga sebesar Rp150.000. Gunakan voucher ini untuk mendapatkan penghematan terbaik.',
                'used_for'            => 'product',
                'point_cost'          => 150000 / 250,
                'discount_percentage' => 15,
                'max_discount'        => 150000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VP20001',
                'name'                => 'Diskon 20% - Produk s.d. Rp200.000',
                'description'         => 'Voucher eksklusif dengan diskon 20% untuk pembelian produk, dengan maksimal potongan harga sebesar Rp200.000. Jangan lewatkan kesempatan berhemat lebih besar bersama kami.',
                'used_for'            => 'product',
                'point_cost'          => 200000 / 250, // 800
                'discount_percentage' => 20,
                'max_discount'        => 200000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VP20002',
                'name'                => 'Diskon 20% - Produk s.d. Rp200.000 (2)',
                'description'         => 'Voucher eksklusif dengan diskon 20% untuk pembelian produk, dengan maksimal potongan harga sebesar Rp200.000. Jangan lewatkan kesempatan berhemat lebih besar bersama kami.',
                'used_for'            => 'product',
                'point_cost'          => 200000 / 250,
                'discount_percentage' => 20,
                'max_discount'        => 200000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VP20003',
                'name'                => 'Diskon 20% - Produk s.d. Rp200.000 (3)',
                'description'         => 'Voucher eksklusif dengan diskon 20% untuk pembelian produk, dengan maksimal potongan harga sebesar Rp200.000. Jangan lewatkan kesempatan berhemat lebih besar bersama kami.',
                'used_for'            => 'product',
                'point_cost'          => 200000 / 250,
                'discount_percentage' => 20,
                'max_discount'        => 200000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],

            // --- DELIVERY FEE VOUCHERS ---
            [
                'id'                  => 'VD4501',
                'name'                => 'Gratis Ongkir s.d. Rp45.000',
                'description'         => 'Nikmati gratis ongkos kirim hingga Rp45.000 untuk setiap pembelian. Voucher ini berlaku untuk semua jenis pengiriman yang tersedia di toko kami.',
                'used_for'            => 'delivery',
                'point_cost'          => 45000 / 250, // 180
                'discount_percentage' => 100,
                'max_discount'        => 45000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VD4502',
                'name'                => 'Gratis Ongkir s.d. Rp45.000 (2)',
                'description'         => 'Nikmati gratis ongkos kirim hingga Rp45.000 untuk setiap pembelian. Voucher ini berlaku untuk semua jenis pengiriman yang tersedia di toko kami.',
                'used_for'            => 'delivery',
                'point_cost'          => 45000 / 250,
                'discount_percentage' => 100,
                'max_discount'        => 45000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VD4503',
                'name'                => 'Gratis Ongkir s.d. Rp45.000 (3)',
                'description'         => 'Nikmati gratis ongkos kirim hingga Rp45.000 untuk setiap pembelian. Voucher ini berlaku untuk semua jenis pengiriman yang tersedia di toko kami.',
                'used_for'            => 'delivery',
                'point_cost'          => 45000 / 250,
                'discount_percentage' => 100,
                'max_discount'        => 45000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VD4504',
                'name'                => 'Gratis Ongkir s.d. Rp45.000 (4)',
                'description'         => 'Nikmati gratis ongkos kirim hingga Rp45.000 untuk setiap pembelian. Voucher ini berlaku untuk semua jenis pengiriman yang tersedia di toko kami.',
                'used_for'            => 'delivery',
                'point_cost'          => 45000 / 250,
                'discount_percentage' => 100,
                'max_discount'        => 45000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 'VD4505',
                'name'                => 'Gratis Ongkir s.d. Rp45.000 (5)',
                'description'         => 'Nikmati gratis ongkos kirim hingga Rp45.000 untuk setiap pembelian. Voucher ini berlaku untuk semua jenis pengiriman yang tersedia di toko kami.',
                'used_for'            => 'delivery',
                'point_cost'          => 45000 / 250,
                'discount_percentage' => 100,
                'max_discount'        => 45000.00,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ];

        DB::table('voucher_types')->insert($voucherTypes);
    }
}