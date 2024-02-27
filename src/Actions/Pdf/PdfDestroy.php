<?php

namespace VenderaTradingCompany\LaravelAssets\Actions\Pdf;

use VenderaTradingCompany\LaravelAssets\Models\Pdf;
use VenderaTradingCompany\PHPActions\Action;

/**
 * @data string $id
 */
class PdfDestroy extends Action
{
    protected $secure = [
        'id',
    ];

    public function handle()
    {
        $id = $this->getData('id');

        if (empty($id)) {
            return;
        }

        $pdf = Pdf::where('id', $id)->first();

        if (empty($pdf)) {
            return;
        }

        $pdf->delete();
    }
}
