<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Markdown;

use VenderaTradingCompany\LaravelAssets\Models\Markdown;
use VenderaTradingCompany\PHPActions\Action;

/**
 * @data string $database
 * @data string $disk
 * @data string $path
 * @data string $file
 * @data string $id
 * @data string $raw
 * @data string $formatted
 * @response Markdown $markdown
 */
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
                $markdown = Action::build(MarkdownUpdate::class)->data([
                    'id' => $markdown->id,
                    'raw' => $raw,
                    'formatted' => $formatted,
                    'database' => $database,
                ])->run()->getData('markdown');

                if (!empty($markdown)) {
                    return [
                        'markdown' => $markdown
                    ];
                }
            }
        }

        $markdown = Action::build(MarkdownStore::class)->data([
            'raw' => $raw,
            'formatted' => $formatted,
            'database' => $database,
            'name' => $name,
            'disk' => $disk,
            'path' => $path,
        ])->run()->getData('markdown');

        if (empty($markdown)) {
            return;
        }

        return [
            'markdown' => $markdown
        ];
    }
}
