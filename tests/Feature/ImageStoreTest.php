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

        $this->assertDatabaseCount('laravel_asset_images', 0);

        $image = Action::run(ImageStore::class, [
            'file' => 'test_file',
            'path' => 'images'
        ])->getData('image');

        $this->assertNotEmpty($image);

        $this->assertDatabaseCount('laravel_asset_images', 1);

        $this->assertTrue(Storage::disk($image->disk)->exists($image->relative_path));
    }

    public function testImageCanBeStoredWithRequest()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('laravel_asset_images', 0);

        $response = $this->post(route('file.image.store'), [
            'file' => 'test_file',
        ]);

        $this->assertDatabaseCount('laravel_asset_images', 1);

        $this->assertNotEmpty($response->json('image'));
    }

    public function testImageCanBeDeleted()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('laravel_asset_images', 0);

        $image = Action::run(ImageStore::class, [
            'file' => 'test_file',
            'path' => 'images'
        ])->getData('image');

        $this->assertNotEmpty($image);

        $this->assertDatabaseCount('laravel_asset_images', 1);

        $this->assertTrue(Storage::disk($image->disk)->exists($image->relative_path));

        $image->delete();

        $this->assertFalse(Storage::disk($image->disk)->exists($image->relative_path));
    }
}
