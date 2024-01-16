<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FileTest extends TestCase
{
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('laravel_asset_files', [
                'id',
                'disk',
                'relative_path',
                'absolute_path',
            ])
        );
    }
}
