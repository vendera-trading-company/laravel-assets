<?php

namespace VenderaTradingCompany\LaravelAssets\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelAssetsProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
