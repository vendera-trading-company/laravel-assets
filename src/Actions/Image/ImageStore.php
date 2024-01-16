<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Image;

use VenderaTradingCompany\LaravelAssets\Actions\AssetStore;
use VenderaTradingCompany\LaravelAssets\Models\Image;
use VenderaTradingCompany\PHPActions\Action;
use Illuminate\Support\Str;

class ImageStore extends Action
{
    protected $secure = [
        'url',
        'file',
        'disk',
        'path',
        'name'
    ];

    public function handle()
    {
        $url = $this->getData('url');
        $file = $this->getData('file');
        $disk = $this->getData('disk');
        $path = $this->getData('path');
        $name = $this->getData('name');

        if (empty($path)) {
            return;
        }

        if (empty($url) && empty($file)) {
            return;
        }

        if (!empty($url)) {
            $file = file_get_contents($url);

            if (empty($file)) {
                return;
            }
        }

        $stored_file = Action::run(AssetStore::class, [
            'name' => $name,
            'disk' => $disk,
            'path' => $path,
            'data' => $file
        ]);

        $absolute_path = $stored_file->getData('absolute_path');
        $relative_path = $stored_file->getData('relative_path');
        $disk = $stored_file->getData('disk');

        if (empty($absolute_path) || empty($relative_path)) {
            return;
        }

        $id = now()->timestamp . '_' . strtolower(Str::random(32));

        $image = Image::create([
            'id' => $id,
            'absolute_path' => $absolute_path,
            'relative_path' => $relative_path,
            'disk' => $disk
        ]);

        return [
            'image' => $image
        ];
    }
}
