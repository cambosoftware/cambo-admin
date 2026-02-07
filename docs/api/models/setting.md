# Setting Model

The `Setting` model manages dynamic application settings with support for multiple types, encryption, and caching in CamboAdmin.

## Description

The Setting model provides a flexible key-value storage system for application settings. It supports multiple data types, automatic type casting, value encryption for sensitive data, and automatic cache management.

## Usage

```php
use CamboSoftware\CamboAdmin\Models\Setting;

// Get a setting value
$siteName = Setting::get('site_name', 'Default Site');

// Set a setting value
Setting::set('site_name', 'My Application');
```

## Properties

| Property | Type | Description |
|----------|------|-------------|
| `group` | `string` | Setting group (general, appearance, etc.) |
| `key` | `string` | Unique setting key |
| `label` | `string` | Display label |
| `description` | `string\|null` | Setting description |
| `type` | `string` | Data type (text, boolean, number, etc.) |
| `value` | `mixed` | Current value |
| `default_value` | `mixed` | Default value |
| `options` | `array\|null` | Available options for select types |
| `validation` | `array\|null` | Validation rules |
| `is_public` | `boolean` | Whether exposed to frontend |
| `is_encrypted` | `boolean` | Whether value is encrypted |
| `order` | `integer` | Display order |

## Fillable Attributes

```php
protected $fillable = [
    'group',
    'key',
    'label',
    'description',
    'type',
    'value',
    'default_value',
    'options',
    'validation',
    'is_public',
    'is_encrypted',
    'order',
];
```

## Casts

```php
protected $casts = [
    'options' => 'array',
    'validation' => 'array',
    'is_public' => 'boolean',
    'is_encrypted' => 'boolean',
];
```

## Methods

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `get()` | `string $key, $default` | `mixed` | Get a setting value |
| `set()` | `string $key, $value` | `bool` | Set a setting value |
| `setMany()` | `array $settings` | `void` | Set multiple settings |
| `allGrouped()` | none (static) | `array` | Get all settings grouped |
| `forGroup()` | `string $group` | `Collection` | Get settings for a group |
| `getPublic()` | none (static) | `array` | Get all public settings |
| `clearCache()` | none (static) | `void` | Clear settings cache |
| `define()` | `array $data` (static) | `self` | Define/update a setting |
| `groups()` | none (static) | `array` | Get available groups |

## Accessor

### getTypedValueAttribute

```php
public function getTypedValueAttribute()
```

Returns the value cast to its appropriate type, with decryption if needed.

**Type Conversions:**
- `boolean` - Converts to boolean
- `number`, `integer` - Converts to integer
- `float` - Converts to float
- `json`, `array` - Converts to array
- `multiselect` - Converts to array

## Method Details

### get()

```php
public static function get(string $key, $default = null)
```

Retrieves a setting value by key with automatic type casting.

**Parameters:**
- `$key` - Setting key
- `$default` - Default value if not found

**Example:**

```php
$siteName = Setting::get('site_name', 'My App');
$maintenanceMode = Setting::get('maintenance_mode', false);
$maxUpload = Setting::get('max_upload_size', 10);
```

### set()

```php
public static function set(string $key, $value): bool
```

Sets a setting value and clears cache.

**Parameters:**
- `$key` - Setting key
- `$value` - New value

**Returns:** `true` if successful, `false` if setting not found

**Example:**

```php
Setting::set('site_name', 'New Site Name');
Setting::set('maintenance_mode', true);
```

### setMany()

```php
public static function setMany(array $settings): void
```

Sets multiple settings at once.

**Parameters:**
- `$settings` - Associative array of key => value pairs

**Example:**

```php
Setting::setMany([
    'site_name' => 'My Application',
    'site_description' => 'A great application',
    'maintenance_mode' => false,
]);
```

### allGrouped()

```php
public static function allGrouped(): array
```

Returns all settings grouped by their `group` field. Results are cached for 1 hour.

**Example:**

```php
$settings = Setting::allGrouped();
// Returns:
// [
//     'general' => [...],
//     'appearance' => [...],
//     'email' => [...],
// ]
```

### forGroup()

```php
public static function forGroup(string $group)
```

Returns all settings for a specific group.

**Parameters:**
- `$group` - Group name

**Example:**

```php
$emailSettings = Setting::forGroup('email');
```

### getPublic()

