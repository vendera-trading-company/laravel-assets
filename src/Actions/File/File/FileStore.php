<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\File\File;

use VenderaTradingCompany\LaravelAssets\Actions\File\FileStore as FileFileStore;
use VenderaTradingCompany\PHPActions\Action;

class FileStore extends Action
{
    protected $secure = [
        'disk',
        'path',
        'name'
    ];

    public function handle()
    {
        $file = Action::run(FileFileStore::class)->getData('file');

        if (empty($file)) {
            return response()->json([
                'status' => 'error'
            ]);
        }

        return response()->json([
            'status' => 'done',
            'file' => $file,
            'file_id' => $file->id
        ]);
    }
}
