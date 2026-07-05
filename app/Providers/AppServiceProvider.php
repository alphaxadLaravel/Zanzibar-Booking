<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\System;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        // Normalize app URL: if APP_URL ends with /public, all route()/url() will generate without it.
        // On production, prefer setting APP_URL to the site root (e.g. https://www.zanzibarbookings.com).
        $appUrl = rtrim(config('app.url'), '/');
        if ($appUrl !== '' && str_ends_with($appUrl, '/public')) {
            URL::forceRootUrl(preg_replace('#/public$#', '', $appUrl));
        }

        // Set custom pagination view
        Paginator::defaultView('pagination.custom');

        Blade::if('permission', function (string $slug) {
            $user = Auth::user();

            return $user instanceof User && $user->hasPermission($slug);
        });

        Blade::if('anyPermission', function (...$slugs) {
            $user = Auth::user();

            return $user instanceof User && $user->hasAnyPermission($slugs);
        });

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
