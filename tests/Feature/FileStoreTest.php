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
        Storage::fake('local');

        $this->assertDatabaseCount('laravel_asset_files', 0);

        $file = Action::run(FileStore::class, [
            'file' => 'test_file',
            'path' => 'files'
        ])->getData('file');

        $this->assertNotEmpty($file);

        $this->assertDatabaseCount('laravel_asset_files', 1);

        $this->assertTrue(Storage::disk($file->disk)->exists($file->relative_path));
    }

    public function testFileCanBeDeleted()
    {
        Storage::fake('local');

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

        $this->assertDatabaseCount('laravel_asset_files', 0);
    }
}
