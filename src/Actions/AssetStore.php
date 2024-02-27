<?php

namespace VenderaTradingCompany\LaravelAssets\Actions;

use Illuminate\Support\Facades\Storage;
use VenderaTradingCompany\PHPActions\Action;
use Illuminate\Support\Str;

/**
 * @data string $data
 * @data string $disk
 * @data string $path
 * @data string $name
 * @data string $relative_path
 * @response mixed $relative_path
 * @response mixed $disk
 */
class AssetStore extends Action
{
    protected $secure = [
        'data',
        'disk',
        'path',
        'name',
        'relative_path'
    ];

    public function handle()
    {
        $data = $this->getData('data');
        $disk = $this->getData('disk');
        $path = $this->getData('path');
        $name = $this->getData('name');
        $relative_path = $this->getData('relative_path');

        if (empty($data)) {
            return;
        }

        if (empty($relative_path)) {
            if (empty($path)) {
                $path = 'files/';
            }

            if (!str_ends_with($path, '/')) {
                $path = $path . '/';
            }

            if (empty($name)) {
                $name = now()->timestamp . '_' . strtolower(Str::random(32));
            }

            $relative_path = $path . $name;
        }

        if (!empty($disk)) {
            if (!Storage::disk($disk)->put($relative_path, $data)) {
                return;
            }
        } else {
            if (!Storage::put($relative_path, $data)) {
                return;
            }
        }

        return [
            'relative_path' => $relative_path,
            'disk' => $disk,
        ];
    }
}
