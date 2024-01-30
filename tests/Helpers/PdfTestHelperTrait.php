<?php

namespace Tests\Helpers;

use VenderaTradingCompany\LaravelAssets\Actions\Pdf\PdfStore;
use VenderaTradingCompany\LaravelAssets\Actions\Pdf\PdfUpdate;
use VenderaTradingCompany\LaravelAssets\Models\Pdf;
use VenderaTradingCompany\PHPActions\Action;

trait PdfTestHelperTrait
{
    public function pdfCreate(array $data = []): Pdf | null
    {
        $pdf = Action::run(PdfStore::class, $data)->getData('pdf');

        return $pdf;
    }

    public function pdfUpdate(Pdf | string $pdf, array $data = []): Pdf | null
    {
        $pdf_id = $pdf;

        if ($pdf instanceof Pdf) {
            $pdf_id = $pdf->id;
        }

        $pdf = Action::run(PdfUpdate::class, array_merge([
            'id' => $pdf_id,
            'database' => true,
        ], $data))->getData('pdf');

        return $pdf;
    }
}
