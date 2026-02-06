# Configuration

The main configuration file is `config/cambo-admin.php`.

## Module Configuration

```php
'modules' => [
    'auth' => true,           // Authentication
    'users' => true,          // User management
    'roles' => true,          // Role management
    'permissions' => true,    // Permission management
    'notifications' => true,  // Notifications
    'activity-log' => true,   // Activity log
    'dashboard' => true,      // Dashboard
    'media' => true,          // File manager
    'settings' => true,       // Settings
    'import-export' => true,  // Import/Export
    'i18n' => true,           // Internationalization
    'themes' => true,         // Themes
],
```

## Route Configuration

```php
'routes' => [
    'enabled' => true,
    'prefix' => 'admin',
    'middleware' => ['web', 'auth', 'verified'],
    'as' => 'cambo.',
],
```

## Appearance Configuration

```php
'appearance' => [
    'name' => env('APP_NAME', 'CamboAdmin'),
    'logo' => '/images/logo.svg',
    'favicon' => '/favicon.ico',
    'primary_color' => '#3B82F6',
    'dark_mode' => 'system', // 'light', 'dark', 'system'
],
```

## Feature Configuration

```php
'features' => [
    'two_factor' => true,
    'registration' => true,
    'password_reset' => true,
    'email_verification' => true,
    'remember_me' => true,
    'social_login' => false,
],
```

## Model Configuration

```php
'models' => [
    'user' => App\Models\User::class,
    'role' => CamboSoftware\CamboAdmin\Models\Role::class,
    'permission' => CamboSoftware\CamboAdmin\Models\Permission::class,
],
```

## Migration Configuration

```php
'migrations' => [
    'enabled' => true,
    'table_prefix' => '',
],
```

## Environment Variables

Add these variables to your `.env`:

```env
# Appearance
CAMBO_APP_NAME="My Application"
CAMBO_PRIMARY_COLOR="#3B82F6"
CAMBO_DARK_MODE="system"

# Features
CAMBO_TWO_FACTOR=true
CAMBO_REGISTRATION=true

# Routes
CAMBO_ROUTE_PREFIX="admin"
```

## Locale Configuration

For internationalization, configure supported locales:

```php
// config/app.php
'locale' => 'en',
'fallback_locale' => 'en',
'supported_locales' => ['en', 'fr', 'es', 'ar'],
```

## Storage Configuration

For the file manager:

```php
// config/filesystems.php
'disks' => [
    'media' => [
        'driver' => 'local',
        'root' => storage_path('app/public/media'),
        'url' => env('APP_URL').'/storage/media',
        'visibility' => 'public',
    ],
],
```

## Cache

CamboAdmin uses caching for performance optimization. Configure your cache driver:

```php
// config/cache.php
'default' => env('CACHE_STORE', 'redis'),
```

Clear cache after configuration changes:

```bash
php artisan config:clear
php artisan cache:clear
```
