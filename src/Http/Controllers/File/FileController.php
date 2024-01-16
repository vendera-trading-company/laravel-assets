<?php

namespace VenderaTradingCompany\LaravelAssets\Http\Controllers\File;

use VenderaTradingCompany\LaravelAssets\Models\File;
use VenderaTradingCompany\LaravelHighway\HighwayController;

class FileController extends HighwayController
{
    protected static $entity = File::class;
}
