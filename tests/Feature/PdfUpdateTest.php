<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\Pdf\PdfStore;
use VenderaTradingCompany\LaravelAssets\Actions\Pdf\PdfUpdate;
use VenderaTradingCompany\PHPActions\Action;

class PdfUpdateTest extends TestCase
{
    public function testPdfCanBeUpdated()
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

        $this->assertEquals('# Test', $pdf->header->raw->content());
        $this->assertEquals('<h1>Header</h1>', $pdf->header->formatted->content());
        $this->assertEquals('# Test', $pdf->main->raw->content());
        $this->assertEquals('<h1>Test</h1>', $pdf->main->formatted->content());
        $this->assertEquals('# Test', $pdf->footer->raw->content());
        $this->assertEquals('<h1>Footer</h1>', $pdf->footer->formatted->content());

        $this->assertDatabaseCount('pdfs', 1);
        $this->assertDatabaseCount('markdowns', 3);
        $this->assertDatabaseCount('files', 6);

        $pdf = Action::run(PdfUpdate::class, [
            'id' => $pdf->id,
            'header_raw' => '# Test Updated',
            'header_formatted' => '<h1>Header Updated</h1>',
            'main_raw' => '# Test Updated',
            'main_formatted' => '<h1>Test Updated</h1>',
            'footer_raw' => '# Test Updated',
            'footer_formatted' => '<h1>Footer Updated</h1>'
        ])->getData('pdf');

        $this->assertNotEmpty($pdf);

        $this->assertDatabaseCount('pdfs', 1);
        $this->assertDatabaseCount('markdowns', 3);
        $this->assertDatabaseCount('files', 6);

        $this->assertEquals('# Test Updated', $pdf->header->raw->content());
        $this->assertEquals('<h1>Header Updated</h1>', $pdf->header->formatted->content());
        $this->assertEquals('# Test Updated', $pdf->main->raw->content());
        $this->assertEquals('<h1>Test Updated</h1>', $pdf->main->formatted->content());
        $this->assertEquals('# Test Updated', $pdf->footer->raw->content());
        $this->assertEquals('<h1>Footer Updated</h1>', $pdf->footer->formatted->content());
    }

    public function testPdfUpdateOnlyMain()
    {
        $pdf = $this->pdfCreate([
            'main_raw' => 'Test',
            'main_formatted' => '<h1>Test</h1>'
        ]);

        $this->assertDatabaseCount('pdfs', 1);
        $this->assertDatabaseCount('markdowns', 1);
        $this->assertDatabaseCount('files', 2);

        $this->assertEquals('Test', $pdf->main->raw->content());
        $this->assertEquals('<h1>Test</h1>', $pdf->main->formatted->content());

        $pdf = $this->pdfUpdate($pdf, [
            'main_raw' => 'Test Updated',
            'main_formatted' => '<h1>Test Updated</h1>'
        ]);

        $this->assertDatabaseCount('pdfs', 1);
        $this->assertDatabaseCount('markdowns', 1);
        $this->assertDatabaseCount('files', 2);

        $this->assertEquals('Test Updated', $pdf->main->raw->content());
        $this->assertEquals('<h1>Test Updated</h1>', $pdf->main->formatted->content());

        $pdf = $this->pdfUpdate($pdf, [
            'main_raw' => 'Test Updated 1',
            'main_formatted' => '<h1>Test Updated 1</h1>'
        ]);

        $this->assertDatabaseCount('pdfs', 1);
        $this->assertDatabaseCount('markdowns', 1);
        $this->assertDatabaseCount('files', 2);

        $this->assertEquals('Test Updated 1', $pdf->main->raw->content());
        $this->assertEquals('<h1>Test Updated 1</h1>', $pdf->main->formatted->content());
    }
}
