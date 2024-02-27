<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\File;

use VenderaTradingCompany\PHPActions\Action;
use VenderaTradingCompany\LaravelAssets\Models\File;

/**
 * @data string $id
 */
class FileDestroy extends Action
{
    protected $secure = [
        'id',
    ];

    public function handle()
    {
        $id = $this->getData('id');

        if (empty($id)) {
            return;
        }

        $file = File::where('id', $id)->first();

        if (empty($file)) {
            return;
        }

        $file->delete();
    }
}
