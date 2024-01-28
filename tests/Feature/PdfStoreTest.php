<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\Pdf\PdfStore;
use VenderaTradingCompany\PHPActions\Action;

class PdfStoreTest extends TestCase
{
    public function testMarkdownFileCanBeStored()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('laravel_asset_pdfs', 0);

        $pdf = Action::run(PdfStore::class, [
            'header_raw' => '# Test',
            'header_formatted' => '<h1>Header</h1>',
            'main_raw' => '# Test',
            'main_formatted' => '<h1>Test</h1>',
            'footer_raw' => '# Test',
            'footer_formatted' => '<h1>Footer</h1>'
        ])->getData('pdf');

        $this->assertNotEmpty($pdf);

        $this->assertDatabaseCount('laravel_asset_pdfs', 1);
    }
}
