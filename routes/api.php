<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\DroneApiController;  //переименовать старый DroneApiController в SimulateDroneController
use App\Http\Controllers\Api\Simulate\SimulateDroneController;
use App\Http\Controllers\Api\ApiTargetController;
use App\Http\Controllers\Api\ApiFlightDataController;

// === 📡 БОЕВЫЕ МАРШРУТЫ ===

// Получить актуальные координаты всех дронов
Route::get('/coordinates', [ApiFlightDataController::class, 'coordinates']);

// Получить трек конкретного дрона
Route::get('/drones/{id}/track', [ApiFlightDataController::class, 'track']);

// Прием новой координаты (или группы) от дрона
Route::post('/drones/{id}/flight_data', [ApiFlightDataController::class, 'store']);

// Назначить цель дрону
Route::post('/target', [ApiTargetController::class, 'store']);

// === 🧪 СИМУЛИРОВАННЫЕ МАРШРУТЫ ===

Route::prefix('simulated')->group(function () {
    // Тест: список позиций из фейковой таблицы
    Route::get('/positions', [SimulateDroneController::class, 'index']);

    // Тест: ручная отправка телеметрии
    Route::post('/drones', [SimulateDroneController::class, 'list']);
    Route::post('/drones/flight_data', [SimulateDroneController::class, 'store']);
});