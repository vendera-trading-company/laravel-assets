<?php

use Illuminate\Support\Facades\Route;
use VenderaTradingCompany\LaravelAssets\Models\File as ModelsFile;
use VenderaTradingCompany\LaravelAssets\Models\Image;
use VenderaTradingCompany\LaravelAssets\Routing\File;

$middlewares = config('laravelassets.middlewares');
$routes_enabled = config('laravelassets.routes.enabled', true);

if ($routes_enabled) {
    if (!empty($middlewares)) {
        Route::prefix('filesystem')->middleware($middlewares)->group(function () {
            File::post(Image::class, 'store', 'store');
            File::post(ModelsFile::class, 'store', 'store');
        });
    } else {
        Route::prefix('filesystem')->group(function () {
            File::post(Image::class, 'store', 'store');
            File::post(ModelsFile::class, 'store', 'store');
        });
    }
}
