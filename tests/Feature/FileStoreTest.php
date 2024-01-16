<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\File\FileStore;
use VenderaTradingCompany\PHPActions\Action;

class FileStoreTest extends TestCase
{
    public function testFileCanBeStored()
    {
        $this->assertDatabaseCount('laravel_asset_files', 0);

        $file = Action::run(FileStore::class, [
            'file' => 'test_file',
            'path' => 'files'
        ])->getData('file');

        $this->assertNotEmpty($file);

        $this->assertDatabaseCount('laravel_asset_files', 1);

        $this->assertTrue(Storage::disk($file->disk)->exists($file->relative_path));
    }

    public function testFileCanBeStoredWithRequest()
    {
        $this->assertDatabaseCount('laravel_asset_files', 0);

        $response = $this->post(route('file.file.store'), [
            'file' => 'test_file',
        ]);

        $this->assertDatabaseCount('laravel_asset_files', 1);

        $this->assertNotEmpty($response->json('file'));
    }

    public function testFileCanBeDeleted()
    {
        $this->assertDatabaseCount('laravel_asset_files', 0);

        $file = Action::run(FileStore::class, [
            'file' => 'test_file',
            'path' => 'files'
        ])->getData('file');

        $this->assertNotEmpty($file);

        $this->assertDatabaseCount('laravel_asset_files', 1);

        $this->assertTrue(Storage::disk($file->disk)->exists($file->relative_path));

        $file->delete();

        $this->assertFalse(Storage::disk($file->disk)->exists($file->relative_path));
    }
}
