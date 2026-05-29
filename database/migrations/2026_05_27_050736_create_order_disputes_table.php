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
        Schema::create('order_disputes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('reason'); // 'damaged', 'wrong_item', etc.
            $table->text('description')->nullable(); // Customer's detailed explanation
            $table->string('status'); // 'open', 'negotiating', 'resolved', 'rejected'
            $table->text('admin_note')->nullable(); // Admin's note on the dispute      
            $table->string('resolution_type')->nullable(); // 'refund', 'exchange'
            $table->string('photo_path')->nullable();

            // For handling the refund
            $table->decimal('refund_amount', 8, 2)->nullable();
            
            // For handling the physical return of the bad item
            $table->string('return_tracking_number')->nullable();
            $table->timestamp('return_received_at')->nullable();
            
            // For handling the new replacement item
            $table->string('replacement_tracking_number')->nullable();
            $table->timestamp('replacement_shipped_at')->nullable();
            $table->timestamp('replacement_arrived_at')->nullable();
            
            $table->timestamps();
            $table->timestamp('resolved_at')->nullable();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_disputes');
    }
};
