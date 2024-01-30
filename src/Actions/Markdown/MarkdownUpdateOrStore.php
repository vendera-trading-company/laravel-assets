<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Markdown;

use VenderaTradingCompany\LaravelAssets\Models\Markdown;
use VenderaTradingCompany\PHPActions\Action;

class MarkdownUpdateOrStore extends Action
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
        $id = $this->getData('id');
        $database = $this->getData('database', true);
        $raw = $this->getData('raw');
        $formatted = $this->getData('formatted');
        $disk = $this->getData('disk');
        $path = $this->getData('path');
        $name = $this->getData('name');

        if (!empty($id)) {
            $markdown = Markdown::where('id', $id)->first();

            if (!empty($markdown)) {
                $markdown = Action::run(MarkdownUpdate::class, [
                    'id' => $markdown->id,
                    'raw' => $raw,
                    'formatted' => $formatted,
                    'database' => $database,
                ])->getData('markdown');

                if (!empty($markdown)) {
                    return [
                        'markdown' => $markdown
                    ];
                }
            }
        }

        $markdown = Action::run(MarkdownStore::class, [
            'raw' => $raw,
            'formatted' => $formatted,
            'database' => $database,
            'name' => $name,
            'disk' => $disk,
            'path' => $path,
        ])->getData('markdown');

        if (empty($markdown)) {
            return;
        }

        return [
            'markdown' => $markdown
        ];
    }
}
