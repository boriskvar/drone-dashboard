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
        Schema::table('detections', function (Blueprint $table) {
            $table->decimal('x1', 8, 4)->change();
            $table->decimal('y1', 8, 4)->change();
            $table->decimal('x2', 8, 4)->change();
            $table->decimal('y2', 8, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detections', function (Blueprint $table) {
            //
        });
    }
};