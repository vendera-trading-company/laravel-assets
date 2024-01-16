<?php

namespace VenderaTradingCompany\LaravelAssets\Actions;

use Illuminate\Support\Facades\Storage;
use VenderaTradingCompany\PHPActions\Action;
use Illuminate\Support\Str;

class AssetStore extends Action
{
    protected $secure = [
        'data',
        'disk',
        'path',
        'name'
    ];

    public function handle()
    {
        $data = $this->getData('data');
        $disk = $this->getData('disk');
        $path = $this->getData('path');
        $name = $this->getData('name');

        if (empty($data)) {
            return;
        }

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

        if (!empty($disk)) {
            Storage::disk($disk)->put($relative_path, $data);
            $absolute_path = Storage::disk($disk)->path($relative_path);
        } else {
            Storage::put($relative_path, $data);
            $absolute_path = Storage::path($relative_path);
        }

        return [
            'absolute_path' => $absolute_path,
            'relative_path' => $relative_path,
            'disk' => $disk,
        ];
    }
}
