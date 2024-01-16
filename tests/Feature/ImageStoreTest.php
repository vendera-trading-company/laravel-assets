<?php

namespace Tests\Feature;

use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\Image\ImageStore;
use VenderaTradingCompany\PHPActions\Action;

class ImageStoreTest extends TestCase
{
    public function testImageCanBeStored()
    {
        $image = Action::run(ImageStore::class, [
            'file' => 'test_file',
            'path' => 'images'
        ])->getData('image');

        $this->assertNotEmpty($image);
    }
}
