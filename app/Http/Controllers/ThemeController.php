<?php

namespace App\Http\Controllers;

use App\Services\ThemeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ThemeController extends Controller
{
    protected ThemeService $service;

    public function __construct(ThemeService $service)
    {
        $this->service = $service;
    }

    /**
     * Theme customization page
     */
    public function index()
    {
        $themes = $this->service->getThemes();
        $currentTheme = $this->service->getCurrentTheme();
        $variableGroups = $this->service->getVariableGroups();

        // Add preview colors to each theme
        foreach ($themes as $key => &$theme) {
            $theme['key'] = $key;
            $theme['previewColors'] = $this->service->getPreviewColors($theme);
        }

        return Inertia::render('Theme/Index', [
            'themes' => $themes,
            'currentTheme' => $currentTheme,
            'currentThemeKey' => session('theme', 'default'),
            'variableGroups' => $variableGroups,
        ]);
    }

    /**
     * Switch to a theme
     */
    public function switch(Request $request)
    {
        $request->validate([
            'theme' => 'required|string|max:50',
        ]);

        $this->service->setTheme($request->theme);

        return back()->with('success', 'Theme changed successfully.');
    }

    /**
     * Save customized theme
     */
    public function customize(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'base' => 'required|string|max:50',
            'variables' => 'required|array',
        ]);

        // Get base theme
        $baseTheme = $this->service->getTheme($request->base);

        if (!$baseTheme) {
            return back()->withErrors(['base' => 'Base theme not found.']);
        }

        // Merge variables
        $variables = array_merge(
            $baseTheme['variables'] ?? [],
            $request->variables
        );

        // Save custom theme
        $this->service->saveCustomTheme($request->name, [
            'name' => $request->name,
            'description' => 'Custom theme based on ' . $baseTheme['name'],
            'variables' => $variables,
            'darkMode' => $baseTheme['darkMode'] ?? true,
            'basedOn' => $request->base,
        ]);

        // Set as current theme
        $this->service->setTheme($request->name);

        return back()->with('success', 'Custom theme saved.');
    }

    /**
     * Delete custom theme
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'theme' => 'required|string|max:50',
        ]);

        // Check if it's a custom theme
        $themes = $this->service->getThemes();
        $theme = $themes[$request->theme] ?? null;

        if (!$theme || !($theme['custom'] ?? false)) {
            return back()->withErrors(['theme' => 'Cannot delete default themes.']);
        }

        $this->service->deleteCustomTheme($request->theme);

        // Switch to default theme if current was deleted
        if (session('theme') === $request->theme) {
            $this->service->setTheme('default');
        }

        return back()->with('success', 'Custom theme deleted.');
    }

    /**
     * Get theme CSS
     */
    public function css(Request $request)
    {
        $themeName = $request->get('theme', session('theme', 'default'));
        $theme = $this->service->getTheme($themeName);

        if (!$theme) {
            $theme = $this->service->getTheme('default');
        }

        $css = $this->service->generateCss($theme);

        return response($css, 200, [
            'Content-Type' => 'text/css',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Preview theme
     */
    public function preview(Request $request)
    {
        $variables = $request->get('variables', []);

        // Generate CSS with provided variables
        $css = ":root {\n";
        foreach ($variables as $name => $value) {
            $css .= "    --{$name}: {$value};\n";
        }
        $css .= "}\n";

        return response()->json([
            'css' => $css,
        ]);
    }
}
