<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\File\FileStore;
use VenderaTradingCompany\LaravelAssets\Actions\File\FileUpdate;
use VenderaTradingCompany\PHPActions\Action;

class FileUpdateTest extends TestCase
{
    public function testFileCanBeUpdated()
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

        $this->assertEquals('test_file', $file->content());

        $this->assertNotEmpty($file->relative_path);

        $this->assertTrue(Storage::disk($file->disk)->exists($file->relative_path));

        $file = Action::run(FileUpdate::class, [
            'id' => $file->id,
            'file' => 'test_file_updated',
            'path' => 'files',
            'database' => false,
        ])->getData('file');

        $this->assertEquals('test_file_updated', $file->content());

        $this->assertNotEmpty($file);

        $this->assertDatabaseCount('files', 1);

        $this->assertNotEmpty($file->relative_path);

        $this->assertTrue(Storage::disk($file->disk)->exists($file->relative_path));
    }

    public function testFileCanBeUpdatedInDatabase()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('files', 0);

        $file = Action::run(FileStore::class, [
            'file' => 'test_file',
            'path' => 'files',
        ])->getData('file');

        $this->assertNotEmpty($file);

        $this->assertDatabaseCount('files', 1);

        $this->assertEquals('test_file', $file->content());

        $this->assertEmpty($file->relative_path);

        $file = Action::run(FileUpdate::class, [
            'id' => $file->id,
            'file' => 'test_file_updated',
            'path' => 'files',
        ])->getData('file');

        $this->assertEquals('test_file_updated', $file->content());

        $this->assertNotEmpty($file);

        $this->assertDatabaseCount('files', 1);

        $this->assertEmpty($file->relative_path);
    }
}
