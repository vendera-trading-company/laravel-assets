<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Image;

use Exception;
use VenderaTradingCompany\LaravelAssets\Actions\AssetStore;
use VenderaTradingCompany\LaravelAssets\Models\Image;
use VenderaTradingCompany\PHPActions\Action;

class ImageUpdate extends Action
{
    protected $secure = [
        'id',
        'database',
        'base64',
        'file'
    ];

    public function handle()
    {
        $file = $this->getData('file');
        $id = $this->getData('id');
        $base64 = $this->getData('base64');
        $database = $this->getData('database');

        if (empty($id)) {
            return;
        }

        $image = Image::where('id', $id)->first();

        if (empty($image)) {
            return;
        }

        if (empty($file)) {
            return;
        }

        if ($base64) {
            $file = $this->decodeBase64Image($file);
        }

        $image_data = [];

        if (!$database) {
            $stored_file = Action::run(AssetStore::class, [
                'disk' => $image->disk,
                'relative_path' => $image->relative_path,
                'data' => $file
            ]);

            $relative_path = $stored_file->getData('relative_path');
            $disk = $stored_file->getData('disk');

            if (empty($relative_path)) {
                return;
            }

            $image_data['relative_path'] = $relative_path;
            $image_data['disk'] = $disk;
            $image_data['data'] = null;
        } else {
            $image_data['data'] = $file;
            $image_data['relative_path'] = null;
            $image_data['disk'] = null;
        }

        $image->update($image_data);

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
