<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Markdown;

use VenderaTradingCompany\LaravelAssets\Models\Markdown;
use VenderaTradingCompany\PHPActions\Action;
use VenderaTradingCompany\LaravelAssets\Actions\File\FileUpdate;

class MarkdownUpdate extends Action
{
    protected $secure = [
        'database',
        'id'
    ];

    public function handle()
    {
        $id = $this->getData('id');
        $database = $this->getData('database', true);
        $raw = $this->getData('raw');
        $formatted = $this->getData('formatted');

        if (empty($id)) {
            return;
        }

        $markdown = Markdown::where('id', $id)->first();

        if (empty($markdown)) {
            return;
        }

        $raw_file = Action::run(FileUpdate::class, [
            'id' => $markdown->raw_id,
            'file' => $raw,
            'database' => $database
        ])->getData('file');

        if (empty($raw_file)) {
            return;
        }

        $formatted_file = Action::run(FileUpdate::class, [
            'id' => $markdown->formatted_id,
            'file' => $formatted,
            'database' => $database
        ])->getData('file');

        if (empty($formatted_file)) {
            return;
        }

        return [
            'markdown' => $markdown
        ];
    }
}
