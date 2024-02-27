<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\File\FileStore;
use VenderaTradingCompany\PHPActions\Action;

class FileDestroyTest extends TestCase
{
    public function testFileCanBeDeleted()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('files', 0);

        $file = Action::run(FileStore::class, [
            'file' => 'test_file',
            'path' => 'files',
            'database' => false,
        ])->getData('file');

        $this->assertNotEmpty($file);

        $this->assertDatabaseCount('files', 1);

        $this->assertNotEmpty($file->relative_path);

        $this->assertTrue(Storage::disk($file->disk)->exists($file->relative_path));

        $file->delete();

        $this->assertFalse(Storage::disk($file->disk)->exists($file->relative_path));

        $this->assertDatabaseCount('files', 0);
    }

    public function testFileCanBeDeletedWhenStoredInDatabase()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('files', 0);

        $file = Action::run(FileStore::class, [
            'file' => 'test_file',
            'path' => 'files',
        ])->getData('file');

        $this->assertNotEmpty($file);

        $this->assertDatabaseCount('files', 1);

        $this->assertEmpty($file->relative_path);

        $file->delete();

        $this->assertDatabaseCount('files', 0);
    }
}
