<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon; // ✅ THIS WAS MISSING

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force IST globally (PHP + Laravel + Carbon)
        date_default_timezone_set('Asia/Kolkata');

        Carbon::setLocale('en');

        Paginator::useBootstrap();
        Schema::defaultStringLength(191);

        Blade::if('can', function ($expression, $type = 'admin') {
            return auth('admin')->check()
                && auth('admin')->user()->hasAccess($expression);
        });
    }
}