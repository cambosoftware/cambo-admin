<?php

namespace CamboSoftware\CamboAdmin\Tests\Feature\Commands;

use CamboSoftware\CamboAdmin\Tests\TestCase;
use Illuminate\Support\Facades\File;

class PageCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        // Clean up generated files
        $this->cleanupGeneratedFiles();
        parent::tearDown();
    }

    protected function cleanupGeneratedFiles(): void
    {
        $paths = [
            resource_path('js/Pages/TestPage.vue'),
            resource_path('js/Pages/Reports'),
        ];

        foreach ($paths as $path) {
            if (File::isDirectory($path)) {
                File::deleteDirectory($path);
            } elseif (File::exists($path)) {
                File::delete($path);
            }
        }
    }

    public function test_page_command_exists(): void
    {
        $this->assertTrue(
            $this->app->make(\Illuminate\Contracts\Console\Kernel::class)
                ->all()['cambo:page'] !== null
        );
    }

    public function test_page_command_generates_basic_page(): void
    {
        $this->artisan('cambo:page', [
            'name' => 'TestPage',
            '--force' => true,
        ])->assertSuccessful();

        $pagePath = resource_path('js/Pages/TestPage.vue');
        $this->assertFileExists($pagePath);

        $content = File::get($pagePath);
        $this->assertStringContainsString('<script setup>', $content);
        $this->assertStringContainsString('AdminLayout', $content);
        $this->assertStringContainsString('PageHeader', $content);
    }

    public function test_page_command_with_title(): void
    {
        $this->artisan('cambo:page', [
            'name' => 'TestPage',
            '--title' => 'My Custom Title',
            '--force' => true,
        ])->assertSuccessful();

        $content = File::get(resource_path('js/Pages/TestPage.vue'));
        $this->assertStringContainsString('My Custom Title', $content);
    }

    public function test_page_command_with_card_option(): void
    {
        $this->artisan('cambo:page', [
            'name' => 'TestPage',
            '--with-card' => true,
            '--force' => true,
        ])->assertSuccessful();

        $content = File::get(resource_path('js/Pages/TestPage.vue'));
        $this->assertStringContainsString('Card', $content);
        $this->assertStringContainsString('<Card>', $content);
    }

    public function test_page_command_with_form_option(): void
    {
        $this->artisan('cambo:page', [
            'name' => 'TestPage',
            '--with-form' => true,
            '--force' => true,
        ])->assertSuccessful();

        $content = File::get(resource_path('js/Pages/TestPage.vue'));
        $this->assertStringContainsString('Form', $content);
        $this->assertStringContainsString('FormGroup', $content);
        $this->assertStringContainsString('Input', $content);
    }

    public function test_page_command_with_table_option(): void
    {
        $this->artisan('cambo:page', [
            'name' => 'TestPage',
            '--with-table' => true,
            '--force' => true,
        ])->assertSuccessful();

        $content = File::get(resource_path('js/Pages/TestPage.vue'));
        $this->assertStringContainsString('Table', $content);
        $this->assertStringContainsString('TableHead', $content);
        $this->assertStringContainsString('TableBody', $content);
    }

    public function test_page_command_creates_nested_directory(): void
    {
        $this->artisan('cambo:page', [
            'name' => 'Reports/Analytics',
            '--force' => true,
        ])->assertSuccessful();

        $pagePath = resource_path('js/Pages/Reports/Analytics.vue');
        $this->assertFileExists($pagePath);
    }

    public function test_page_command_does_not_overwrite_without_force(): void
    {
        // Create the file first
        $pagePath = resource_path('js/Pages/TestPage.vue');
        File::ensureDirectoryExists(dirname($pagePath));
        File::put($pagePath, 'existing content');

        $this->artisan('cambo:page', [
            'name' => 'TestPage',
        ])->assertFailed();

        // Content should not have changed
        $this->assertEquals('existing content', File::get($pagePath));
    }
}
