<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Image;

use Exception;
use VenderaTradingCompany\PHPActions\Action;

/**
 * @data string $image
 */
class ImageBase64Decode extends Action
{
    protected $secure = [
        'image',
    ];

    public function handle()
    {
        $image = $this->getData('image');

        if (empty($image)) {
            return null;
        }

        try {
            $image = preg_replace('/^.+\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);

            $image = base64_decode($image);

            return [
                'image' => $image
            ];
        } catch (Exception $e) {
        }

        return null;
    }
}
