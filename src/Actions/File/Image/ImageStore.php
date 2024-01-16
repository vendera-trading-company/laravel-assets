<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\File\Image;

use VenderaTradingCompany\PHPActions\Action;

class ImageStore extends Action
{
    protected $secure = [
        'disk',
        'path',
        'name'
    ];

    public function handle()
    {
        $image = Action::run(ImageStore::class)->getData('image');

        return response()->json([
            'image' => $image
        ]);
    }
}
