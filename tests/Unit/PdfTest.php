<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PdfTest extends TestCase
{
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('pdfs', [
                'id',
                'header_id',
                'main_id',
                'footer_id'
            ])
        );
    }
}
