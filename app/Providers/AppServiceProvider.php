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
        // Force all generated URLs (reset password, verification, etc.) to use APP_URL.
        // On production set APP_URL=https://www.zanzibarbookings.com (no /public suffix).
        $appUrl = rtrim((string) config('app.url'), '/');
        if ($appUrl !== '') {
            if (str_ends_with($appUrl, '/public')) {
                $appUrl = preg_replace('#/public$#', '', $appUrl);
            }
            URL::forceRootUrl($appUrl);
            if (str_starts_with($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
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
