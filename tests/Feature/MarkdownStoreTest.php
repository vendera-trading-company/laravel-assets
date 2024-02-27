<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\Markdown\MarkdownStore;
use VenderaTradingCompany\PHPActions\Action;

class MarkdownStoreTest extends TestCase
{
    public function testMarkdownFileCanBeStored()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('markdowns', 0);
        $this->assertDatabaseCount('files', 0);

        $markdown = Action::run(MarkdownStore::class, [
            'path' => 'files',
            'raw' => '# Test',
            'formatted' => '<h1>Test</h1>',
            'database' => false,
        ])->getData('markdown');

        $this->assertNotEmpty($markdown);

        $this->assertDatabaseCount('markdowns', 1);
        $this->assertDatabaseCount('files', 2);

        $this->assertNotEmpty($markdown->formatted->path());
        $this->assertNotEmpty($markdown->raw->path());

        $this->assertNotEmpty($markdown->formatted->content());
        $this->assertNotEmpty($markdown->raw->content());

        $this->assertTrue(Storage::disk($markdown->formatted->disk)->exists($markdown->formatted->relative_path));
        $this->assertTrue(Storage::disk($markdown->raw->disk)->exists($markdown->raw->relative_path));
    }

    public function testMarkdownFileCanBeStoredAsData()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('markdowns', 0);
        $this->assertDatabaseCount('files', 0);

        $markdown = Action::run(MarkdownStore::class, [
            'path' => 'files',
            'raw' => '# Test',
            'formatted' => '<h1>Test</h1>',
            'database' => true,
        ])->getData('markdown');

        $this->assertNotEmpty($markdown);

        $this->assertDatabaseCount('markdowns', 1);
        $this->assertDatabaseCount('files', 2);

        $this->assertEquals('<h1>Test</h1>', $markdown->formatted->content());
        $this->assertEquals('# Test', $markdown->raw->content());
    }

    public function testFileCanBeDeleted()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('markdowns', 0);
        $this->assertDatabaseCount('files', 0);

        $markdown = Action::run(MarkdownStore::class, [
            'path' => 'files',
            'raw' => '# Test',
            'formatted' => '<h1>Test</h1>',
            'database' => false,
        ])->getData('markdown');

        $this->assertNotEmpty($markdown);

        $this->assertDatabaseCount('markdowns', 1);
        $this->assertDatabaseCount('files', 2);

        $this->assertTrue(Storage::disk($markdown->formatted->disk)->exists($markdown->formatted->relative_path));

        $markdown->delete();

        $this->assertFalse(Storage::disk($markdown->formatted->disk)->exists($markdown->formatted->relative_path));

        $this->assertDatabaseCount('markdowns', 0);
        $this->assertDatabaseCount('files', 0);
    }
}
