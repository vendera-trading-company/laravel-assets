<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\File\File;

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
        $file = Action::run(FileStore::class)->getData('file');

        return response()->json([
            'file' => $file
        ]);
    }
}
