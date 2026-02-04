<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
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
        // Ensure generated URLs do not include /public (e.g. Packages link going to /public/packages)
        $appUrl = rtrim(config('app.url'), '/');
        if (str_ends_with($appUrl, '/public')) {
            URL::forceRootUrl(preg_replace('#/public$#', '', $appUrl));
        }

        // Set custom pagination view
        Paginator::defaultView('pagination.custom');

        // Share system settings with all views
        View::composer('*', function ($view) {
            $systemSettings = System::first();
            $view->with('systemSettings', $systemSettings);
        });

        // Share user currency for geo-based price conversion (GeoGenius)
        View::composer('*', function ($view) {
            $view->with('userCurrency', userCurrency());
            $view->with('userCountryCode', userCountryCode());
        });
    }
}
