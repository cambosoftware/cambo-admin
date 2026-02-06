<?php

namespace CamboSoftware\CamboAdmin\Tests\Feature\Commands;

use CamboSoftware\CamboAdmin\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ComponentCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        $this->cleanupGeneratedFiles();
        parent::tearDown();
    }

    protected function cleanupGeneratedFiles(): void
    {
        $paths = [
            resource_path('js/Components/TestComponent.vue'),
            resource_path('js/Components/Custom'),
        ];

        foreach ($paths as $path) {
            if (File::isDirectory($path)) {
                File::deleteDirectory($path);
            } elseif (File::exists($path)) {
                File::delete($path);
            }
        }
    }

    public function test_component_command_exists(): void
    {
        $this->assertTrue(
            $this->app->make(\Illuminate\Contracts\Console\Kernel::class)
                ->all()['cambo:component'] !== null
        );
    }

    public function test_component_command_generates_basic_component(): void
    {
        $this->artisan('cambo:component', [
            'name' => 'TestComponent',
            '--force' => true,
        ])->assertSuccessful();

        $componentPath = resource_path('js/Components/TestComponent.vue');
        $this->assertFileExists($componentPath);

        $content = File::get($componentPath);
        $this->assertStringContainsString('<script setup>', $content);
        $this->assertStringContainsString('<template>', $content);
        $this->assertStringContainsString('<slot />', $content);
    }

    public function test_component_command_with_category(): void
    {
        $this->artisan('cambo:component', [
            'name' => 'TestComponent',
            '--category' => 'Custom',
            '--force' => true,
        ])->assertSuccessful();

        $componentPath = resource_path('js/Components/Custom/TestComponent.vue');
        $this->assertFileExists($componentPath);
    }

    public function test_component_command_with_props(): void
    {
        $this->artisan('cambo:component', [
            'name' => 'TestComponent',
            '--with-props' => true,
            '--force' => true,
        ])->assertSuccessful();

        $content = File::get(resource_path('js/Components/TestComponent.vue'));
        $this->assertStringContainsString('defineProps', $content);
        $this->assertStringContainsString('variant', $content);
        $this->assertStringContainsString('disabled', $content);
    }

    public function test_component_command_with_emits(): void
    {
        $this->artisan('cambo:component', [
            'name' => 'TestComponent',
            '--with-emits' => true,
            '--force' => true,
        ])->assertSuccessful();

        $content = File::get(resource_path('js/Components/TestComponent.vue'));
        $this->assertStringContainsString('defineEmits', $content);
        $this->assertStringContainsString('emit(', $content);
    }

    public function test_component_command_with_slots(): void
    {
        $this->artisan('cambo:component', [
            'name' => 'TestComponent',
            '--with-slots' => true,
            '--force' => true,
        ])->assertSuccessful();

        $content = File::get(resource_path('js/Components/TestComponent.vue'));
        $this->assertStringContainsString('<slot />', $content);
        $this->assertStringContainsString('$slots.header', $content);
        $this->assertStringContainsString('name="header"', $content);
    }

    public function test_component_command_converts_name_to_studly_case(): void
    {
        $this->artisan('cambo:component', [
            'name' => 'my-custom-component',
            '--force' => true,
        ])->assertSuccessful();

        $componentPath = resource_path('js/Components/MyCustomComponent.vue');
        $this->assertFileExists($componentPath);
    }
}
