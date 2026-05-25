<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoucherTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Point cost formula: max_discount / 250
        // IDs are unique (VP8001, VP8002 ...) but names are identical within the same tier
        // The UI groups them by name and shows quantity (e.g. "x3 available")

        $voucherTypes = [

            // ── PRODUCT DISCOUNT: 10% up to Rp80.000 (x3) ─────────────
            ['id' => 'VP8001',  'name' => 'Save 10% - Product up to Rp80,000',   'description' => 'Get a 10% discount on product purchases with a maximum deduction of Rp80,000. Valid for all available product categories.',               'used_for' => 'product',  'point_cost' => 320, 'discount_percentage' => 10,  'max_discount' => 80000.00,  'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VP8002',  'name' => 'Save 10% - Product up to Rp80,000',   'description' => 'Get a 10% discount on product purchases with a maximum deduction of Rp80,000. Valid for all available product categories.',               'used_for' => 'product',  'point_cost' => 320, 'discount_percentage' => 10,  'max_discount' => 80000.00,  'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VP8003',  'name' => 'Save 10% - Product up to Rp80,000',   'description' => 'Get a 10% discount on product purchases with a maximum deduction of Rp80,000. Valid for all available product categories.',               'used_for' => 'product',  'point_cost' => 320, 'discount_percentage' => 10,  'max_discount' => 80000.00,  'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // ── PRODUCT DISCOUNT: 12% up to Rp100.000 (x3) ────────────
            ['id' => 'VP10001', 'name' => 'Discount 12% - Product up to Rp100,000', 'description' => 'Enjoy a 12% discount on every product purchase with a maximum deduction of Rp100,000. Valid for all selected products in our store.',       'used_for' => 'product',  'point_cost' => 400, 'discount_percentage' => 12,  'max_discount' => 100000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VP10002', 'name' => 'Discount 12% - Product up to Rp100,000', 'description' => 'Enjoy a 12% discount on every product purchase with a maximum deduction of Rp100,000. Valid for all selected products in our store.',       'used_for' => 'product',  'point_cost' => 400, 'discount_percentage' => 12,  'max_discount' => 100000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VP10003', 'name' => 'Discount 12% - Product up to Rp100,000', 'description' => 'Enjoy a 12% discount on every product purchase with a maximum deduction of Rp100,000. Valid for all selected products in our store.',       'used_for' => 'product',  'point_cost' => 400, 'discount_percentage' => 12,  'max_discount' => 100000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // ── PRODUCT DISCOUNT: 15% up to Rp150.000 (x3) ────────────
            ['id' => 'VP15001', 'name' => 'Discount 15% - Product up to Rp150,000', 'description' => 'Save more with a 15% discount on product purchases, with a maximum deduction of Rp150,000. Use this voucher for the best savings.', 'used_for' => 'product', 'point_cost' => 600, 'discount_percentage' => 15, 'max_discount' => 150000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VP15002', 'name' => 'Discount 15% - Product up to Rp150,000', 'description' => 'Save more with a 15% discount on product purchases, with a maximum deduction of Rp150,000. Use this voucher for the best savings.', 'used_for' => 'product', 'point_cost' => 600, 'discount_percentage' => 15, 'max_discount' => 150000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VP15003', 'name' => 'Discount 15% - Product up to Rp150,000', 'description' => 'Save more with a 15% discount on product purchases, with a maximum deduction of Rp150,000. Use this voucher for the best savings.', 'used_for' => 'product', 'point_cost' => 600, 'discount_percentage' => 15, 'max_discount' => 150000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // ── PRODUCT DISCOUNT: 20% up to Rp200.000 (x3) ────────────
            ['id' => 'VP20001', 'name' => 'Discount 20% - Product up to Rp200,000', 'description' => 'Exclusive voucher with a 20% discount on product purchases, with a maximum deduction of Rp200,000. Don\'t miss the chance to save even more.', 'used_for' => 'product', 'point_cost' => 800, 'discount_percentage' => 20, 'max_discount' => 200000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VP20002', 'name' => 'Discount 20% - Product up to Rp200,000', 'description' => 'Exclusive voucher with a 20% discount on product purchases, with a maximum deduction of Rp200,000. Don\'t miss the chance to save even more.', 'used_for' => 'product', 'point_cost' => 800, 'discount_percentage' => 20, 'max_discount' => 200000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VP20003', 'name' => 'Discount 20% - Product up to Rp200,000', 'description' => 'Exclusive voucher with a 20% discount on product purchases, with a maximum deduction of Rp200,000. Don\'t miss the chance to save even more.', 'used_for' => 'product', 'point_cost' => 800, 'discount_percentage' => 20, 'max_discount' => 200000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // ── DELIVERY FEE: 100% up to Rp45.000 (x5) ────────────────
            ['id' => 'VD4501', 'name' => 'Free Shipping up to Rp45,000', 'description' => 'Enjoy free shipping up to Rp45,000 on every purchase. Valid for all shipping methods available in our store.', 'used_for' => 'delivery', 'point_cost' => 180, 'discount_percentage' => 100, 'max_discount' => 45000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VD4502', 'name' => 'Free Shipping up to Rp45,000', 'description' => 'Enjoy free shipping up to Rp45,000 on every purchase. Valid for all shipping methods available in our store.', 'used_for' => 'delivery', 'point_cost' => 180, 'discount_percentage' => 100, 'max_discount' => 45000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VD4503', 'name' => 'Free Shipping up to Rp45,000', 'description' => 'Enjoy free shipping up to Rp45,000 on every purchase. Valid for all shipping methods available in our store.', 'used_for' => 'delivery', 'point_cost' => 180, 'discount_percentage' => 100, 'max_discount' => 45000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VD4504', 'name' => 'Free Shipping up to Rp45,000', 'description' => 'Enjoy free shipping up to Rp45,000 on every purchase. Valid for all shipping methods available in our store.', 'used_for' => 'delivery', 'point_cost' => 180, 'discount_percentage' => 100, 'max_discount' => 45000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'VD4505', 'name' => 'Free Shipping up to Rp45,000', 'description' => 'Enjoy free shipping up to Rp45,000 on every purchase. Valid for all shipping methods available in our store.', 'used_for' => 'delivery', 'point_cost' => 180, 'discount_percentage' => 100, 'max_discount' => 45000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('voucher_types')->insert($voucherTypes);
    }
}