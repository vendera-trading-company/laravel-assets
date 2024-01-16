<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\File\Image;

use VenderaTradingCompany\LaravelAssets\Actions\Image\ImageStore as ImageImageStore;
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
        $image = Action::run(ImageImageStore::class)->getData('image');

        if (empty($image)) {
            return response()->json([
                'status' => 'error'
            ]);
        }

        return response()->json([
            'image' => $image,
            'image_id' => $image->id
        ]);
    }
}
