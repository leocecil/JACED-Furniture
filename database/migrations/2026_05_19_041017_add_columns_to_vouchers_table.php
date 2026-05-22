<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->unsignedBigInteger('discount_percentage')->nullable()->after('expiry_date');
            $table->unsignedBigInteger('min_purchase')->nullable()->after('discount_percentage');
            $table->unsignedBigInteger('max_discount')->nullable()->after('min_purchase');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['discount_percentage', 'min_purchase', 'max_discount']);
        });
    }
};
