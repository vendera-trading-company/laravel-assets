<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Markdown;

use VenderaTradingCompany\LaravelAssets\Models\Markdown;
use VenderaTradingCompany\PHPActions\Action;
use VenderaTradingCompany\LaravelAssets\Actions\File\FileUpdate;

class MarkdownUpdate extends Action
{
    protected $secure = [
        'database',
        'id',
        'raw',
        'formatted'
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

        $raw_file = Action::build(FileUpdate::class)->data([
            'id' => $markdown->raw_id,
            'file' => $raw,
            'database' => $database
        ])->run()->getData('file');

        if (empty($raw_file)) {
            return;
        }

        $formatted_file = Action::build(FileUpdate::class)->data([
            'id' => $markdown->formatted_id,
            'file' => $formatted,
            'database' => $database
        ])->run()->getData('file');

        if (empty($formatted_file)) {
            return;
        }

        $markdown_data = [];

        if ($markdown->raw_id != $raw_file->id) {
            $markdown_data['raw_id'] = $raw_file->id;
        }

        if ($markdown->formatted_id != $formatted_file->id) {
            $markdown_data['formatted_id'] = $formatted_file->id;
        }

        if (!empty($raw)) {
            if ($raw != $raw_file->content()) {
                return;
            }
        }

        if (!empty($formatted)) {
            if ($formatted != $formatted_file->content()) {
                return;
            }
        }

        $markdown->update($markdown_data);

        return [
            'markdown' => $markdown
        ];
    }
}
