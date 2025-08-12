<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\DroneApiController;  //переименовать старый DroneApiController в SimulateDroneController
// use App\Http\Controllers\Api\Simulate\SimulateDroneController;
// use App\Http\Controllers\Api\ApiTargetController;
// use App\Http\Controllers\Api\ApiFlightDataController;

// === 📡 БОЕВЫЕ МАРШРУТЫ ===

// Получить актуальные координаты всех дронов
// Route::get('/coordinates', [ApiFlightDataController::class, 'coordinates']);

// Получить трек конкретного дрона
// Route::get('/drones/{id}/track', [ApiFlightDataController::class, 'track']);

// Прием новой координаты (или группы) от дрона
// Route::post('/drones/{id}/flight_data', [ApiFlightDataController::class, 'store']);

// Назначить цель дрону
// Route::post('/target', [ApiTargetController::class, 'store']);

// === 🧪 СИМУЛИРОВАННЫЕ МАРШРУТЫ ===
use App\Http\Controllers\Api\Simulate\ApiSimulateFlightDataController;
use App\Http\Controllers\Api\Simulate\ApiSimulateTrackController;
use App\Http\Controllers\Api\Simulate\ApiSimulateTargetController;

Route::prefix('simulate-flight-data')->group(function () {
    Route::get('/', [ApiSimulateFlightDataController::class, 'coordinates']);
    Route::post('/drones/{simulateFlightData}/flight-data', [ApiSimulateFlightDataController::class, 'store']);
});

Route::prefix('simulate-tracks')->group(function () {
    Route::get('/drones', [ApiSimulateTrackController::class, 'index']);
    Route::get('/drones/{simulateTrack}/track', [ApiSimulateTrackController::class, 'track']);
    Route::get('/positions', [ApiSimulateTrackController::class, 'positions']);
});

Route::prefix('simulate-targets')->group(function () {
    Route::post('/', [ApiSimulateTargetController::class, 'store']);
});