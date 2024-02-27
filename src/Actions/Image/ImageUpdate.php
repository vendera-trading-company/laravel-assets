<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Image;

use VenderaTradingCompany\LaravelAssets\Actions\AssetStore;
use VenderaTradingCompany\LaravelAssets\Actions\Base64Decode;
use VenderaTradingCompany\LaravelAssets\Models\Image;
use VenderaTradingCompany\PHPActions\Action;

/**
 * @option bool $base64
 * @data string $database
 * @data string $file
 * @data string $id
 * @response Image $image
 */
class ImageUpdate extends Action
{
    protected $secure = [
        'id',
        'database',
        'file'
    ];

    public function handle()
    {
        $file = $this->getData('file');
        $id = $this->getData('id');
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

        if ($this->getOption('base64', false)) {
            $file = Action::build(Base64Decode::class)->data([
                'data' => $file,
            ])->run()->getData('data');
        }

        if (empty($file)) {
            return;
        }

        $image_data = [];

        if (!$database) {
            $stored_file = Action::build(AssetStore::class)->data([
                'disk' => $image->disk,
                'relative_path' => $image->relative_path,
                'data' => $file
            ])->run();

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
}
