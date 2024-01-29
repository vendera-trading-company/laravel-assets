<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use VenderaTradingCompany\LaravelAssets\Actions\Markdown\MarkdownStore;
use VenderaTradingCompany\LaravelAssets\Actions\Markdown\MarkdownUpdate;
use VenderaTradingCompany\PHPActions\Action;

class MarkdownUpdateTest extends TestCase
{
    public function testMarkdownFileCanBeUpdated()
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

        $this->assertEquals('# Test', $markdown->raw->content());
        $this->assertEquals('<h1>Test</h1>', $markdown->formatted->content());

        $this->assertDatabaseCount('markdowns', 1);
        $this->assertDatabaseCount('files', 2);

        $this->assertTrue(Storage::disk($markdown->formatted->disk)->exists($markdown->formatted->relative_path));
        $this->assertTrue(Storage::disk($markdown->raw->disk)->exists($markdown->raw->relative_path));


        $markdown = Action::run(MarkdownUpdate::class, [
            'path' => 'files',
            'id' => $markdown->id,
            'raw' => '# Test Updated',
            'formatted' => '<h1>Test Updated</h1>',
            'database' => false,
        ])->getData('markdown');

        $this->assertNotEmpty($markdown);

        $this->assertEquals('# Test Updated', $markdown->raw->content());
        $this->assertEquals('<h1>Test Updated</h1>', $markdown->formatted->content());

        $this->assertDatabaseCount('markdowns', 1);
        $this->assertDatabaseCount('files', 2);

        $this->assertTrue(Storage::disk($markdown->formatted->disk)->exists($markdown->formatted->relative_path));
        $this->assertTrue(Storage::disk($markdown->raw->disk)->exists($markdown->raw->relative_path));
    }
}
