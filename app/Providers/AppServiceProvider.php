<?php

namespace App\Providers;

use Doctrine\DBAL\Logging\Middleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

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
        Paginator::useTailwind();
       

        Route::middleware('api')->prefix('api')->group(function () {
                require base_path('routes/api.php');
        });

        // Register Blade components
        Blade::component('app-admin', 'layouts.app-admin');
    }
}
