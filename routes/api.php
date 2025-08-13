<?php

// === 🧪 СИМУЛИРОВАННЫЕ МАРШРУТЫ ===
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Simulate\ApiSimulateFlightDataController;

// use App\Http\Controllers\Api\Simulate\ApiSimulateTargetController;

Route::prefix('simulate-flight-data')->group(function () {
    // Получить последние позиции и треки по всем дронам
    Route::get('/', [ApiSimulateFlightDataController::class, 'latestPositions']);
});