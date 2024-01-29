<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\Pdf\PdfStore;
use VenderaTradingCompany\PHPActions\Action;

class PdfStoreTest extends TestCase
{
    public function testPdfCanBeStored()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('pdfs', 0);

        $pdf = Action::run(PdfStore::class, [
            'header_raw' => '# Test',
            'header_formatted' => '<h1>Header</h1>',
            'main_raw' => '# Test',
            'main_formatted' => '<h1>Test</h1>',
            'footer_raw' => '# Test',
            'footer_formatted' => '<h1>Footer</h1>'
        ])->getData('pdf');

        $this->assertNotEmpty($pdf);

        $this->assertDatabaseCount('pdfs', 1);
    }

    public function testPdfCanBeReadedAsRaw()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('pdfs', 0);

        $pdf = Action::run(PdfStore::class, [
            'header_raw' => '# Test',
            'header_formatted' => '<h1>Header</h1>',
            'main_raw' => '# Test',
            'main_formatted' => '<h1>Test</h1>',
            'footer_raw' => '# Test',
            'footer_formatted' => '<h1>Footer</h1>'
        ])->getData('pdf');

        $this->assertNotEmpty($pdf);

        $expected = '<header># Test</header>' . PHP_EOL . '<main># Test</main>' . PHP_EOL . '<footer># Test</footer>';

        $this->assertEquals($expected, $pdf->raw());

        $this->assertDatabaseCount('pdfs', 1);
    }

    public function testPdfCanBeReadedAsFormatted()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('pdfs', 0);

        $pdf = Action::run(PdfStore::class, [
            'header_raw' => '# Test',
            'header_formatted' => '<h1>Header</h1>',
            'main_raw' => '# Test',
            'main_formatted' => '<h1>Test</h1>',
            'footer_raw' => '# Test',
            'footer_formatted' => '<h1>Footer</h1>'
        ])->getData('pdf');

        $this->assertNotEmpty($pdf);

        $expected = '<header><h1>Header</h1></header>' . PHP_EOL . '<main><h1>Test</h1></main>' . PHP_EOL . '<footer><h1>Footer</h1></footer>';

        $this->assertEquals($expected, $pdf->formatted());

        $this->assertDatabaseCount('pdfs', 1);
    }
}
