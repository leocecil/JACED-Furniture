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
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('village_id'); 
            
            $table->string('postal_code', 5);
            $table->timestamps();

            $table->foreign('village_id')
                ->references('id')
                ->on(config('laravolt.indonesia.table_prefix') . 'villages')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postal_codes');
    }
};