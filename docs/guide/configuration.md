# Configuration

CamboAdmin is highly configurable through a single configuration file. This guide explains every option and how to customize CamboAdmin to match your project's needs.

## Configuration File

After installation, the main configuration file is located at:

```
config/cambo-admin.php
```

If you haven't published it yet:

```bash
php artisan vendor:publish --tag=cambo-admin-config
```

## Module Configuration

Control which modules are enabled in your application:

```php
'modules' => [
    'auth' => true,           // Authentication (login, register, 2FA)
    'users' => true,          // User management CRUD
    'roles' => true,          // Role management (requires auth)
    'permissions' => true,    // Granular permissions (requires roles)
    'notifications' => true,  // Notification center
    'activity-log' => true,   // Activity logging
    'dashboard' => true,      // Customizable dashboard with widgets
    'media' => true,          // File manager
    'settings' => true,       // Dynamic settings
    'import-export' => true,  // Import/Export functionality
    'i18n' => true,           // Multi-language support
    'themes' => true,         // Theme customization
],
```

### Module Dependencies

Some modules depend on others:

| Module | Depends On |
|--------|------------|
| `users` | `auth` |
| `roles` | `auth` |
| `permissions` | `roles` |
| `notifications` | `auth` |
| `activity-log` | `auth` |
| `dashboard` | `auth` |
| `media` | `auth` |

When you enable a module, its dependencies are automatically enabled.

### Disabling Modules

To disable a module, set it to `false`:

```php
'modules' => [
    'media' => false,         // File manager disabled
    'import-export' => false, // Import/Export disabled
],
```

Disabling a module:
- Removes its routes from the application
- Hides related UI elements
- Skips related migrations during installation

## Model Configuration

Specify custom model classes or use package defaults:

```php
'models' => [
    'user' => null, // null = uses App\Models\User::class
],
```

### Using Custom Models

If you have custom model classes:

```php
'models' => [
    'user' => App\Models\CustomUser::class,
],
```

Your custom User model must:
- Extend `Illuminate\Foundation\Auth\User`
- Use the `HasRoles` trait from CamboAdmin

```php
<?php

namespace App\Models;

use CamboSoftware\CamboAdmin\Models\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomUser extends Authenticatable
{
    use HasRoles;

    // Your custom implementation
}
```

## Route Configuration

Configure how CamboAdmin routes are registered:

```php
'routes' => [
    'enabled' => true,
    'prefix' => 'admin',
    'middleware' => ['web', 'auth', 'verified'],
    'as' => 'cambo.',
],
```

### Route Options

| Option | Type | Description |
|--------|------|-------------|
| `enabled` | bool | Enable/disable automatic route registration |
| `prefix` | string | URL prefix for all admin routes |
| `middleware` | array | Middleware applied to all routes |
| `as` | string | Route name prefix |

### Custom Route Prefix

Change the admin URL from `/admin` to something else:

```php
'routes' => [
    'prefix' => 'dashboard',  // Now: /dashboard/users, /dashboard/roles
],
```

### Custom Middleware

Add or modify middleware:

```php
'routes' => [
    'middleware' => [
        'web',
        'auth',
        'verified',
        'role:admin',          // Custom middleware
        App\Http\Middleware\EnsureUserIsActive::class,
    ],
],
```

### Disabling Automatic Routes

If you want full control over routing:

```php
'routes' => [
    'enabled' => false,
],
```

Then register routes manually in your `routes/web.php`:

```php
use CamboSoftware\CamboAdmin\Http\Controllers\DashboardController;
use CamboSoftware\CamboAdmin\Http\Controllers\UserController;

Route::middleware(['web', 'auth'])->prefix('my-admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    // ... other routes
});
```

## Appearance Configuration

Customize the look and feel of your admin panel:

```php
'appearance' => [
    'name' => env('APP_NAME', 'CamboAdmin'),
    'logo' => null,
    'favicon' => null,
    'primary_color' => '#6366f1',
    'dark_mode' => 'auto', // 'light', 'dark', 'auto'
],
```

### Appearance Options

| Option | Type | Description |
|--------|------|-------------|
| `name` | string | Application name shown in the header |
| `logo` | string\|null | Path to logo image (relative to public) |
| `favicon` | string\|null | Path to favicon |
| `primary_color` | string | Primary brand color (hex) |
| `dark_mode` | string | Dark mode behavior |

