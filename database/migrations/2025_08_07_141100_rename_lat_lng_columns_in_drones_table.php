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
        Schema::table('drones', function (Blueprint $table) {
            $table->renameColumn('lat', 'latitude');
            $table->renameColumn('lng', 'longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drones', function (Blueprint $table) {
            $table->renameColumn('latitude', 'lat');
            $table->renameColumn('longitude', 'lng');
        });
    }
};