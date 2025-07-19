<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Operator\OperatorController;

// Главный редирект
Route::redirect('/', '/operator');

// Группа аутентифицированных маршрутов
Route::middleware(['auth', 'verified'])->group(function () {
    // Профиль (из Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Панель оператора
    Route::prefix('operator')->group(function () {
        Route::get('/', [OperatorController::class, 'index'])->name('operator');
    });

    // Админ-панель
    Route::middleware(['auth', 'verified', 'is_admin'])->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin');
    });
});

// Устаревший dashboard (редирект на операторскую панель)
Route::get('/dashboard', fn() => redirect()->route('operator'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__ . '/auth.php';