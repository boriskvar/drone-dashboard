<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDroneController;
use App\Http\Controllers\Admin\AdminDronePositionController;

// Группируем всё под общим префиксом `admin` + middleware
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

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