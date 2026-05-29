<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('indonesia_postal_codes', function (Blueprint $table) {
            $table->id();
            $table->char('village_code', 10)->index(); // Untuk kita hubungkan ke code di indonesia_villages
            $table->string('postal_code', 5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postal_code');
    }
};
