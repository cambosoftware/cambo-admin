# ThemeService

The `ThemeService` manages themes, color schemes, and visual customization for CamboAdmin.

## Description

ThemeService provides a complete theme management system with built-in themes (Default, Ocean, Forest) and support for custom themes stored in JSON format. It handles CSS variable generation, theme switching, and customization options.

## Usage

```php
use CamboSoftware\CamboAdmin\Services\ThemeService;

$themeService = app(ThemeService::class);
```

## Methods

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `getThemes()` | none | `array` | Get all available themes |
| `getCurrentTheme()` | none | `array` | Get the current active theme |
| `getTheme()` | `string $name` | `?array` | Get a specific theme by name |
| `setTheme()` | `string $themeName` | `void` | Set the current theme |
| `saveCustomTheme()` | `string $name, array $theme` | `bool` | Save a custom theme |
| `deleteCustomTheme()` | `string $name` | `bool` | Delete a custom theme |
| `generateCss()` | `array $theme` | `string` | Generate CSS from theme variables |
| `getPreviewColors()` | `array $theme` | `array` | Get preview colors for a theme |
| `getVariableGroups()` | none | `array` | Get customizable variable groups |

## Method Details

### getThemes()

```php
public function getThemes(): array
```

Returns all available themes including default and custom themes.

**Example:**

```php
$themes = $themeService->getThemes();
// Returns: ['default' => [...], 'ocean' => [...], 'forest' => [...]]
```

### getCurrentTheme()

```php
public function getCurrentTheme(): array
```

Returns the currently active theme based on session or configuration.

**Example:**

```php
$currentTheme = $themeService->getCurrentTheme();
// Returns theme array with name, description, preview, variables, darkMode
```

### getTheme()

```php
public function getTheme(string $name): ?array
```

Returns a specific theme by name, or null if not found.

**Parameters:**
- `$name` - Theme name (e.g., 'default', 'ocean', 'forest')

**Example:**

```php
$oceanTheme = $themeService->getTheme('ocean');
if ($oceanTheme) {
    // Theme found
}
```

### setTheme()

```php
public function setTheme(string $themeName): void
```

Sets the current theme in the session.

**Parameters:**
- `$themeName` - Theme name to activate

**Example:**

```php
$themeService->setTheme('ocean');
```

### saveCustomTheme()

```php
public function saveCustomTheme(string $name, array $theme): bool
```

Saves a custom theme to storage. Custom themes are stored in `storage/app/themes.json`.

**Parameters:**
- `$name` - Unique theme name
- `$theme` - Theme configuration array

**Example:**

```php
$themeService->saveCustomTheme('my-theme', [
    'name' => 'My Theme',
    'description' => 'A custom theme',
    'variables' => [
        'primary-500' => '#ff6b6b',
        'secondary-500' => '#4ecdc4',
    ],
    'darkMode' => true,
]);
```

### deleteCustomTheme()

```php
public function deleteCustomTheme(string $name): bool
```

Deletes a custom theme from storage.

**Parameters:**
- `$name` - Theme name to delete

**Returns:** `true` if deleted, `false` if theme not found

**Example:**

```php
if ($themeService->deleteCustomTheme('my-theme')) {
    // Theme deleted
}
```

### generateCss()

```php
public function generateCss(array $theme): string
```

Generates CSS custom properties from theme variables.

**Parameters:**
- `$theme` - Theme array with 'variables' key

**Example:**

```php
$theme = $themeService->getTheme('default');
$css = $themeService->generateCss($theme);
// Returns:
// :root {
//     --primary-50: #eef2ff;
//     --primary-100: #e0e7ff;
//     ...
// }
```

### getPreviewColors()

```php
public function getPreviewColors(array $theme): array
```

Returns a simplified array of main colors for theme preview.

**Parameters:**
- `$theme` - Theme array

**Example:**

```php
$colors = $themeService->getPreviewColors($theme);
// Returns:
// [
//     'primary' => '#6366f1',
//     'secondary' => '#6b7280',
//     'accent' => '#14b8a6',
//     'background' => '#f9fafb',
//     'surface' => '#f3f4f6',
//     'text' => '#111827',
// ]
```

### getVariableGroups()

```php
public function getVariableGroups(): array
```

Returns customizable variable groups for the theme editor UI.

**Example:**

```php
$groups = $themeService->getVariableGroups();
// Returns groups for 'colors', 'typography', 'layout'
```

## Built-in Themes

### Default Theme

Clean and modern theme with Indigo primary color:
- Primary: `#6366f1` (Indigo)
- Secondary: `#6b7280` (Gray)
- Accent: `#14b8a6` (Teal)

### Ocean Theme

Cool blue tones for a calm interface:
- Primary: `#3b82f6` (Blue)
- Secondary: `#64748b` (Slate)
- Accent: `#06b6d4` (Cyan)

### Forest Theme

Natural green tones for an organic feel:
- Primary: `#10b981` (Emerald)
- Secondary: `#78716c` (Stone)
- Accent: `#84cc16` (Lime)

## Theme Variables

Each theme contains the following variable categories:

### Colors

```php
// Primary colors (50-900 scale)
'primary-50' => '#eef2ff',
'primary-500' => '#6366f1',
'primary-900' => '#312e81',

// Secondary colors (50-900 scale)
'secondary-50' => '#f9fafb',
'secondary-500' => '#6b7280',
'secondary-900' => '#111827',

// Accent
'accent-500' => '#14b8a6',
```

### Layout

```php
'sidebar-width' => '256px',
'sidebar-collapsed-width' => '64px',
'navbar-height' => '64px',
'border-radius' => '0.5rem',
'border-radius-lg' => '0.75rem',
```

### Typography

```php
'font-family' => 'Inter, system-ui, sans-serif',
'font-size-base' => '0.875rem',
'font-size-sm' => '0.75rem',
'font-size-lg' => '1rem',
'font-size-xl' => '1.25rem',
'font-size-2xl' => '1.5rem',
```

### Spacing

```php
'spacing-xs' => '0.25rem',
'spacing-sm' => '0.5rem',
'spacing-md' => '1rem',
'spacing-lg' => '1.5rem',
'spacing-xl' => '2rem',
```

## Complete Usage Example

```php
use CamboSoftware\CamboAdmin\Services\ThemeService;

class ThemeController extends Controller
{
    public function __construct(
        protected ThemeService $themeService
    ) {}

    public function index()
    {
        return response()->json([
            'themes' => $this->themeService->getThemes(),
            'current' => $this->themeService->getCurrentTheme(),
        ]);
    }

    public function setTheme(Request $request)
    {
        $this->themeService->setTheme($request->theme);
        return back();
    }

    public function createCustom(Request $request)
    {
        $this->themeService->saveCustomTheme(
            $request->name,
            $request->only(['variables', 'description', 'darkMode'])
        );

        return response()->json(['success' => true]);
    }

    public function css()
    {
        $theme = $this->themeService->getCurrentTheme();
        $css = $this->themeService->generateCss($theme);

        return response($css)->header('Content-Type', 'text/css');
    }
}
```

## Source Code

**Location:** `src/Services/ThemeService.php`

**Namespace:** `CamboSoftware\CamboAdmin\Services`
