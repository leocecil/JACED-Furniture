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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voucher_type_id');
            $table->unsignedBigInteger('user_id');
            $table->date('expiry_date');
            $table->date('redeemed_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('voucher_type_id')->references('id')->on('voucher_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
