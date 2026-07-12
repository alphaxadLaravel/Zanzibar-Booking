<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        // Exclude Pesapal callback routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'payment/*',
            'api/v1/payments/mobile-callback',
        ]);

        // After auth gate, send guests to login with return URL so they resume booking
        // (web only — API clients get JSON 401 from Sanctum)
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return null;
            }

            return route('login', [
                'redirect' => url()->full(),
            ]);
        });

        // Register custom middleware aliases
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'track.visit' => \App\Http\Middleware\TrackSiteVisit::class,
            'not.suspended' => \App\Http\Middleware\EnsureUserNotSuspended::class,
            'admin.panel' => \App\Http\Middleware\EnsureAdminPanelAccess::class,
            'permission' => \App\Http\Middleware\EnsurePermission::class,
            'permission.deal' => \App\Http\Middleware\EnsureDealPermission::class,
            'super.admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
        ]);
        
        // Apply middleware to web routes
        $middleware->web(append: [
            \App\Http\Middleware\TrackSiteVisit::class,
            \App\Http\Middleware\EnsureEmailIsVerified::class,
            \App\Http\Middleware\EnsureUserNotSuspended::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
