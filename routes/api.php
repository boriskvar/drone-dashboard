<?php

// === 🧪 СИМУЛИРОВАННЫЕ МАРШРУТЫ ===
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Simulate\ApiSimulateFlightDataController;
// use App\Http\Controllers\Api\Simulate\ApiSimulateTrackController;
// use App\Http\Controllers\Api\Simulate\ApiSimulateTargetController;

Route::prefix('simulate-flight-data')->group(function () {
    // Получить последние позиции и треки по всем дронам
    Route::get('/', [ApiSimulateFlightDataController::class, 'latestPositions']);
});

/* Route::prefix('simulate-tracks')->group(function () {
    Route::get('/drones', [ApiSimulateTrackController::class, 'index']);
    Route::get('/drones/{simulateTrack}/track', [ApiSimulateTrackController::class, 'track']);
    Route::get('/positions', [ApiSimulateTrackController::class, 'positions']);
}); */

/* Route::prefix('simulate-targets')->group(function () {
    Route::post('/', [ApiSimulateTargetController::class, 'store']);
}); */
