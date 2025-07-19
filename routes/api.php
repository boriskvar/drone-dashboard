<?php

Route::middleware('auth:sanctum')->prefix('drones')->group(function () {
    Route::post('/telemetry', [DroneApiController::class, 'store']);
});