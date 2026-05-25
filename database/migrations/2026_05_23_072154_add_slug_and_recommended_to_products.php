<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // public function up(): void
    // {
    //     Schema::table('products', function (Blueprint $table) {
    //         $table->string('slug')->nullable()->unique()->after('name');
    //         $table->boolean('is_recommended')->default(false)->after('stock');
    //         $table->decimal('old_price', 10, 2)->nullable()->after('price');
    //         $table->string('badge')->nullable()->after('label');
    //     });
    // }

    // public function down(): void
    // {
    //     Schema::table('products', function (Blueprint $table) {
    //         $table->dropColumn(['slug', 'is_recommended', 'old_price', 'badge']);
    //     });
    // }
};