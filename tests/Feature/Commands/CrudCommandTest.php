<?php

namespace CamboSoftware\CamboAdmin\Tests\Feature\Commands;

use CamboSoftware\CamboAdmin\Tests\TestCase;
use Illuminate\Support\Facades\File;

class CrudCommandTest extends TestCase
{
    protected string $testModelName = 'TestProduct';

    protected function tearDown(): void
    {
        // Clean up generated files
        $this->cleanupGeneratedFiles();
        parent::tearDown();
    }

    protected function cleanupGeneratedFiles(): void
    {
        // Remove generated model
        $modelPath = app_path("Models/{$this->testModelName}.php");
        if (File::exists($modelPath)) {
            File::delete($modelPath);
        }

        // Remove generated controller
        $controllerPath = app_path("Http/Controllers/{$this->testModelName}Controller.php");
        if (File::exists($controllerPath)) {
            File::delete($controllerPath);
        }

        // Remove generated Vue pages
        $pagesPath = resource_path("js/Pages/TestProducts");
        if (File::isDirectory($pagesPath)) {
            File::deleteDirectory($pagesPath);
        }

        // Remove generated migrations (find by name pattern)
        $migrations = File::glob(database_path('migrations/*_create_test_products_table.php'));
        foreach ($migrations as $migration) {
            File::delete($migration);
        }
    }

    public function test_crud_command_exists(): void
    {
        $this->assertTrue(
            $this->app->make(\Illuminate\Contracts\Console\Kernel::class)
                ->all()['cambo:crud'] !== null
        );
    }

    public function test_crud_command_requires_name_argument(): void
    {
        $this->expectException(\Symfony\Component\Console\Exception\RuntimeException::class);
        $this->artisan('cambo:crud');
    }

    public function test_crud_command_generates_model(): void
    {
        $this->artisan('cambo:crud', [
            'name' => $this->testModelName,
            '--fields' => 'name:string,price:decimal',
            '--force' => true,
        ])->assertSuccessful();

        $modelPath = app_path("Models/{$this->testModelName}.php");
        $this->assertFileExists($modelPath);

        $content = File::get($modelPath);
        $this->assertStringContainsString("class {$this->testModelName}", $content);
        $this->assertStringContainsString("'name'", $content);
        $this->assertStringContainsString("'price'", $content);
    }

    public function test_crud_command_generates_controller(): void
    {
        $this->artisan('cambo:crud', [
            'name' => $this->testModelName,
            '--force' => true,
        ])->assertSuccessful();

        $controllerPath = app_path("Http/Controllers/{$this->testModelName}Controller.php");
        $this->assertFileExists($controllerPath);

        $content = File::get($controllerPath);
        $this->assertStringContainsString("class {$this->testModelName}Controller", $content);
        $this->assertStringContainsString('public function index', $content);
        $this->assertStringContainsString('public function create', $content);
        $this->assertStringContainsString('public function store', $content);
        $this->assertStringContainsString('public function show', $content);
        $this->assertStringContainsString('public function edit', $content);
        $this->assertStringContainsString('public function update', $content);
        $this->assertStringContainsString('public function destroy', $content);
    }

    public function test_crud_command_generates_migration(): void
    {
        $this->artisan('cambo:crud', [
            'name' => $this->testModelName,
            '--fields' => 'name:string,active:boolean',
            '--force' => true,
        ])->assertSuccessful();

        $migrations = File::glob(database_path('migrations/*_create_test_products_table.php'));
        $this->assertNotEmpty($migrations);

        $content = File::get($migrations[0]);
        $this->assertStringContainsString("Schema::create('test_products'", $content);
        $this->assertStringContainsString("\$table->string('name')", $content);
        $this->assertStringContainsString("\$table->boolean('active')", $content);
    }

    public function test_crud_command_generates_vue_pages(): void
    {
        $this->artisan('cambo:crud', [
            'name' => $this->testModelName,
            '--force' => true,
        ])->assertSuccessful();

        $pagesPath = resource_path('js/Pages/TestProducts');

        $this->assertDirectoryExists($pagesPath);
        $this->assertFileExists("{$pagesPath}/Index.vue");
        $this->assertFileExists("{$pagesPath}/Create.vue");
        $this->assertFileExists("{$pagesPath}/Edit.vue");
        $this->assertFileExists("{$pagesPath}/Show.vue");
    }

    public function test_crud_command_with_soft_deletes(): void
    {
        $this->artisan('cambo:crud', [
            'name' => $this->testModelName,
            '--soft-deletes' => true,
            '--force' => true,
        ])->assertSuccessful();

        $modelPath = app_path("Models/{$this->testModelName}.php");
        $content = File::get($modelPath);

        $this->assertStringContainsString('use SoftDeletes', $content);
    }
}
