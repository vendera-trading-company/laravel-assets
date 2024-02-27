<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Pdf;

use VenderaTradingCompany\LaravelAssets\Models\Pdf;
use VenderaTradingCompany\PHPActions\Action;
use Illuminate\Support\Str;
use VenderaTradingCompany\LaravelAssets\Actions\Markdown\MarkdownStore;

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
        $database = $this->getData('database');
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
            $header = Action::run(MarkdownStore::class, [
                'raw' => $header_raw,
                'formatted' => $header_formatted,
                'database' => $database,
                'id' => $file_id . '_header',
            ])->getData('markdown');

            if (empty($header)) {
                return;
            }
        }

        if (!empty($main_raw) && !empty($main_formatted)) {
            $main = Action::run(MarkdownStore::class, [
                'raw' => $main_raw,
                'formatted' => $main_formatted,
                'database' => $database,
                'id' => $file_id . '_main',
            ])->getData('markdown');

            if (empty($main)) {
                return;
            }
        }

        if (!empty($footer_raw) && !empty($footer_formatted)) {
            $footer = Action::run(MarkdownStore::class, [
                'raw' => $footer_raw,
                'formatted' => $footer_formatted,
                'database' => $database,
                'id' => $file_id . '_footer',
            ])->getData('markdown');

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
