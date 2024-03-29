<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\File;

use VenderaTradingCompany\LaravelAssets\Actions\AssetStore;
use VenderaTradingCompany\LaravelAssets\Actions\Base64Decode;
use VenderaTradingCompany\PHPActions\Action;
use VenderaTradingCompany\LaravelAssets\Models\File;

/**
 * @option bool $base64
 * @data string $database
 * @data string $file
 * @data string $id
 * @response File $file
 */
class FileUpdate extends Action
{
    protected $secure = [
        'database',
        'id',
        'file'
    ];

    public function handle()
    {
        $id = $this->getData('id');
        $file = $this->getData('file');
        $database = $this->getData('database', true);

        if (empty($id)) {
            return;
        }

        $file_model = File::where('id', $id)->first();

        if (empty($file_model)) {
            return;
        }

        if ($this->getOption('base64', false)) {
            $file = Action::build(Base64Decode::class)->data([
                'data' => $file,
            ])->run()->getData('data');
        }

        $file_data = [];

        if (!$database) {
            $stored_file = Action::build(AssetStore::class)->data([
                'disk' => $file_model->disk,
                'relative_path' => $file_model->relative_path,
                'data' => $file
            ])->run();

            $relative_path = $stored_file->getData('relative_path');
            $disk = $stored_file->getData('disk');

            if (empty($relative_path)) {
                return;
            }

            $file_data['relative_path'] = $relative_path;
            $file_data['disk'] = $disk;
            $file_data['data'] = null;
        } else {
            $file_data['data'] = $file;
            $file_data['relative_path'] = null;
            $file_data['disk'] = null;
        }

        $file_model->update($file_data);

        return [
            'file' => $file_model
        ];
    }
}
