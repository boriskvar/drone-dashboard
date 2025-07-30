<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DroneApiController;
use App\Http\Controllers\Api\ApiTelemetryController;


// Route::get('/coordinates', [DroneApiController::class, 'getLatest']);

/* Route::get('/coordinates', function () {
    return response()->json([
        ['id' => 1, 'lat' => 50.4501, 'lng' => 30.5234],
        ['id' => 2, 'lat' => 50.4550, 'lng' => 30.5200],
        ['id' => 3, 'lat' => 50.4480, 'lng' => 30.5250],
    ]);
}); */

// Отдаёт координаты всех дронов (для карты)

// Для боевого трека (из telemetries)
Route::get('/coordinates', [ApiTelemetryController::class, 'coordinates']);

// Для тестового трека (из drone_positions)
Route::get('/positions', [DroneApiController::class, 'index']);

// Защищённая группа API-маршрутов для отправки телеметрии
// Route::middleware('auth:sanctum')->prefix('drones')->group(function () {
Route::prefix('drones')->group(function () {
    Route::post('/', action: [DroneApiController::class, 'list']);  // список всех дронов (если нужно)
    Route::post('/telemetry', [DroneApiController::class, 'store']); // например, сохранение координат

    // Получить все координаты дрона (трек)
    Route::get('{id}/track', [ApiTelemetryController::class, 'track']);

    // Приём телеметрии (координаты, высота и т.п.)
    Route::post('{id}/telemetry', [ApiTelemetryController::class, 'store']);
});

/* Route::get('/test', function () {
    return response()->json(['message' => 'API работает']);
}); */