<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Markdown;

use VenderaTradingCompany\LaravelAssets\Actions\File\FileStore;
use VenderaTradingCompany\LaravelAssets\Models\Markdown;
use VenderaTradingCompany\PHPActions\Action;
use Illuminate\Support\Str;

class MarkdownStore extends Action
{
    protected $secure = [
        'disk',
        'path',
        'name',
        'database'
    ];

    public function handle()
    {
        $disk = $this->getData('disk');
        $path = $this->getData('path');
        $name = $this->getData('name');
        $database = $this->getData('database', true);
        $raw = $this->getData('raw');
        $formatted = $this->getData('formatted');

        $raw_file = Action::run(FileStore::class, [
            'disk' => $disk,
            'path' => $path,
            'name' => $name,
            'file' => $raw,
            'database' => $database
        ])->getData('file');

        if (empty($raw_file)) {
            return;
        }

        $formatted_file = Action::run(FileStore::class, [
            'disk' => $disk,
            'path' => $path,
            'name' => $name,
            'file' => $formatted,
            'database' => $database
        ])->getData('file');

        if (empty($formatted_file)) {
            return;
        }

        $id = now()->timestamp . '_' . strtolower(Str::random(32)) . '_markdown';

        $markdown = Markdown::create([
            'id' => $id,
            'raw_id' => $raw_file->id,
            'formatted_id' => $formatted_file->id
        ]);

        return [
            'markdown' => $markdown
        ];
    }
}
