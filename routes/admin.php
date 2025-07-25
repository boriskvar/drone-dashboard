    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Admin\AdminDroneController;

    // Route::prefix('admin/drones')->group(function () {
    Route::middleware(['auth', 'admin'])
        ->prefix('admin/drones')
        ->group(function () {
            Route::get('/', [AdminDroneController::class, 'index'])->name('admin.drones.index');
            Route::get('/create', [AdminDroneController::class, 'create'])->name('admin.drones.create');
            Route::post('/', [AdminDroneController::class, 'store'])->name('admin.drones.store');
            Route::get('/{drone}/edit', [AdminDroneController::class, 'edit'])->name('admin.drones.edit');
            Route::put('/{drone}', [AdminDroneController::class, 'update'])->name('admin.drones.update');
            Route::delete('/{drone}', [AdminDroneController::class, 'destroy'])->name('admin.drones.destroy');
        });