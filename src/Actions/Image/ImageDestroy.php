<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Image;

use VenderaTradingCompany\LaravelAssets\Models\Image;
use VenderaTradingCompany\PHPActions\Action;

/**
 * @data string $id
 */
class ImageDestroy extends Action
{
    protected $secure = [
        'id',
    ];

    public function handle()
    {
        $id = $this->getData('id');

        if (empty($id)) {
            return;
        }

        $image = Image::where('id', $id)->first();

        if (empty($image)) {
            return;
        }

        $image->delete();
    }
}
