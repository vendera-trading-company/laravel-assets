<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\File;

use VenderaTradingCompany\LaravelAssets\Actions\AssetStore;
use VenderaTradingCompany\PHPActions\Action;
use VenderaTradingCompany\LaravelAssets\Models\File;

class FileUpdate extends Action
{
    protected $secure = [
        'database',
        'id'
    ];

    public function handle()
    {
        $id = $this->getData('id');
        $url = $this->getData('url');
        $file = $this->getData('file');
        $database = $this->getData('database');

        if (empty($id)) {
            return;
        }

        $file_model = File::where('id', $id)->first();

        if (empty($file_model)) {
            return;
        }

        if (empty($url) && empty($file)) {
            return;
        }

        if (!empty($url)) {
            $file = file_get_contents($url);

            if (empty($file)) {
                return;
            }
        }

        $file_data = [];

        if (!$database) {
            $stored_file = Action::run(AssetStore::class, [
                'disk' => $file_model->disk,
                'relative_path' => $file_model->relative_path,
                'data' => $file
            ]);

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