```php
public static function getPublic(): array
```

Returns all settings marked as public (safe to expose to frontend).

**Example:**

```php
$publicSettings = Setting::getPublic();
// Returns: ['site_name' => 'My App', 'theme' => 'default', ...]
```

### clearCache()

```php
public static function clearCache(): void
```

Clears the settings cache. Called automatically when settings are saved.

**Example:**

```php
Setting::clearCache();
```

### define()

```php
public static function define(array $data): self
```

Defines or updates a setting. Uses `updateOrCreate` on the key.

**Example:**

```php
Setting::define([
    'group' => 'general',
    'key' => 'site_name',
    'label' => 'Site Name',
    'description' => 'The name of your site',
    'type' => 'text',
    'default_value' => 'My Application',
    'is_public' => true,
]);
```

### groups()

```php
public static function groups(): array
```

Returns available setting groups with metadata.

**Example:**

```php
$groups = Setting::groups();
// Returns:
// [
//     'general' => ['label' => 'General', 'icon' => 'cog-6-tooth', 'description' => '...'],
//     'appearance' => ['label' => 'Appearance', 'icon' => 'paint-brush', 'description' => '...'],
//     'email' => ['label' => 'Email', 'icon' => 'envelope', 'description' => '...'],
//     'security' => ['label' => 'Security', 'icon' => 'shield-check', 'description' => '...'],
//     'integrations' => ['label' => 'Integrations', 'icon' => 'puzzle-piece', 'description' => '...'],
//     'advanced' => ['label' => 'Advanced', 'icon' => 'wrench-screwdriver', 'description' => '...'],
// ]
```

## Setting Types

| Type | Description | Cast |
|------|-------------|------|
| `text` | Single line text | string |
| `textarea` | Multi-line text | string |
| `number` | Integer number | integer |
| `float` | Decimal number | float |
| `boolean` | True/False toggle | boolean |
| `select` | Single selection | string |
| `multiselect` | Multiple selection | array |
| `json` | JSON data | array |
| `color` | Color picker | string |
| `file` | File upload | string |
| `password` | Encrypted password | string |

## Complete Usage Example

```php
use CamboSoftware\CamboAdmin\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        return response()->json([
            'settings' => Setting::allGrouped(),
            'groups' => Setting::groups(),
        ]);
    }

    public function show(string $group)
    {
        return response()->json([
            'settings' => Setting::forGroup($group),
        ]);
    }

    public function update(Request $request)
    {
        Setting::setMany($request->settings);

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
        ]);
    }

    public function public()
    {
        return response()->json(Setting::getPublic());
    }
}
```

## Seeder Example

```php
use CamboSoftware\CamboAdmin\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            [
                'group' => 'general',
                'key' => 'site_name',
                'label' => 'Site Name',
                'type' => 'text',
                'default_value' => 'My Application',
                'is_public' => true,
                'order' => 1,
            ],
            [
                'group' => 'general',
                'key' => 'site_description',
                'label' => 'Site Description',
                'type' => 'textarea',
                'is_public' => true,
                'order' => 2,
            ],
            [
                'group' => 'general',
                'key' => 'maintenance_mode',
                'label' => 'Maintenance Mode',
                'type' => 'boolean',
                'default_value' => false,
                'order' => 3,
            ],

            // Email
            [
                'group' => 'email',
                'key' => 'smtp_host',
                'label' => 'SMTP Host',
                'type' => 'text',
                'order' => 1,
            ],
            [
                'group' => 'email',
                'key' => 'smtp_password',
                'label' => 'SMTP Password',
                'type' => 'password',
                'is_encrypted' => true,
                'order' => 2,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::define($setting);
        }
    }
}
```

## Database Schema

```php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('group')->index();
    $table->string('key')->unique();
    $table->string('label');
    $table->text('description')->nullable();
    $table->string('type')->default('text');
    $table->text('value')->nullable();
    $table->text('default_value')->nullable();
    $table->json('options')->nullable();
    $table->json('validation')->nullable();
    $table->boolean('is_public')->default(false);
    $table->boolean('is_encrypted')->default(false);
    $table->integer('order')->default(0);
    $table->timestamps();
});
```

## Source Code

**Location:** `src/Models/Setting.php`

**Namespace:** `CamboSoftware\CamboAdmin\Models`

**Cache Key:** `app_settings`
