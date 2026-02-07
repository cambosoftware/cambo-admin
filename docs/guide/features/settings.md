# Dynamic Settings

CamboAdmin includes a flexible settings system for managing application configuration through the UI.

## Overview

The settings module provides:

- Key-value configuration storage
- Settings grouped by category
- Caching for performance
- Type casting (string, boolean, integer, array, JSON)
- UI for managing settings

## Configuration

Enable the settings module:

```php
// config/cambo-admin.php
'modules' => [
    'settings' => true,
],
```

## The Setting Model

### Creating Settings

```php
use CamboSoftware\CamboAdmin\Models\Setting;

// Simple setting
Setting::set('site_name', 'My Application');

// With group
Setting::set('mail_from_address', 'noreply@example.com', 'mail');

// With type
Setting::set('items_per_page', 25, 'general', 'integer');

// Boolean
Setting::set('maintenance_mode', false, 'general', 'boolean');

// Array/JSON
Setting::set('allowed_domains', ['example.com', 'test.com'], 'security', 'array');
```

### Retrieving Settings

```php
// Get a setting
$siteName = Setting::get('site_name');

// With default value
$perPage = Setting::get('items_per_page', 15);

// Get all settings in a group
$mailSettings = Setting::getGroup('mail');

// Get all settings
$allSettings = Setting::all();
```

### Updating Settings

```php
Setting::set('site_name', 'New Name');

// Bulk update
Setting::setMany([
    'site_name' => 'New Name',
    'site_description' => 'New Description',
]);
```

### Deleting Settings

```php
Setting::forget('temporary_setting');
```

## Setting Types

| Type | Description | Example |
|------|-------------|---------|
| `string` | Text values (default) | `'Hello World'` |
| `integer` | Whole numbers | `25` |
| `boolean` | True/false | `true` |
| `array` | PHP arrays | `['a', 'b', 'c']` |
| `json` | JSON objects | `{"key": "value"}` |

## Using the Facade

```php
use CamboSoftware\CamboAdmin\Facades\Cambo;

// Get setting
$value = Cambo::setting('site_name');

// Set setting
Cambo::setting('site_name', 'New Value');
```

## Caching

Settings are automatically cached for performance:

```php
// Clear settings cache
Setting::clearCache();

// Cache is automatically cleared when settings change
```

## Built-in Settings Groups

| Group | Description |
|-------|-------------|
| `general` | Site name, description, timezone |
| `mail` | Email configuration |
| `security` | Security-related settings |
| `appearance` | Theme, colors, branding |
| `social` | Social media links |

## Settings UI

Access the settings page at `/admin/settings`. The UI provides:

- Grouped tabs for organization
- Appropriate input types based on setting type
- Validation and error handling
- Real-time preview for appearance settings

## Example: App Settings Page

```php
// SettingsController.php
public function index()
{
    return Inertia::render('Settings/Index', [
        'settings' => Setting::getGrouped(),
        'groups' => ['general', 'mail', 'appearance', 'security'],
    ]);
}

public function update(Request $request)
{
    $validated = $request->validate([
        'site_name' => 'required|string|max:255',
        'site_description' => 'nullable|string|max:500',
        'items_per_page' => 'required|integer|min:5|max:100',
    ]);

    Setting::setMany($validated);

    return back()->with('success', 'Settings updated successfully.');
}
```

## Using Settings in Views

### Blade

```blade
{{ setting('site_name') }}

@if(setting('maintenance_mode'))
    <div class="alert">Site is in maintenance mode</div>
@endif
```

### Vue

```vue
<script setup>
import { usePage } from '@inertiajs/vue3'

const settings = usePage().props.settings
</script>

<template>
  <h1>{{ settings.site_name }}</h1>
</template>
```

## Environment-Specific Settings

Some settings can be overridden by environment variables:

```php
// config/cambo-admin.php
'settings' => [
    'site_name' => env('APP_NAME', Setting::get('site_name')),
],
```
