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
use App\Http\Controllers\Api\Simulate\SimulateDroneController;
use App\Http\Controllers\Api\Simulate\SimulateFlightDataController;
use App\Http\Controllers\Api\Simulate\SimulateTargetController;

Route::prefix('simulate')->group(function () {
    // 📌 Получить актуальные координаты всех дронов (симуляция)
    Route::get('/coordinates', [SimulateFlightDataController::class, 'coordinates']);

    // 📌 Получить трек дрона (симуляция)
    Route::get('/drones/{id}/track', [SimulateFlightDataController::class, 'track']);

    // 📌 Принять координаты от дрона (симуляция)
    Route::post('/drones/{id}/flight_data', [SimulateFlightDataController::class, 'store']);

    // 📌 Назначить цель (симуляция)
    Route::post('/target', [SimulateTargetController::class, 'store']);

    // 📌 (Необязательно) Тест: список всех позиций
    Route::get('/positions', [SimulateDroneController::class, 'index']);

    // 📌 (Необязательно) Тест: список дронов
    Route::post('/drones', [SimulateDroneController::class, 'list']);
});