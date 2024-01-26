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

        if (empty($url) && empty($file)) {
            return;
        }

        if (!empty($url)) {
            $file = file_get_contents($url);

            if (empty($file)) {
                return;
            }
        }

        $base64DecodedImage = $this->decodeBase64Image($file);

        if (!empty($base64DecodedImage)) {
            $file = $base64DecodedImage;
        }

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

        $id = now()->timestamp . '_' . strtolower(Str::random(32)) . '_image';

        $image = Image::create([
            'id' => $id,
            'relative_path' => $relative_path,
            'disk' => $disk
        ]);

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

            if ($this->is_base64_encoded($image)) {
                $image = base64_decode($image);

                return $image;
            }
        } catch (Exception $e) {
        }

        return null;
    }

    private function is_base64_encoded($str)
    {
        try {
            $decoded_str = base64_decode($str);
            $Str1 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $decoded_str);
            if ($Str1 != $decoded_str || $Str1 == '') {
                return false;
            }

            return true;
        } catch (Exception $e) {
        }

        return false;
    }
}
