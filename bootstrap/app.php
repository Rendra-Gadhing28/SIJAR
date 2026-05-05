<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class, // Ada session
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->api([
             \Illuminate\Routing\Middleware\SubstituteBindings::class,
               
             \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
               'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,  // Pastikan ada
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
