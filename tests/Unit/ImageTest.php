<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ImageTest extends TestCase
{
    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('laravel_asset_images', [
                'id',
                'disk',
                'relative_path',
            ])
        );
    }
}
