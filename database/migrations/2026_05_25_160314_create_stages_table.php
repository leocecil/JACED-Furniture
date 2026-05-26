<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->integer('min_points_accumulative')->default(0); 
            $table->integer('discount_percentage')->default(0); 
            $table->unsignedBigInteger('free_shipping_min_spending')->nullable(); 
            $table->text('perks_description')->nullable();
            $table->json('additional_perks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};
