<?php

namespace CamboSoftware\CamboAdmin\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ThemeService
{
    protected array $defaultThemes;

    public function __construct()
    {
        $this->defaultThemes = $this->getDefaultThemes();
    }

    /**
     * Get all available themes
     */
    public function getThemes(): array
    {
        $themes = $this->defaultThemes;

        // Load custom themes from database or file
        $customThemes = $this->getCustomThemes();

        return array_merge($themes, $customThemes);
    }

    /**
     * Get default themes
     */
    protected function getDefaultThemes(): array
    {
        return [
            'default' => [
                'name' => 'Default',
                'description' => 'Clean and modern default theme',
                'preview' => '/themes/default-preview.png',
                'variables' => [
                    // Primary colors (Indigo)
                    'primary-50' => '#eef2ff',
                    'primary-100' => '#e0e7ff',
                    'primary-200' => '#c7d2fe',
                    'primary-300' => '#a5b4fc',
                    'primary-400' => '#818cf8',
                    'primary-500' => '#6366f1',
                    'primary-600' => '#4f46e5',
                    'primary-700' => '#4338ca',
                    'primary-800' => '#3730a3',
                    'primary-900' => '#312e81',

                    // Secondary colors (Gray)
                    'secondary-50' => '#f9fafb',
                    'secondary-100' => '#f3f4f6',
                    'secondary-200' => '#e5e7eb',
                    'secondary-300' => '#d1d5db',
                    'secondary-400' => '#9ca3af',
                    'secondary-500' => '#6b7280',
                    'secondary-600' => '#4b5563',
                    'secondary-700' => '#374151',
                    'secondary-800' => '#1f2937',
                    'secondary-900' => '#111827',

                    // Accent (Teal)
                    'accent-500' => '#14b8a6',

                    // Layout
                    'sidebar-width' => '256px',
                    'sidebar-collapsed-width' => '64px',
                    'navbar-height' => '64px',
                    'border-radius' => '0.5rem',
                    'border-radius-lg' => '0.75rem',

                    // Typography
                    'font-family' => 'Inter, system-ui, sans-serif',
                    'font-size-base' => '0.875rem',
                    'font-size-sm' => '0.75rem',
                    'font-size-lg' => '1rem',
                    'font-size-xl' => '1.25rem',
                    'font-size-2xl' => '1.5rem',

                    // Spacing
                    'spacing-xs' => '0.25rem',
                    'spacing-sm' => '0.5rem',
                    'spacing-md' => '1rem',
                    'spacing-lg' => '1.5rem',
                    'spacing-xl' => '2rem',
                ],
                'darkMode' => true,
            ],

            'ocean' => [
                'name' => 'Ocean',
                'description' => 'Cool blue tones for a calm interface',
                'preview' => '/themes/ocean-preview.png',
                'variables' => [
                    // Primary colors (Blue)
                    'primary-50' => '#eff6ff',
                    'primary-100' => '#dbeafe',
                    'primary-200' => '#bfdbfe',
                    'primary-300' => '#93c5fd',
                    'primary-400' => '#60a5fa',
                    'primary-500' => '#3b82f6',
                    'primary-600' => '#2563eb',
                    'primary-700' => '#1d4ed8',
                    'primary-800' => '#1e40af',
                    'primary-900' => '#1e3a8a',

                    // Secondary colors (Slate)
                    'secondary-50' => '#f8fafc',
                    'secondary-100' => '#f1f5f9',
                    'secondary-200' => '#e2e8f0',
                    'secondary-300' => '#cbd5e1',
                    'secondary-400' => '#94a3b8',
                    'secondary-500' => '#64748b',
                    'secondary-600' => '#475569',
                    'secondary-700' => '#334155',
                    'secondary-800' => '#1e293b',
                    'secondary-900' => '#0f172a',

                    // Accent (Cyan)
                    'accent-500' => '#06b6d4',

                    // Layout
                    'sidebar-width' => '256px',
                    'sidebar-collapsed-width' => '64px',
                    'navbar-height' => '64px',
                    'border-radius' => '0.5rem',
                    'border-radius-lg' => '0.75rem',

                    // Typography
                    'font-family' => 'Inter, system-ui, sans-serif',
                    'font-size-base' => '0.875rem',
                    'font-size-sm' => '0.75rem',
                    'font-size-lg' => '1rem',
                    'font-size-xl' => '1.25rem',
                    'font-size-2xl' => '1.5rem',

                    // Spacing
                    'spacing-xs' => '0.25rem',
                    'spacing-sm' => '0.5rem',
                    'spacing-md' => '1rem',
                    'spacing-lg' => '1.5rem',
                    'spacing-xl' => '2rem',
                ],
                'darkMode' => true,
            ],

            'forest' => [
                'name' => 'Forest',
                'description' => 'Natural green tones for an organic feel',
                'preview' => '/themes/forest-preview.png',
                'variables' => [
                    // Primary colors (Emerald)
                    'primary-50' => '#ecfdf5',
                    'primary-100' => '#d1fae5',
                    'primary-200' => '#a7f3d0',
                    'primary-300' => '#6ee7b7',
                    'primary-400' => '#34d399',
                    'primary-500' => '#10b981',
                    'primary-600' => '#059669',
                    'primary-700' => '#047857',
                    'primary-800' => '#065f46',
                    'primary-900' => '#064e3b',

                    // Secondary colors (Stone)
                    'secondary-50' => '#fafaf9',
                    'secondary-100' => '#f5f5f4',
                    'secondary-200' => '#e7e5e4',
                    'secondary-300' => '#d6d3d1',
                    'secondary-400' => '#a8a29e',
                    'secondary-500' => '#78716c',
                    'secondary-600' => '#57534e',
                    'secondary-700' => '#44403c',
                    'secondary-800' => '#292524',
                    'secondary-900' => '#1c1917',

                    // Accent (Lime)
                    'accent-500' => '#84cc16',

                    // Layout
                    'sidebar-width' => '256px',
                    'sidebar-collapsed-width' => '64px',
                    'navbar-height' => '64px',
                    'border-radius' => '0.75rem',
                    'border-radius-lg' => '1rem',

                    // Typography
                    'font-family' => 'Inter, system-ui, sans-serif',
                    'font-size-base' => '0.875rem',
                    'font-size-sm' => '0.75rem',
                    'font-size-lg' => '1rem',
                    'font-size-xl' => '1.25rem',
                    'font-size-2xl' => '1.5rem',

                    // Spacing
                    'spacing-xs' => '0.25rem',
                    'spacing-sm' => '0.5rem',
                    'spacing-md' => '1rem',
                    'spacing-lg' => '1.5rem',
                    'spacing-xl' => '2rem',
                ],
                'darkMode' => true,
            ],
        ];
    }

