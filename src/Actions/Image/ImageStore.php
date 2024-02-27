<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Image;

use Exception;
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
        'base64',
        'file'
    ];

    public function handle()
    {
        $file = $this->getData('file');
        $database = $this->getData('database');
        $disk = $this->getData('disk');
        $path = $this->getData('path');
        $name = $this->getData('name');
        $base64 = $this->getData('base64');

        if (empty($file)) {
            return;
        }

        if ($base64) {
            $file = $this->decodeBase64Image($file);
        }

        $id = now()->timestamp . '_' . strtolower(Str::random(32)) . '_image';

        $image_data = [
            'id' => $id,
        ];

        if (!$database) {
            $stored_file = Action::run(AssetStore::class, [
                'name' => $name,
                'disk' => $disk,
                'path' => $path,
                'data' => $file
            ]);

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

    private function decodeBase64Image($data)
    {
        try {
            $image = $data;
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);

            $image = base64_decode($image);

            return $image;
        } catch (Exception $e) {
        }

        return null;
    }
}