### Custom Branding

```php
'appearance' => [
    'name' => 'My Company Admin',
    'logo' => '/images/my-logo.svg',
    'favicon' => '/images/my-favicon.ico',
    'primary_color' => '#3B82F6', // Blue theme
],
```

### Dark Mode Options

```php
'dark_mode' => 'auto',   // Follow system preference
'dark_mode' => 'light',  // Always light mode
'dark_mode' => 'dark',   // Always dark mode
```

## Feature Configuration

Toggle specific features within modules:

```php
'features' => [
    'registration' => true,
    'password_reset' => true,
    'two_factor' => true,
    'email_verification' => true,
    'remember_me' => true,
    'social_login' => false,
],
```

### Feature Options

| Feature | Description | Default |
|---------|-------------|---------|
| `registration` | Allow new user registration | `true` |
| `password_reset` | Enable "forgot password" flow | `true` |
| `two_factor` | Enable two-factor authentication | `true` |
| `email_verification` | Require email verification | `true` |
| `remember_me` | Show "remember me" on login | `true` |
| `social_login` | Enable OAuth social login | `false` |

### Disabling Registration

For internal admin panels where users are created manually:

```php
'features' => [
    'registration' => false,
],
```

### Enabling Social Login

To enable social login, you'll also need to configure providers:

```php
'features' => [
    'social_login' => true,
],

'social_providers' => [
    'google' => [
        'enabled' => true,
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    ],
    'github' => [
        'enabled' => true,
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
    ],
],
```

## Sidebar Configuration

Configure the sidebar behavior:

```php
'sidebar' => [
    'collapsible' => true,
    'collapsed_by_default' => true,
    'expand_on_hover' => true,
    'theme' => 'dark', // 'dark', 'light'
],
```

### Sidebar Options

| Option | Type | Description |
|--------|------|-------------|
| `collapsible` | bool | Allow users to collapse the sidebar |
| `collapsed_by_default` | bool | Start with sidebar collapsed |
| `expand_on_hover` | bool | Expand sidebar on mouse hover |
| `theme` | string | Sidebar color theme |

## DataTable Configuration

Default settings for data tables throughout the application:

```php
'datatable' => [
    'per_page_options' => [10, 25, 50, 100],
    'default_per_page' => 25,
    'debounce_search' => 300,
],
```

### DataTable Options

| Option | Type | Description |
|--------|------|-------------|
| `per_page_options` | array | Available "items per page" options |
| `default_per_page` | int | Default items per page |
| `debounce_search` | int | Search debounce delay (ms) |

## Export Configuration

Configure data export options:

```php
'exports' => [
    'csv' => true,
    'excel' => true,
    'pdf' => false,
],
```

### Export Dependencies

| Format | Required Package |
|--------|------------------|
| CSV | Built-in |
| Excel | `maatwebsite/excel` |
| PDF | `barryvdh/laravel-dompdf` |

To enable PDF exports:

```bash
composer require barryvdh/laravel-dompdf
```

Then update config:

```php
'exports' => [
    'pdf' => true,
],
```

## Localization Configuration

Configure supported languages:

```php
'locales' => [
    'en' => ['name' => 'English', 'native' => 'English', 'rtl' => false],
    'fr' => ['name' => 'French', 'native' => 'Francais', 'rtl' => false],
    'es' => ['name' => 'Spanish', 'native' => 'Espanol', 'rtl' => false],
    'de' => ['name' => 'German', 'native' => 'Deutsch', 'rtl' => false],
    'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'rtl' => true],
],
```

### Locale Options

| Key | Description |
|-----|-------------|
| `name` | English name of the language |
| `native` | Native name (shown in language selector) |
| `rtl` | Right-to-left text direction |

### Adding a New Locale

```php
'locales' => [
    // ... existing locales
    'ja' => ['name' => 'Japanese', 'native' => '日本語', 'rtl' => false],
    'zh' => ['name' => 'Chinese', 'native' => '中文', 'rtl' => false],
],
```

## Migration Configuration

Control migration behavior:

```php
'migrations' => [
    'enabled' => true,
    'table_prefix' => '',
],
```

### Migration Options

