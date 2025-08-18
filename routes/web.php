<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Operator\OperatorController;

// Панель оператора (карта)
/* Route::prefix('operator')->name('operator.')->group(function () {
    // Route::get('/', [OperatorController::class, 'index'])->name('index'); // главная страница оператора
    // Route::get('/map', [OperatorController::class, 'map'])->name('map');  // карта дронов
    Route::get('/', [OperatorController::class, 'map'])->name('map');  // карта дронов
    // Route::get('/', [OperatorController::class, 'map'])->name('map');
}); */

Route::get('/', [OperatorController::class, 'map'])->name('map');  // карта дронов

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
});

// Главный редирект (на dashboard или operator - выбирайте)
// Route::redirect('/', '/dashboard'); // Или '/operator'
// Редирект на operator по умолчанию
// Route::redirect('/', '/operator');


// Админ-маршруты
require base_path('routes/admin.php');
require __DIR__ . '/auth.php';