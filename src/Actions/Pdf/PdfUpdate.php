<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Pdf;

use VenderaTradingCompany\LaravelAssets\Models\Pdf;
use VenderaTradingCompany\PHPActions\Action;
use VenderaTradingCompany\LaravelAssets\Actions\Markdown\MarkdownStore;
use VenderaTradingCompany\LaravelAssets\Actions\Markdown\MarkdownUpdate;

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
        $database = $this->getData('database');
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

        $header = null;
        $main = null;
        $footer = null;

        if (!empty($header_raw) && !empty($header_formatted)) {
            if (!empty($pdf->header_id)) {
                $header = Action::run(MarkdownUpdate::class, [
                    'id' => $pdf->header_id,
                    'raw' => $header_raw,
                    'formatted' => $header_formatted,
                    'database' => $database,
                ])->getData('markdown');

                if (empty($header)) {
                    return;
                }
            } else {
                $header = Action::run(MarkdownStore::class, [
                    'raw' => $header_raw,
                    'formatted' => $header_formatted,
                    'database' => $database,
                ])->getData('markdown');

                if (empty($header)) {
                    return;
                }
            }
        } else {
            $pdf->header?->delete();
        }

        if (!empty($main_raw) && !empty($main_formatted)) {
            if (!empty($pdf->main_id)) {
                $main = Action::run(MarkdownUpdate::class, [
                    'id' => $pdf->main_id,
                    'raw' => $main_raw,
                    'formatted' => $main_formatted,
                    'database' => $database,
                ])->getData('markdown');

                if (empty($main)) {
                    return;
                }
            } else {
                $main = Action::run(MarkdownStore::class, [
                    'raw' => $main_raw,
                    'formatted' => $main_formatted,
                    'database' => $database,
                ])->getData('markdown');

                if (empty($main)) {
                    return;
                }
            }
        } else {
            $pdf->main?->delete();
        }

        if (!empty($footer_raw) && !empty($footer_formatted)) {
            if (!empty($pdf->footer_id)) {
                $footer = Action::run(MarkdownUpdate::class, [
                    'id' => $pdf->footer_id,
                    'raw' => $footer_raw,
                    'formatted' => $footer_formatted,
                    'database' => $database,
                ])->getData('markdown');

                if (empty($footer)) {
                    return;
                }
            } else {
                $footer = Action::run(MarkdownStore::class, [
                    'raw' => $footer_raw,
                    'formatted' => $footer_formatted,
                    'database' => $database,
                ])->getData('markdown');

                if (empty($footer)) {
                    return;
                }
            }
        } else {
            $pdf->footer?->delete();
        }

        $pdf_data = [
            'meta' => $meta,
        ];

        if ($pdf->header_id != $header?->id) {
            $pdf_data['header_id'] = $header?->id;
        }

        if ($pdf->main_id != $main?->id) {
            $pdf_data['main_id'] = $main?->id;
        }

        if ($pdf->footer_id != $footer?->id) {
            $pdf_data['footer_id'] = $footer?->id;
        }

        if (!empty($header_raw)) {
            if ($header_raw != $header?->raw?->content()) {
                return;
            }
        }

        if (!empty($main_raw)) {
            if ($main_raw != $main?->raw?->content()) {
                return;
            }
        }

        if (!empty($footer_raw)) {
            if ($footer_raw != $footer?->raw?->content()) {
                return;
            }
        }

        $pdf->update($pdf_data);

        return [
            'pdf' => $pdf
        ];
    }
}
