<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Markdown;

use VenderaTradingCompany\LaravelAssets\Actions\File\FileStore;
use VenderaTradingCompany\LaravelAssets\Models\Markdown;
use VenderaTradingCompany\PHPActions\Action;
use Illuminate\Support\Str;

/**
 * @data string $database
 * @data string $disk
 * @data string $path
 * @data string $file
 * @data string $id
 * @data string $raw
 * @data string $formatted
 */
class MarkdownStore extends Action
{
    protected $secure = [
        'disk',
        'path',
        'name',
        'database',
        'raw',
        'formatted',
        'id'
    ];

    public function handle()
    {
        $disk = $this->getData('disk');
        $path = $this->getData('path');
        $name = $this->getData('name');
        $database = $this->getData('database', true);
        $raw = $this->getData('raw');
        $formatted = $this->getData('formatted');

        $file_id = now()->timestamp . '_' . strtolower(Str::random(32)) . '_markdown';

        $raw_file = Action::build(FileStore::class)->data([
            'disk' => $disk,
            'path' => $path,
            'name' => $name,
            'file' => $raw,
            'database' => $database,
            'id' => $file_id . '_raw',
        ])->run()->getData('file');

        if (empty($raw_file)) {
            return;
        }

        $formatted_file = Action::build(FileStore::class)->data([
            'disk' => $disk,
            'path' => $path,
            'name' => $name,
            'file' => $formatted,
            'database' => $database,
            'id' => $file_id . '_formatted',
        ])->run()->getData('file');

        if (empty($formatted_file)) {
            return;
        }

        $id = $this->getData('id', now()->timestamp . '_' . strtolower(Str::random(32)) . '_markdown');

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
