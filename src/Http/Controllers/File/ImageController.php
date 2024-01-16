<?php

namespace VenderaTradingCompany\LaravelAssets\Http\Controllers\File;

use VenderaTradingCompany\LaravelAssets\Models\Image;
use VenderaTradingCompany\LaravelHighway\HighwayController;

class ImageController extends HighwayController
{
    protected static $entity = Image::class;
}
