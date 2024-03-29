<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Pdf;

use VenderaTradingCompany\LaravelAssets\Models\Pdf;
use VenderaTradingCompany\PHPActions\Action;
use Illuminate\Support\Str;
use VenderaTradingCompany\LaravelAssets\Actions\Markdown\MarkdownStore;

/**
 * @data string $database
 * @data string $header_raw
 * @data string $header_formatted
 * @data string $main_raw
 * @data string $main_formatted
 * @data string $footer_raw
 * @data string $footer_formatted
 * @data array $meta
 * @response Pdf $pdf
 */
class PdfStore extends Action
{
    protected $secure = [
        'database',
        'header_raw',
        'header_formatted',
        'main_raw',
        'main_formatted',
        'footer_raw',
        'footer_formatted',
        'meta'
    ];

    public function handle()
    {
        $database = $this->getData('database', true);
        $header_raw = $this->getData('header_raw');
        $header_formatted = $this->getData('header_formatted');
        $main_raw = $this->getData('main_raw');
        $main_formatted = $this->getData('main_formatted');
        $footer_raw = $this->getData('footer_raw');
        $footer_formatted = $this->getData('footer_formatted');
        $meta = $this->getData('meta');

        $header = null;
        $main = null;
        $footer = null;

        $file_id = now()->timestamp . '_' . strtolower(Str::random(32)) . '_pdf_markdown';

        if (!empty($header_raw) && !empty($header_formatted)) {
            $header = Action::build(MarkdownStore::class)->data([
                'raw' => $header_raw,
                'formatted' => $header_formatted,
                'database' => $database,
                'id' => $file_id . '_header',
            ])->run()->getData('markdown');

            if (empty($header)) {
                return;
            }
        }

        if (!empty($main_raw) && !empty($main_formatted)) {
            $main = Action::build(MarkdownStore::class)->data([
                'raw' => $main_raw,
                'formatted' => $main_formatted,
                'database' => $database,
                'id' => $file_id . '_main',
            ])->run()->getData('markdown');

            if (empty($main)) {
                return;
            }
        }

        if (!empty($footer_raw) && !empty($footer_formatted)) {
            $footer = Action::build(MarkdownStore::class)->data([
                'raw' => $footer_raw,
                'formatted' => $footer_formatted,
                'database' => $database,
                'id' => $file_id . '_footer',
            ])->run()->getData('markdown');

            if (empty($footer)) {
                return;
            }
        }

        $id = $this->getData('id', now()->timestamp . '_' . strtolower(Str::random(32)) . '_pdf');

        $pdf = Pdf::create([
            'id' => $id,
            'header_id' => $header?->id,
            'main_id' => $main?->id,
            'footer_id' => $footer?->id,
            'meta' => $meta,
        ]);

        return [
            'pdf' => $pdf
        ];
    }
}
