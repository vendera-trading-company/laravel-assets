<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Pdf;

use VenderaTradingCompany\LaravelAssets\Models\Pdf;
use VenderaTradingCompany\PHPActions\Action;
use VenderaTradingCompany\LaravelAssets\Actions\Markdown\MarkdownUpdateOrStore;

/**
 * @data string $database
 * @data string $id
 * @data string $header_raw
 * @data string $header_formatted
 * @data string $main_raw
 * @data string $main_formatted
 * @data string $footer_raw
 * @data string $footer_formatted
 * @data array $meta
 * @response Pdf $pdf
 */
class PdfUpdate extends Action
{
    protected $secure = [
        'database',
        'id',
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
        $id = $this->getData('id');
        $database = $this->getData('database', true);
        $header_raw = $this->getData('header_raw');
        $header_formatted = $this->getData('header_formatted');
        $main_raw = $this->getData('main_raw');
        $main_formatted = $this->getData('main_formatted');
        $footer_raw = $this->getData('footer_raw');
        $footer_formatted = $this->getData('footer_formatted');
        $meta = $this->getData('meta');

        if (empty($id)) {
            return;
        }

        $pdf = Pdf::where('id', $id)->first();

        if (empty($pdf)) {
            return;
        }

        $pdf_data = [
            'meta' => $meta,
        ];

        $header = null;
        $main = null;
        $footer = null;

        if (!empty($header_raw) && !empty($header_formatted)) {
            $header = Action::build(MarkdownUpdateOrStore::class)->data([
                'id' => $pdf->header_id,
                'raw' => $header_raw,
                'formatted' => $header_formatted,
                'database' => $database,
            ])->run()->getData('markdown');

            if (empty($header)) {
                return;
            }

            if ($pdf->header_id != $header?->id) {
                $pdf_data['header_id'] = $header?->id;
            }

            if ($header_raw != $header?->raw?->content()) {
                return;
            }
        } else {
            $pdf->header?->delete();
            $pdf_data['header_id'] = null;
        }

        if (!empty($main_raw) && !empty($main_formatted)) {
            $main = Action::build(MarkdownUpdateOrStore::class)->data([
                'id' => $pdf->main_id,
                'raw' => $main_raw,
                'formatted' => $main_formatted,
                'database' => $database,
            ])->run()->getData('markdown');

            if (empty($main)) {
                return;
            }

            if ($pdf->main_id != $main?->id) {
                $pdf_data['main_id'] = $main?->id;
            }

            if ($main_raw != $main?->raw?->content()) {
                return;
            }
        } else {
            $pdf->main?->delete();
            $pdf_data['main_id'] = null;
        }

        if (!empty($footer_raw) && !empty($footer_formatted)) {
            $footer = Action::build(MarkdownUpdateOrStore::class)->data([
                'id' => $pdf->footer_id,
                'raw' => $footer_raw,
                'formatted' => $footer_formatted,
                'database' => $database,
            ])->run()->getData('markdown');

            if (empty($footer)) {
                return;
            }

            if ($pdf->footer_id != $footer?->id) {
                $pdf_data['footer_id'] = $footer?->id;
            }

            if ($footer_raw != $footer?->raw?->content()) {
                return;
            }
        } else {
            $pdf->footer?->delete();
            $pdf_data['footer_id'] = null;
        }

        $pdf->update($pdf_data);

        return [
            'pdf' => $pdf
        ];
    }
}
