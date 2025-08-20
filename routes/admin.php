<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDroneController;
use App\Http\Controllers\Admin\AdminTargetController;
use App\Http\Controllers\Admin\AdminFlightDataController;
use App\Http\Controllers\Admin\AdminSimulationController;

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

        // 🛰️ CRUD для полётных данных
        Route::prefix('flight-data')->group(function () {
            Route::get('/', [AdminFlightDataController::class, 'index'])->name('admin.flight_data.index');
            Route::get('/create', [AdminFlightDataController::class, 'create'])->name('admin.flight_data.create');
            Route::post('/store', [AdminFlightDataController::class, 'store'])->name('admin.flight_data.store');
            Route::get('{flightData}/edit', [AdminFlightDataController::class, 'edit'])->name('admin.flight_data.edit');
            Route::put('{flightData}', [AdminFlightDataController::class, 'update'])->name('admin.flight_data.update');
            Route::delete('{flightData}', [AdminFlightDataController::class, 'destroy'])->name('admin.flight_data.destroy');
        });

        // 🎯 CRUD для симуляционных целей
        Route::prefix('targets')->group(function () {
            Route::get('/', [AdminTargetController::class, 'index'])->name('admin.targets.index');
            Route::get('/create', [AdminTargetController::class, 'create'])->name('admin.targets.create');
            Route::post('/store', [AdminTargetController::class, 'store'])->name('admin.targets.store');
            Route::get('{target}/edit', [AdminTargetController::class, 'edit'])->name('admin.targets.edit');
            Route::put('{target}', [AdminTargetController::class, 'update'])->name('admin.targets.update');
            Route::delete('{target}', [AdminTargetController::class, 'destroy'])->name('admin.targets.destroy');
        });

        // 🚀 Маршруты для симуляции движения дронов
        /* Route::prefix('simulation')->name('admin.simulation.')->group(function () {
            Route::post('start/{drone}', [ApiFlightDataController::class, 'startSimulation'])->name('start');
            Route::post('stop/{drone}', [ApiFlightDataController::class, 'stopSimulation'])->name('stop');
        }); */

        // 🚀 Маршруты для симуляции дронов
        /*  Route::prefix('simulation')->name('admin.simulation.')->group(function () {
            Route::post('start/{drone}', [AdminSimulationController::class, 'start'])->name('start');
            Route::post('stop/{drone}', [AdminSimulationController::class, 'stop'])->name('stop');
        }); */

        Route::prefix('simulation')->name('admin.simulation.')->group(function () {
            Route::get('/', [AdminDroneController::class, 'simulation'])->name('index');
            Route::post('start/{drone}', [AdminDroneController::class, 'startSimulation'])->name('start');
            Route::post('stop/{drone}', [AdminDroneController::class, 'stopSimulation'])->name('stop');
        });
    });