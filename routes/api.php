<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiFlightDataController;   // Данные полёта (store, show, track)
use App\Http\Controllers\Api\ApiImageController;        // Загрузка изображений
use App\Http\Controllers\Api\ApiDetectionController;    // Распознавание объектов
use App\Http\Controllers\Api\ApiTargetController;       // Текущая цель

// Ограничение: параметр {drone} всегда число
Route::pattern('drone', '[0-9]+');

// --- Маршруты для работы с дронами ---
Route::prefix('drones')->group(function () {

    /**
     * 📍 Получить текущую цель дрона
     * Метод: GET /api/drones/{drone}/target
     * Может использоваться независимо от simulate-flight-data
     */
    Route::get('{drone}/target', [ApiTargetController::class, 'show'])
        ->name('api.drones.target.show');

    // Назначить цель дрону
    Route::post('{drone}/target', [ApiTargetController::class, 'update'])
        ->name('api.drones.target.update');

    /**
     * 📍 Получить последние координаты ВСЕХ дронов
     * Метод: GET /api/drones/flight-data
     * Возвращает массив с последними позициями и дополнительными данными
     */
    Route::get('flight-data', [ApiFlightDataController::class, 'latestPositions'])
        ->name('api.drones.flight-data.latest');

    /**
     * 📍 Прислать данные полёта ОДНОГО дрона
     * Метод: POST /api/drones/{drone}/flight-data
     * Тело запроса: { latitude, longitude, altitude?, speed?, heading?, captured_at? }
     */
    Route::post('{drone}/flight-data', [ApiFlightDataController::class, 'store'])
        ->name('api.drones.flight-data.store');

    /**
     * 📍 Получить последние данные ОДНОГО дрона
     * Метод: GET /api/drones/{drone}/flight-data
     */
    Route::get('{drone}/flight-data', [ApiFlightDataController::class, 'show'])
        ->name('api.drones.flight-data.show');

    /**
     * 📍 Получить трек движения дрона
     * Метод: GET /api/drones/{drone}/track
     * Параметры фильтра (опционально) можно добавить позже: за час, за день и т.п.
     */
    Route::get('{drone}/track', [ApiFlightDataController::class, 'track'])
        ->name('api.drones.track');

    /**
     * 📍 Загрузить изображение или кадр от дрона
     * Метод: POST /api/drones/{drone}/image
     * Форматы: multipart/form-data или base64 (реализуется в контроллере)
     */
    Route::post('{drone}/image', [ApiImageController::class, 'store'])
        ->name('api.drones.image.store');

    /**
     * 📍 Получить результаты распознавания объектов дрона
     * Метод: GET /api/drones/{drone}/detections
     */
    Route::get('{drone}/detections', [ApiDetectionController::class, 'index'])
        ->name('api.drones.detections.index');
});
