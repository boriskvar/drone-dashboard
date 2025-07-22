<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DroneApiController;

Route::middleware('auth:sanctum')->prefix('drones')->group(function () {
    Route::post('/telemetry', action: [DroneApiController::class, 'index']);
    Route::post('/telemetry', [DroneApiController::class, 'store']);
});
