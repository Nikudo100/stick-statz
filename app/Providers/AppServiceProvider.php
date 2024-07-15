<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WbApiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('wbapi', function ($app) {
            return new WbApiService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
