<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\System;

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
        // Set custom pagination view
        Paginator::defaultView('pagination.custom');

        // Share system settings with all views
        View::composer('*', function ($view) {
            $systemSettings = System::first();
            $view->with('systemSettings', $systemSettings);
        });
    }
}
