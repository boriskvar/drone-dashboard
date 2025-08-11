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
        Schema::create('simulate_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drone_id')->constrained()->onDelete('cascade');

            // Координаты
            $table->decimal('latitude', 8, 4);
            $table->decimal('longitude', 8, 4);

            // Дополнительная телеметрия
            $table->decimal('altitude', 8, 2)->nullable(); // м
            $table->decimal('speed', 8, 2)->nullable();    // км/ч
            $table->decimal('heading', 5, 1)->nullable();  // °
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulate_tracks');
    }
};