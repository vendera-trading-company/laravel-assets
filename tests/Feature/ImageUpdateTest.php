<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\Image\ImageStore;
use VenderaTradingCompany\LaravelAssets\Actions\Image\ImageUpdate;
use VenderaTradingCompany\PHPActions\Action;

class ImageUpdateTest extends TestCase
{
    public function testImageCanBeUpdated()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('images', 0);

        $image = Action::run(ImageStore::class, [
            'file' => 'test_file',
            'path' => 'images'
        ])->getData('image');

        $this->assertNotEmpty($image);

        $this->assertDatabaseCount('images', 1);

        $this->assertTrue(Storage::disk($image->disk)->exists($image->relative_path));

        $this->assertEmpty($image->data);

        $this->assertEquals('test_file', $image->content());

        $image = Action::run(ImageUpdate::class, [
            'id' => $image->id,
            'file' => 'test_file_updated',
        ])->getData('image');

        $this->assertDatabaseCount('images', 1);

        $this->assertEquals('test_file_updated', $image->content());
    }

    public function testImageCanBeUpdatedAsData()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('images', 0);

        $image = Action::run(ImageStore::class, [
            'file' => 'test_file',
            'path' => 'images',
            'database' => true,
        ])->getData('image');

        $this->assertNotEmpty($image);

        $this->assertDatabaseCount('images', 1);

        $this->assertEmpty($image->relative_path);

        $this->assertNotEmpty($image->data);

        $this->assertEquals('test_file', $image->content());

        $image = Action::run(ImageUpdate::class, [
            'id' => $image->id,
            'file' => 'test_file_updated',
            'database' => true,
        ])->getData('image');

        $this->assertDatabaseCount('images', 1);

        $this->assertEquals('test_file_updated', $image->content());
    }
}
