<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiFlightDataController;   // store(), track()
use App\Http\Controllers\Api\ApiImageController;       // store()
use App\Http\Controllers\Api\ApiDetectionController;   // index()
use App\Http\Controllers\Api\ApiTargetController;      // (опционально) show()

// Чтобы {drone} был числом
Route::pattern('drone', '[0-9]+');

Route::prefix('drones')->group(function () {

    // 1) Приём flight-data (эмулятор или реальный дрон шлёт POST)
    //    body: { latitude, longitude, altitude?, speed?, heading?, captured_at? }
    Route::post('{drone}/flight-data', [ApiFlightDataController::class, 'store'])
        ->name('api.drones.flight-data.store');

    // 2) Получить трек (например, за последний час/день — параметры можно добавить позже)
    Route::get('{drone}/track', [ApiFlightDataController::class, 'track'])
        ->name('api.drones.track');

    // 3) Загрузка изображения/кадра (multipart или base64 — решим в контроллере)
    Route::post('{drone}/image', [ApiImageController::class, 'store'])
        ->name('api.drones.image.store');

    // 4) Результаты распознавания объектов (для фронта)
    Route::get('{drone}/detections', [ApiDetectionController::class, 'index'])
        ->name('api.drones.detections.index');

    // 5) (Опционально) Получить текущую цель из БД отдельным запросом,
    //    если тебе нужно вызывать её независимо от simulate-flight-data
    Route::get('{drone}/target', [ApiTargetController::class, 'show'])
        ->name('api.drones.target.show');
});
