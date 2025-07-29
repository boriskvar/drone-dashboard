<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDroneController;
use App\Http\Controllers\Admin\AdminDronePositionController;
use App\Http\Controllers\Admin\AdminTelemetryController;

// Группируем всё под общим префиксом `admin` + middleware
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::prefix('telemetries')->group(function () {
            Route::get('/', [AdminTelemetryController::class, 'index'])->name('admin.telemetries.index');
            Route::get('/create', [AdminTelemetryController::class, 'create'])->name('admin.telemetries.create');
            Route::post('/store', [AdminTelemetryController::class, 'store'])->name('admin.telemetries.store');
            Route::get('{id}/edit', [AdminTelemetryController::class, 'edit'])->name('admin.telemetries.edit');
            Route::put('{id}', [AdminTelemetryController::class, 'update'])->name('admin.telemetries.update');
            Route::delete('{id}', [AdminTelemetryController::class, 'destroy'])->name('admin.telemetries.destroy');
        });

        // --- Drones ---
        Route::prefix('drones')->group(function () {
            Route::get('/', [AdminDroneController::class, 'index'])->name('admin.drones.index');
            Route::get('/create', [AdminDroneController::class, 'create'])->name('admin.drones.create');
            Route::post('/', [AdminDroneController::class, 'store'])->name('admin.drones.store');
            Route::get('/{drone}/edit', [AdminDroneController::class, 'edit'])->name('admin.drones.edit');
            Route::put('/{drone}', [AdminDroneController::class, 'update'])->name('admin.drones.update');
            Route::delete('/{drone}', [AdminDroneController::class, 'destroy'])->name('admin.drones.destroy');

            // Кнопка "Сдвинуть дрона"
            Route::post('/{drone}/move', [AdminDroneController::class, 'move'])->name('admin.drones.move');
        });

        // --- Drone Positions ---
        Route::prefix('positions')->group(function () {
            Route::get('/', [AdminDronePositionController::class, 'index'])->name('admin.positions.index');
            Route::get('/create', [AdminDronePositionController::class, 'create'])->name('admin.positions.create');
            Route::post('/', [AdminDronePositionController::class, 'store'])->name('admin.positions.store');
            Route::get('/{position}/edit', [AdminDronePositionController::class, 'edit'])->name('admin.positions.edit');
            Route::put('/{position}', [AdminDronePositionController::class, 'update'])->name('admin.positions.update');
            Route::delete('/{position}', [AdminDronePositionController::class, 'destroy'])->name('admin.positions.destroy');
        });
    });