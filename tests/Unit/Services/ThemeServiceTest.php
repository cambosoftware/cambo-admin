<?php

namespace CamboSoftware\CamboAdmin\Tests\Unit\Services;

use CamboSoftware\CamboAdmin\Services\ThemeService;
use CamboSoftware\CamboAdmin\Tests\TestCase;

class ThemeServiceTest extends TestCase
{
    protected ThemeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ThemeService();
    }

    public function test_get_themes_returns_default_themes(): void
    {
        $themes = $this->service->getThemes();

        $this->assertIsArray($themes);
        $this->assertArrayHasKey('default', $themes);
        $this->assertArrayHasKey('ocean', $themes);
        $this->assertArrayHasKey('forest', $themes);
    }

    public function test_get_theme_returns_specific_theme(): void
    {
        $theme = $this->service->getTheme('default');

        $this->assertIsArray($theme);
        $this->assertArrayHasKey('name', $theme);
        $this->assertArrayHasKey('variables', $theme);
        $this->assertEquals('Default', $theme['name']);
    }

    public function test_get_theme_returns_null_for_unknown_theme(): void
    {
        $theme = $this->service->getTheme('unknown-theme');

        $this->assertNull($theme);
    }

    public function test_get_current_theme_returns_default_when_no_session(): void
    {
        $theme = $this->service->getCurrentTheme();

        $this->assertIsArray($theme);
        $this->assertArrayHasKey('name', $theme);
    }

    public function test_set_theme_stores_in_session(): void
    {
        $this->service->setTheme('ocean');

        $this->assertEquals('ocean', session('theme'));
    }

    public function test_get_variable_groups_returns_array(): void
    {
        $groups = $this->service->getVariableGroups();

        $this->assertIsArray($groups);
        $this->assertNotEmpty($groups);
    }

    public function test_get_preview_colors_returns_colors(): void
    {
        $theme = $this->service->getTheme('default');
        $colors = $this->service->getPreviewColors($theme);

        $this->assertIsArray($colors);
        $this->assertArrayHasKey('primary', $colors);
        $this->assertArrayHasKey('secondary', $colors);
        $this->assertArrayHasKey('accent', $colors);
    }

    public function test_generate_css_returns_valid_css(): void
    {
        $theme = $this->service->getTheme('default');
        $css = $this->service->generateCss($theme);

        $this->assertIsString($css);
        $this->assertStringContainsString(':root', $css);
        $this->assertStringContainsString('--primary-500', $css);
    }

    public function test_save_custom_theme_adds_theme(): void
    {
        $this->service->saveCustomTheme('my-theme', [
            'name' => 'My Theme',
            'description' => 'A custom theme',
            'variables' => ['primary-500' => '#ff0000'],
        ]);

        $themes = $this->service->getThemes();

        $this->assertArrayHasKey('my-theme', $themes);
        $this->assertTrue($themes['my-theme']['custom'] ?? false);
    }

    public function test_delete_custom_theme_removes_theme(): void
    {
        // First save a custom theme
        $this->service->saveCustomTheme('temp-theme', [
            'name' => 'Temp Theme',
            'description' => 'Temporary',
            'variables' => [],
        ]);

        // Then delete it
        $this->service->deleteCustomTheme('temp-theme');

        $themes = $this->service->getThemes();
        $this->assertArrayNotHasKey('temp-theme', $themes);
    }
}
