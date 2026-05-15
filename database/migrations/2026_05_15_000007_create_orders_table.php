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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->unsignedBigInteger('shipping_address_id');
            $table->decimal('delivery_fee', 10, 2);
            $table->decimal('service_tax', 10, 2);
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('status');
            $table->timestamps();
            $table->timestamp('packed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('set null');
            $table->foreign('shipping_address_id')->references('id')->on('shipping_address')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
