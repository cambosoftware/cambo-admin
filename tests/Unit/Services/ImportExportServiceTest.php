<?php

namespace CamboSoftware\CamboAdmin\Tests\Unit\Services;

use CamboSoftware\CamboAdmin\Services\ImportExportService;
use CamboSoftware\CamboAdmin\Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ImportExportServiceTest extends TestCase
{
    protected ImportExportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ImportExportService();

        // Use fake storage for testing
        Storage::fake('local');
    }

    public function test_export_to_csv_creates_file(): void
    {
        $data = collect([
            ['id' => 1, 'name' => 'Product A', 'price' => 10.00],
            ['id' => 2, 'name' => 'Product B', 'price' => 20.00],
        ]);
        $columns = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'price', 'label' => 'Price'],
        ];

        $path = $this->service->exportToCsv($data, $columns);

        $this->assertNotEmpty($path);
        $this->assertStringEndsWith('.csv', $path);
        Storage::disk('local')->assertExists($path);
    }

    public function test_export_to_csv_with_custom_filename(): void
    {
        $data = collect([['id' => 1, 'name' => 'Test']]);
        $columns = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Name'],
        ];

        $path = $this->service->exportToCsv($data, $columns, 'custom-export.csv');

        $this->assertStringContainsString('custom-export', $path);
    }

    public function test_export_csv_contains_headers(): void
    {
        $data = collect([
            ['id' => 1, 'name' => 'John'],
        ]);
        $columns = [
            ['key' => 'id', 'label' => 'User ID'],
            ['key' => 'name', 'label' => 'User Name'],
        ];

        $path = $this->service->exportToCsv($data, $columns);
        $content = Storage::disk('local')->get($path);

        // Remove BOM
        $content = str_replace("\xEF\xBB\xBF", '', $content);

        $this->assertStringContainsString('User ID', $content);
        $this->assertStringContainsString('User Name', $content);
        $this->assertStringContainsString('John', $content);
    }

    public function test_preview_import_returns_limited_rows(): void
    {
        // Create a test CSV file with many rows
        $rows = ["id,name"];
        for ($i = 1; $i <= 20; $i++) {
            $rows[] = "{$i},Name {$i}";
        }
        $csvContent = implode("\n", $rows);
        $path = 'imports/test-preview.csv';
        Storage::disk('local')->put($path, $csvContent);

        $result = $this->service->previewImport($path, 5);

        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('preview', $result);
        $this->assertArrayHasKey('total_rows', $result);
        $this->assertCount(5, $result['preview']);
        $this->assertEquals(['id', 'name'], $result['headers']);
    }

    public function test_import_from_csv_with_mapping(): void
    {
        $csvContent = "id,name,email\n1,John Doe,john@example.com\n2,Jane Doe,jane@example.com";
        $path = 'imports/test-import.csv';
        Storage::disk('local')->put($path, $csvContent);

        $mapping = [
            'user_name' => 'name',
            'user_email' => 'email',
        ];

        $result = $this->service->importFromCsv($path, $mapping);

        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('errors', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals(2, $result['success']);
    }

    public function test_import_validates_rows(): void
    {
        $csvContent = "name,email\n,invalid-email\nJohn,john@example.com";
        $path = 'imports/test-validate.csv';
        Storage::disk('local')->put($path, $csvContent);

        $mapping = [
            'name' => 'name',
            'email' => 'email',
        ];

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
        ];

        $result = $this->service->importFromCsv($path, $mapping, $rules);

        $this->assertEquals(1, $result['success']);
        $this->assertEquals(1, $result['errors']);
        $this->assertNotEmpty($result['error_details']);
    }

    public function test_generate_template_creates_file(): void
    {
        $columns = [
            ['key' => 'name', 'label' => 'Name', 'type' => 'text'],
            ['key' => 'email', 'label' => 'Email', 'type' => 'email'],
            ['key' => 'active', 'label' => 'Active', 'type' => 'boolean'],
        ];

        $path = $this->service->generateTemplate($columns);

        $this->assertNotEmpty($path);
        Storage::disk('local')->assertExists($path);

        $content = Storage::disk('local')->get($path);
        $content = str_replace("\xEF\xBB\xBF", '', $content);

        $this->assertStringContainsString('Name', $content);
        $this->assertStringContainsString('Email', $content);
        $this->assertStringContainsString('Yes/No', $content);
    }
}
