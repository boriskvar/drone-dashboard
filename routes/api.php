<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DroneApiController;


// Route::get('/coordinates', [DroneApiController::class, 'getLatest']);

/* Route::get('/coordinates', function () {
    return response()->json([
        ['id' => 1, 'lat' => 50.4501, 'lng' => 30.5234],
        ['id' => 2, 'lat' => 50.4550, 'lng' => 30.5200],
        ['id' => 3, 'lat' => 50.4480, 'lng' => 30.5250],
    ]);
}); */

Route::get('/coordinates', [DroneApiController::class, 'index']);

Route::middleware('auth:sanctum')->prefix('drones')->group(function () {
    Route::post('/', action: [DroneApiController::class, 'index']);
    Route::post('/telemetry', [DroneApiController::class, 'store']);
});

/* Route::get('/test', function () {
    return response()->json(['message' => 'API работает']);
}); */
