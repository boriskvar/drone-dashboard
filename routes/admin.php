<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDroneController;
use App\Http\Controllers\Admin\AdminDronePositionController;
// use App\Http\Controllers\Admin\AdminTelemetryController;
use App\Http\Controllers\Admin\Simulate\AdminSimulatePositionController;
use App\Http\Controllers\Admin\AdminTargetController;

// Группируем всё под общим префиксом `admin` + middleware

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::prefix('simulate-positions')->group(function () {
            Route::get('/', [AdminSimulatePositionController::class, 'index'])->name('admin.simulate_positions.index');
            Route::get('/create', [AdminSimulatePositionController::class, 'create'])->name('admin.simulate_positions.create');
            Route::post('/store', [AdminSimulatePositionController::class, 'store'])->name('admin.simulate_positions.store');
            Route::get('{id}/edit', [AdminSimulatePositionController::class, 'edit'])->name('admin.simulate_positions.edit');
            Route::put('{id}', [AdminSimulatePositionController::class, 'update'])->name('admin.simulate_positions.update');
            Route::delete('{id}', [AdminSimulatePositionController::class, 'destroy'])->name('admin.simulate_positions.destroy');
        });



        Route::prefix('targets')->group(function () {
            Route::get('/', [AdminTargetController::class, 'index'])->name('admin.targets.index');
            Route::get('/targets/create', [AdminTargetController::class, 'create'])->name('admin.targets.create');
            Route::post('/targets', [AdminTargetController::class, 'store'])->name('admin.targets.store');
            Route::get('/{id}/edit', [AdminTargetController::class, 'edit'])->name('admin.targets.edit');
            Route::put('/{id}', [AdminTargetController::class, 'update'])->name('admin.targets.update');
            Route::delete('/{id}', [AdminTargetController::class, 'destroy'])->name('admin.targets.destroy');
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