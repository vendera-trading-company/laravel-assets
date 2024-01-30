<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Markdown;

use VenderaTradingCompany\LaravelAssets\Models\Markdown;
use VenderaTradingCompany\PHPActions\Action;
use VenderaTradingCompany\LaravelAssets\Actions\File\FileUpdate;
use VenderaTradingCompany\PHPActions\Response;

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
            return Response::error($this, 'empty_id');
        }

        $markdown = Markdown::where('id', $id)->first();

        if (empty($markdown)) {
            return Response::error($this, 'markdown_not_found');
        }

        $raw_file = Action::run(FileUpdate::class, [
            'id' => $markdown->raw_id,
            'file' => $raw,
            'database' => $database
        ])->getData('file');

        if (empty($raw_file)) {
            return Response::error($this, 'raw_update_error');
        }

        $formatted_file = Action::run(FileUpdate::class, [
            'id' => $markdown->formatted_id,
            'file' => $formatted,
            'database' => $database
        ])->getData('file');

        if (empty($formatted_file)) {
            return Response::error($this, 'formatted_update_error');
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
                return Response::error($this, 'raw_mismatch');
            }
        }

        if (!empty($formatted)) {
            if ($formatted != $formatted_file->content()) {
                return Response::error($this, 'formatted_mismatch');
            }
        }

        $markdown->update($markdown_data);

        return [
            'markdown' => $markdown
        ];
    }
}
