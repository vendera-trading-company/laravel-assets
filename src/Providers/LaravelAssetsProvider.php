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
        $this->publishes([
            __DIR__ . '/../database/migrations/2023_01_01_000000_create_images_table.php' => database_path('migrations/2023_01_01_000000_create_images_table.php')
        ], 'vendera-trading-company/laravel-assets/migrations-images');

        $this->publishes([
            __DIR__ . '/../database/migrations/2023_01_01_000001_create_files_table.php' => database_path('migrations/2023_01_01_000001_create_files_table.php')
        ], 'vendera-trading-company/laravel-assets/migrations-files');

        $this->publishes([
            __DIR__ . '/../database/migrations/2023_01_01_000002_create_markdowns_table.php' => database_path('migrations/2023_01_01_000002_create_markdowns_table.php')
        ], 'vendera-trading-company/laravel-assets/migrations-markdowns');

        $this->publishes([
            __DIR__ . '/../database/migrations/2023_01_01_000003_create_pdfs_table.php' => database_path('migrations/2023_01_01_000003_create_pdfs_table.php')
        ], 'vendera-trading-company/laravel-assets/migrations-pdfs');
    }
}
