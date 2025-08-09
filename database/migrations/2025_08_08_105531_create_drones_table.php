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
            $table->decimal('latitude', 8, 4)->nullable();
            $table->decimal('longitude', 8, 4)->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable()->unique();
            $table->string('manufacturer')->nullable();
            $table->date('manufacture_date')->nullable();
            $table->string('firmware_version')->nullable();
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