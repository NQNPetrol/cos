<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use App\Observers\PermissionObserver;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;


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
         Permission::observe(PermissionObserver::class);
         Route::aliasMiddleware('role', RoleMiddleware::class);

    }
}
