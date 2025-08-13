<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDroneController;
use App\Http\Controllers\Admin\Simulate\AdminSimulateTargetController;
use App\Http\Controllers\Admin\Simulate\AdminSimulateFlightDataController;

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        // 📦 CRUD для дронов
        Route::prefix('drones')->group(function () {
            Route::get('/', [AdminDroneController::class, 'index'])->name('admin.drones.index');
            Route::get('/create', [AdminDroneController::class, 'create'])->name('admin.drones.create');
            Route::post('/store', [AdminDroneController::class, 'store'])->name('admin.drones.store');
            Route::get('{drone}/edit', [AdminDroneController::class, 'edit'])->name('admin.drones.edit');
            Route::put('{drone}', [AdminDroneController::class, 'update'])->name('admin.drones.update');
            Route::delete('{drone}', [AdminDroneController::class, 'destroy'])->name('admin.drones.destroy');

            Route::post('{drone}/move', [AdminDroneController::class, 'move'])->name('admin.drones.move');
        });


        // 🛰️ CRUD для симуляционных полётных данных
        Route::prefix('simulate-flight-data')->group(function () {
            Route::get('/', [AdminSimulateFlightDataController::class, 'index'])->name('admin.simulate_flight_data.index');
            Route::get('/create', [AdminSimulateFlightDataController::class, 'create'])->name('admin.simulate_flight_data.create');
            Route::post('/store', [AdminSimulateFlightDataController::class, 'store'])->name('admin.simulate_flight_data.store');
            Route::get('{simulateFlightData}/edit', [AdminSimulateFlightDataController::class, 'edit'])->name('admin.simulate_flight_data.edit');
            Route::put('{simulateFlightData}', [AdminSimulateFlightDataController::class, 'update'])->name('admin.simulate_flight_data.update');
            Route::delete('{simulateFlightData}', [AdminSimulateFlightDataController::class, 'destroy'])->name('admin.simulate_flight_data.destroy');
        });

        // 🎯 CRUD для симуляционных целей
        Route::prefix('simulate-targets')->group(function () {
            Route::get('/', [AdminSimulateTargetController::class, 'index'])->name('admin.simulate_targets.index');
            Route::get('/create', [AdminSimulateTargetController::class, 'create'])->name('admin.simulate_targets.create');
            Route::post('/store', [AdminSimulateTargetController::class, 'store'])->name('admin.simulate_targets.store');
            Route::get('{simulateTarget}/edit', [AdminSimulateTargetController::class, 'edit'])->name('admin.simulate_targets.edit');
            Route::put('{simulateTarget}', [AdminSimulateTargetController::class, 'update'])->name('admin.simulate_targets.update');
            Route::delete('{simulateTarget}', [AdminSimulateTargetController::class, 'destroy'])->name('admin.simulate_targets.destroy');
        });
    });