    /**
     * Get custom themes from storage
     */
    protected function getCustomThemes(): array
    {
        $path = storage_path('app/themes.json');

        if (!File::exists($path)) {
            return [];
        }

        $content = File::get($path);
        return json_decode($content, true) ?? [];
    }

    /**
     * Get current theme
     */
    public function getCurrentTheme(): array
    {
        $themeName = session('theme', config('app.theme', 'default'));
        $themes = $this->getThemes();

        return $themes[$themeName] ?? $themes['default'];
    }

    /**
     * Get theme by name
     */
    public function getTheme(string $name): ?array
    {
        $themes = $this->getThemes();
        return $themes[$name] ?? null;
    }

    /**
     * Set current theme
     */
    public function setTheme(string $themeName): void
    {
        session(['theme' => $themeName]);
    }

    /**
     * Save custom theme
     */
    public function saveCustomTheme(string $name, array $theme): bool
    {
        $path = storage_path('app/themes.json');
        $themes = $this->getCustomThemes();

        $themes[$name] = array_merge([
            'name' => $name,
            'description' => 'Custom theme',
            'custom' => true,
        ], $theme);

        File::put($path, json_encode($themes, JSON_PRETTY_PRINT));

        return true;
    }

    /**
     * Delete custom theme
     */
    public function deleteCustomTheme(string $name): bool
    {
        $path = storage_path('app/themes.json');
        $themes = $this->getCustomThemes();

        if (!isset($themes[$name])) {
            return false;
        }

        unset($themes[$name]);
        File::put($path, json_encode($themes, JSON_PRETTY_PRINT));

        return true;
    }

    /**
     * Generate CSS from theme variables
     */
    public function generateCss(array $theme): string
    {
        $variables = $theme['variables'] ?? [];
        $css = ":root {\n";

        foreach ($variables as $name => $value) {
            $css .= "    --{$name}: {$value};\n";
        }

        $css .= "}\n";

        return $css;
    }

    /**
     * Get theme preview colors
     */
    public function getPreviewColors(array $theme): array
    {
        $vars = $theme['variables'] ?? [];

        return [
            'primary' => $vars['primary-500'] ?? '#6366f1',
            'secondary' => $vars['secondary-500'] ?? '#6b7280',
            'accent' => $vars['accent-500'] ?? '#14b8a6',
            'background' => $vars['secondary-50'] ?? '#f9fafb',
            'surface' => $vars['secondary-100'] ?? '#f3f4f6',
            'text' => $vars['secondary-900'] ?? '#111827',
        ];
    }

    /**
     * Get customizable variables groups
     */
    public function getVariableGroups(): array
    {
        return [
            'colors' => [
                'label' => 'Couleurs',
                'variables' => [
                    'primary-500' => ['label' => 'Couleur principale', 'type' => 'color'],
                    'primary-600' => ['label' => 'Couleur principale (hover)', 'type' => 'color'],
                    'secondary-500' => ['label' => 'Couleur secondaire', 'type' => 'color'],
                    'accent-500' => ['label' => 'Couleur d\'accent', 'type' => 'color'],
                ],
            ],
            'typography' => [
                'label' => 'Typographie',
                'variables' => [
                    'font-family' => [
                        'label' => 'Police',
                        'type' => 'select',
                        'options' => [
                            'Inter, system-ui, sans-serif' => 'Inter',
                            'Poppins, system-ui, sans-serif' => 'Poppins',
                            'Roboto, system-ui, sans-serif' => 'Roboto',
                            'Open Sans, system-ui, sans-serif' => 'Open Sans',
                            'system-ui, sans-serif' => 'System',
                        ],
                    ],
                    'font-size-base' => [
                        'label' => 'Taille de base',
                        'type' => 'select',
                        'options' => [
                            '0.75rem' => 'Petit',
                            '0.875rem' => 'Normal',
                            '1rem' => 'Grand',
                        ],
                    ],
                ],
            ],
            'layout' => [
                'label' => 'Mise en page',
                'variables' => [
                    'sidebar-width' => [
                        'label' => 'Largeur sidebar',
                        'type' => 'select',
                        'options' => [
                            '224px' => 'Compacte',
                            '256px' => 'Normal',
                            '288px' => 'Large',
                        ],
                    ],
                    'border-radius' => [
                        'label' => 'Arrondis',
                        'type' => 'select',
                        'options' => [
                            '0' => 'Aucun',
                            '0.25rem' => 'Léger',
                            '0.5rem' => 'Normal',
                            '0.75rem' => 'Prononcé',
                            '1rem' => 'Très prononcé',
                        ],
                    ],
                ],
            ],
        ];
    }
}
