<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FileTest extends TestCase
{
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('files', [
                'id',
                'disk',
                'relative_path',
                'data'
            ])
        );
    }
}
