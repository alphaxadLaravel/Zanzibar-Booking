<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Exclude Pesapal callback routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'payment/*',
        ]);

        // Register custom middleware aliases
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'track.visit' => \App\Http\Middleware\TrackSiteVisit::class,
        ]);
        
        // Apply middleware to web routes
        $middleware->web(append: [
            \App\Http\Middleware\TrackSiteVisit::class,
            \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