| Option | Type | Description |
|--------|------|-------------|
| `enabled` | bool | Run package migrations |
| `table_prefix` | string | Prefix for all package tables |

### Using Table Prefix

To avoid table name conflicts:

```php
'migrations' => [
    'table_prefix' => 'cambo_',
],
```

This creates tables like `cambo_roles`, `cambo_permissions`, etc.

## Media Configuration

Configure the file manager:

```php
'media' => [
    'disk' => 'public',
    'max_upload_size' => 10240, // KB (10 MB)
    'allowed_types' => [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
        'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv'],
        'video' => ['mp4', 'avi', 'mov', 'wmv'],
        'audio' => ['mp3', 'wav', 'ogg'],
        'archive' => ['zip', 'rar', '7z', 'tar', 'gz'],
    ],
],
```

### Media Options

| Option | Type | Description |
|--------|------|-------------|
| `disk` | string | Laravel filesystem disk |
| `max_upload_size` | int | Max file size in KB |
| `allowed_types` | array | Allowed file extensions by category |

### Custom Storage Disk

Create a custom disk in `config/filesystems.php`:

```php
'disks' => [
    'media' => [
        'driver' => 'local',
        'root' => storage_path('app/public/media'),
        'url' => env('APP_URL') . '/storage/media',
        'visibility' => 'public',
    ],
],
```

Then configure CamboAdmin:

```php
'media' => [
    'disk' => 'media',
],
```

## Activity Log Configuration

Configure activity logging:

```php
'activity_log' => [
    'enabled' => true,
    'log_name' => 'default',
    'retention_days' => 90, // null = keep forever
],
```

### Activity Log Options

| Option | Type | Description |
|--------|------|-------------|
| `enabled` | bool | Enable/disable logging |
| `log_name` | string | Log channel name |
| `retention_days` | int\|null | Auto-delete logs after days |

### Disabling for Specific Models

To exclude certain models from logging, implement the `DoesNotLogActivity` interface in your model.

## Notification Configuration

Configure the notification system:

```php
'notifications' => [
    'database' => true,
    'email' => true,
    'real_time' => false, // Requires Laravel Echo / Pusher
],
```

### Real-Time Notifications

To enable real-time notifications:

1. Install Laravel Echo and a broadcasting driver
2. Configure `.env`:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
```

3. Enable in config:

```php
'notifications' => [
    'real_time' => true,
],
```

## Environment Variables

Many options can be set via `.env` for different environments:

```env
# Application
APP_NAME="My Admin Panel"

# CamboAdmin
CAMBO_ROUTE_PREFIX=admin
CAMBO_PRIMARY_COLOR=#6366f1
CAMBO_DARK_MODE=auto

# Features
CAMBO_REGISTRATION=true
CAMBO_TWO_FACTOR=true
CAMBO_EMAIL_VERIFICATION=true

# Exports
CAMBO_EXPORT_PDF=false

# Activity Log
CAMBO_ACTIVITY_LOG_RETENTION=90
```

Then reference in config:

```php
'appearance' => [
    'primary_color' => env('CAMBO_PRIMARY_COLOR', '#6366f1'),
    'dark_mode' => env('CAMBO_DARK_MODE', 'auto'),
],

'features' => [
    'registration' => env('CAMBO_REGISTRATION', true),
    'two_factor' => env('CAMBO_TWO_FACTOR', true),
],
```

## Cache Configuration

CamboAdmin uses caching for performance. Configure your preferred cache driver:

```php
// config/cache.php
'default' => env('CACHE_STORE', 'redis'),
```

### Clearing Cache

After changing configuration:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

Or use the combined command:

```bash
php artisan optimize:clear
```

## Security Configuration

### Session Configuration

Ensure secure sessions in `config/session.php`:

```php
'secure' => env('SESSION_SECURE_COOKIE', true),
'same_site' => 'lax',
```

### CSRF Protection

CamboAdmin includes CSRF protection by default. Ensure your Blade layout includes:

```blade
@csrf
```

## Next Steps

Now that you've configured CamboAdmin:

1. **[Quick Start Tutorial](/guide/quick-start)** - Build your first CRUD
2. **[Authentication Features](/guide/features/authentication)** - Set up auth
3. **[Roles & Permissions](/guide/features/roles-permissions)** - Configure RBAC
