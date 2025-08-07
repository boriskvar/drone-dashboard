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
        Schema::create('flight_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drone_id');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->float('altitude')->nullable();    // высота
            $table->float('speed')->nullable();       // скорость
            $table->float('heading')->nullable();     // курс (0–360°)
            $table->timestamp('recorded_at')->useCurrent(); // время записи в БД
            $table->timestamps();

            // Внешний ключ (если есть таблица drones)
            $table->foreign('drone_id')->references('id')->on('drones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_data');
    }
};