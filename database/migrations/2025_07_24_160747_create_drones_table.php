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
        Schema::create('drones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['active', 'offline', 'idle', 'in_mission', 'maintenance'])->default('offline');
            $table->decimal('lat', 10, 6);
            $table->decimal('lng', 10, 6);
            $table->string('model')->nullable(); // Модель дрона
            $table->string('serial_number')->nullable(); // Не Уникальный пока серийный номер
            $table->string('manufacturer')->nullable(); // Производитель дрона
            $table->date('manufacture_date')->nullable(); // Дата производства
            $table->string('firmware_version')->nullable(); // Версия прошивки
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drones');
    }
};