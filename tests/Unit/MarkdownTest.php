<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MarkdownTest extends TestCase
{
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('laravel_asset_markdowns', [
                'id',
                'raw_id',
                'formatted_id',
            ])
        );
    }
}
