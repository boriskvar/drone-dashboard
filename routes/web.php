<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Operator\OperatorController;
use App\Http\Controllers\Admin\AdminController;

// Главный редирект (на dashboard или operator - выбирайте)
Route::redirect('/', '/dashboard'); // Или '/operator'

// Группа аутентифицированных маршрутов
Route::middleware(['auth', 'verified'])->group(function () {
    // Стандартный Breeze dashboard (без контроллера)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Профиль (из Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Панель оператора
    Route::prefix('operator')->name('operator.')->group(function () {
        Route::get('/', [OperatorController::class, 'index'])->name('index');
        Route::get('/map', [OperatorController::class, 'map'])->name('map');
    });

    // Админ-панель
    Route::middleware('is_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
    });
});

require __DIR__ . '/auth.php';
