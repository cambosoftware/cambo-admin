<?php

namespace CamboSoftware\CamboAdmin\Tests\Unit;

use CamboSoftware\CamboAdmin\CamboAdmin;
use CamboSoftware\CamboAdmin\Tests\TestCase;

class CamboAdminTest extends TestCase
{
    protected CamboAdmin $cambo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cambo = new CamboAdmin([
            'modules' => [
                'auth' => true,
                'users' => true,
                'roles' => false,
            ],
            'routes' => [
                'prefix' => 'dashboard',
                'middleware' => ['web', 'auth', 'verified'],
            ],
            'appearance' => [
                'name' => 'Test App',
                'primary_color' => '#ff0000',
                'dark_mode' => 'dark',
            ],
            'features' => [
                'two_factor' => true,
                'registration' => false,
            ],
            'models' => [
                'user' => 'App\\Models\\CustomUser',
            ],
        ]);
    }

    public function test_config_returns_value(): void
    {
        $this->assertEquals('Test App', $this->cambo->config('appearance.name'));
        $this->assertEquals('#ff0000', $this->cambo->config('appearance.primary_color'));
    }

    public function test_config_returns_default_when_key_not_found(): void
    {
        $this->assertEquals('default', $this->cambo->config('nonexistent.key', 'default'));
    }

    public function test_module_enabled_returns_correct_status(): void
    {
        $this->assertTrue($this->cambo->moduleEnabled('auth'));
        $this->assertTrue($this->cambo->moduleEnabled('users'));
        $this->assertFalse($this->cambo->moduleEnabled('roles'));
    }

    public function test_enabled_modules_returns_only_enabled(): void
    {
        $enabled = $this->cambo->enabledModules();

        $this->assertContains('auth', $enabled);
        $this->assertContains('users', $enabled);
        $this->assertNotContains('roles', $enabled);
    }

    public function test_route_prefix_returns_configured_value(): void
    {
        $this->assertEquals('dashboard', $this->cambo->routePrefix());
    }

    public function test_route_middleware_returns_array(): void
    {
        $middleware = $this->cambo->routeMiddleware();

        $this->assertIsArray($middleware);
        $this->assertContains('web', $middleware);
        $this->assertContains('auth', $middleware);
        $this->assertContains('verified', $middleware);
    }

    public function test_primary_color_returns_configured_color(): void
    {
        $this->assertEquals('#ff0000', $this->cambo->primaryColor());
    }

    public function test_app_name_returns_configured_name(): void
    {
        $this->assertEquals('Test App', $this->cambo->appName());
    }

    public function test_dark_mode_returns_configured_mode(): void
    {
        $this->assertEquals('dark', $this->cambo->darkMode());
    }

    public function test_feature_enabled_returns_correct_status(): void
    {
        $this->assertTrue($this->cambo->featureEnabled('two_factor'));
        $this->assertFalse($this->cambo->featureEnabled('registration'));
    }

    public function test_user_model_returns_configured_model(): void
    {
        $this->assertEquals('App\\Models\\CustomUser', $this->cambo->userModel());
    }
}
