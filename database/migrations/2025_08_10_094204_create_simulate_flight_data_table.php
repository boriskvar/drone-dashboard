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
        Schema::create('simulate_flight_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drone_id');
            $table->decimal('latitude', 8, 4);
            $table->decimal('longitude', 8, 4);
            $table->float('altitude')->nullable();
            $table->float('speed')->nullable();
            $table->float('heading')->nullable();
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();

            $table->foreign('drone_id')->references('id')->on('drones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulate_flight_data');
    }
};