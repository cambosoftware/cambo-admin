<?php

namespace CamboSoftware\CamboAdmin;

class CamboAdmin
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get a configuration value.
     */
    public function config(string $key, mixed $default = null): mixed
    {
        return data_get($this->config, $key, $default);
    }

    /**
     * Check if a module is enabled.
     */
    public function moduleEnabled(string $module): bool
    {
        return (bool) data_get($this->config, "modules.{$module}", false);
    }

    /**
     * Get all enabled modules.
     */
    public function enabledModules(): array
    {
        return array_keys(array_filter($this->config['modules'] ?? []));
    }

    /**
     * Get the route prefix.
     */
    public function routePrefix(): string
    {
        return $this->config['routes']['prefix'] ?? 'admin';
    }

    /**
     * Get the route middleware.
     */
    public function routeMiddleware(): array
    {
        return $this->config['routes']['middleware'] ?? ['web', 'auth'];
    }

    /**
     * Get the primary color.
     */
    public function primaryColor(): string
    {
        return $this->config['appearance']['primary_color'] ?? '#6366f1';
    }

    /**
     * Get the app name.
     */
    public function appName(): string
    {
        return $this->config['appearance']['name'] ?? config('app.name', 'CamboAdmin');
    }

    /**
     * Check if dark mode is enabled.
     */
    public function darkMode(): string
    {
        return $this->config['appearance']['dark_mode'] ?? 'auto';
    }

    /**
     * Check if a feature is enabled.
     */
    public function featureEnabled(string $feature): bool
    {
        return (bool) data_get($this->config, "features.{$feature}", true);
    }

    /**
     * Get the User model class.
     */
    public function userModel(): string
    {
        return $this->config['models']['user'] ?? \App\Models\User::class;
    }
}
