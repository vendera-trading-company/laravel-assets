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
    }

    public function testFileCanBeStoredAsBase64()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('files', 0);

        $response = Action::build(FileStore::class)->data([
            'file' => 'data:text;base64,dGVzdF9maWxl',
            'path' => 'files',
            'database' => false,
        ])->options([
            'base64' => true
        ])->run();

        $file = $response->getData('file');

        $this->assertEquals('test_file', $file->content());

        $this->assertNotEmpty($file);

        $this->assertDatabaseCount('files', 1);

        $this->assertNotEmpty($file->relative_path);

        $this->assertTrue(Storage::disk($file->disk)->exists($file->relative_path));
    }

    public function testFileCanBeStoredInDatabase()
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
    }
}
