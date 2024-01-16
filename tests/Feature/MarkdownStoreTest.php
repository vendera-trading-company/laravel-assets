<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\File\FileStore;
use VenderaTradingCompany\LaravelAssets\Actions\Markdown\MarkdownStore;
use VenderaTradingCompany\PHPActions\Action;

class MarkdownStoreTest extends TestCase
{
    public function testMarkdownFileCanBeStored()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('laravel_asset_markdowns', 0);
        $this->assertDatabaseCount('laravel_asset_files', 0);

        $markdown = Action::run(MarkdownStore::class, [
            'path' => 'files',
            'raw' => '# Test',
            'formatted' => '<h1>Test</h1>'
        ])->getData('markdown');

        $this->assertNotEmpty($markdown);

        $this->assertDatabaseCount('laravel_asset_markdowns', 1);
        $this->assertDatabaseCount('laravel_asset_files', 2);

        $this->assertTrue(Storage::disk($markdown->formatted->disk)->exists($markdown->formatted->relative_path));
    }

    public function testFileCanBeDeleted()
    {
        Storage::fake('local');

        $this->assertDatabaseCount('laravel_asset_markdowns', 0);
        $this->assertDatabaseCount('laravel_asset_files', 0);

        $markdown = Action::run(MarkdownStore::class, [
            'path' => 'files',
            'raw' => '# Test',
            'formatted' => '<h1>Test</h1>'
        ])->getData('markdown');

        $this->assertNotEmpty($markdown);

        $this->assertDatabaseCount('laravel_asset_markdowns', 1);
        $this->assertDatabaseCount('laravel_asset_files', 2);

        $this->assertTrue(Storage::disk($markdown->formatted->disk)->exists($markdown->formatted->relative_path));

        $markdown->delete();

        $this->assertFalse(Storage::disk($markdown->formatted->disk)->exists($markdown->formatted->relative_path));

        $this->assertDatabaseCount('laravel_asset_markdowns', 0);
        $this->assertDatabaseCount('laravel_asset_files', 0);
    }
}
