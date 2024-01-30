<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\File;

use VenderaTradingCompany\LaravelAssets\Actions\AssetStore;
use VenderaTradingCompany\PHPActions\Action;
use Illuminate\Support\Str;
use VenderaTradingCompany\LaravelAssets\Models\File;

class FileStore extends Action
{
    protected $secure = [
        'database',
        'disk',
        'path',
        'name',
        'url',
        'file'
    ];

    public function handle()
    {
        $url = $this->getData('url');
        $file = $this->getData('file');
        $disk = $this->getData('disk');
        $database = $this->getData('database');
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

        $id = now()->timestamp . '_' . strtolower(Str::random(32)) . '_file';

        $file_data = [
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

            $file_data['relative_path'] = $relative_path;
            $file_data['disk'] = $disk;
        } else {
            $file_data['data'] = $file;
        }

        $file_model = File::create($file_data);

        return [
            'file' => $file_model
        ];
    }
}
