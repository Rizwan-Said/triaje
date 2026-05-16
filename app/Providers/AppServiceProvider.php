<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Force HTTPS for all generated URLs when running in production.
        // This ensures asset URLs use https:// even if APP_URL is still set
        // to http://, preventing mixed-content errors behind Railway's proxy.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
