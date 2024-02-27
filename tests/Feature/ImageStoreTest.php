<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\Image\ImageStore;
use VenderaTradingCompany\PHPActions\Action;

class ImageStoreTest extends TestCase
{
    public function testImageCanBeStored()
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
    }

    public function testImageCanBeStoredAsBase64()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('images', 0);

        $image = Action::build(ImageStore::class)->data([
            'file' => 'test_file',
            'path' => 'images',
        ])->options([
            'base64' => true
        ])->run()->getData('image');

        $this->assertNotEmpty($image);

        $this->assertDatabaseCount('images', 1);

        $this->assertTrue(Storage::disk($image->disk)->exists($image->relative_path));

        $this->assertEmpty($image->data);
    }

    public function testImageCanBeStoredAsData()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('images', 0);

        $image = Action::run(ImageStore::class, [
            'file' => 'test_file',
            'database' => true,
        ])->getData('image');

        $this->assertNotEmpty($image);

        $this->assertDatabaseCount('images', 1);

        $this->assertEquals('test_file', $image->content());

        $this->assertNotEmpty($image->content());
    }

    public function testImageCanBeDeleted()
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

        $image->delete();

        $this->assertFalse(Storage::disk($image->disk)->exists($image->relative_path));

        $this->assertDatabaseCount('images', 0);
    }
}
