<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Image;

use VenderaTradingCompany\LaravelAssets\Actions\AssetStore;
use VenderaTradingCompany\LaravelAssets\Models\Image;
use VenderaTradingCompany\PHPActions\Action;
use Illuminate\Support\Str;

class ImageStore extends Action
{
    protected $secure = [
        'database',
        'disk',
        'path',
        'name',
        'file',
        'id'
    ];

    public function handle()
    {
        $file = $this->getData('file');
        $database = $this->getData('database');
        $disk = $this->getData('disk');
        $path = $this->getData('path');
        $name = $this->getData('name');

        if (empty($file)) {
            return;
        }

        if ($this->getOption('base64', false)) {
            $file = Action::build(ImageBase64Decode::class)->data([
                'image' => $file,
            ])->run()->getData('image');
        }

        if (empty($file)) {
            return;
        }

        $id = $this->getData('id', now()->timestamp . '_' . strtolower(Str::random(32)) . '_image');

        $image_data = [
            'id' => $id,
        ];

        if (!$database) {
            $stored_file = Action::build(AssetStore::class)->data([
                'name' => $name,
                'disk' => $disk,
                'path' => $path,
                'data' => $file
            ])->run();

            $relative_path = $stored_file->getData('relative_path');
            $disk = $stored_file->getData('disk');

            if (empty($relative_path)) {
                return;
            }

            $image_data['relative_path'] = $relative_path;
            $image_data['disk'] = $disk;
        } else {
            $image_data['data'] = $file;
        }

        $image = Image::create($image_data);

        return [
            'image' => $image
        ];
    }
}
