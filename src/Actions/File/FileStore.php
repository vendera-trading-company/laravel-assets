<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\File;

use VenderaTradingCompany\LaravelAssets\Actions\AssetStore;
use VenderaTradingCompany\PHPActions\Action;
use Illuminate\Support\Str;
use VenderaTradingCompany\LaravelAssets\Actions\Base64Decode;
use VenderaTradingCompany\LaravelAssets\Models\File;

/**
 * @option bool $base64
 * @data string $database
 * @data string $disk
 * @data string $path
 * @data string $file
 * @data string $id
 * @response File $file
 */
class FileStore extends Action
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
        $disk = $this->getData('disk');
        $database = $this->getData('database', true);
        $path = $this->getData('path');
        $name = $this->getData('name');

        if ($this->getOption('base64', false)) {
            $file = Action::build(Base64Decode::class)->data([
                'data' => $file,
            ])->run()->getData('data');
        }

        $id = $this->getData('id', now()->timestamp . '_' . strtolower(Str::random(32)) . '_file');

        $file_data = [
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
