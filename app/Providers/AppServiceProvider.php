<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Определение прав администратора
        Gate::define('admin', function ($user) {
            return $user->is_admin === true; // Явная проверка на true
        });

        // Альтернативный короткий вариант:
        // Gate::define('admin', fn ($user) => $user->is_admin);
    }
}