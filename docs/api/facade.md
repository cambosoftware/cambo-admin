# Cambo Facade

The `Cambo` facade provides simplified access to CamboAdmin core features and configuration.

## Description

The Cambo facade is a static proxy to the `CamboSoftware\CamboAdmin\CamboAdmin` class, providing convenient access to configuration values, module status, and application settings.

## Usage

```php
use CamboSoftware\CamboAdmin\Facades\Cambo;

// or via alias
use Cambo;
```

## Methods

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `config()` | `string $key, mixed $default = null` | `mixed` | Get a configuration value |
| `moduleEnabled()` | `string $module` | `bool` | Check if a module is enabled |
| `enabledModules()` | none | `array` | Get all enabled modules |
| `routePrefix()` | none | `string` | Get the route prefix |
| `routeMiddleware()` | none | `array` | Get the route middleware |
| `primaryColor()` | none | `string` | Get the primary color |
| `appName()` | none | `string` | Get the application name |
| `darkMode()` | none | `string` | Get the dark mode setting |
| `featureEnabled()` | `string $feature` | `bool` | Check if a feature is enabled |
| `userModel()` | none | `string` | Get the User model class |

## Method Details

### config()

```php
public function config(string $key, mixed $default = null): mixed
```

Retrieves a configuration value using dot notation.

**Parameters:**
- `$key` - The configuration key using dot notation
- `$default` - Default value if key not found

**Example:**

```php
$appName = Cambo::config('appearance.name');
$primaryColor = Cambo::config('appearance.primary_color', '#6366f1');
```

### moduleEnabled()

```php
public function moduleEnabled(string $module): bool
```

Checks if a specific module is enabled in the configuration.

**Parameters:**
- `$module` - The module name to check

**Example:**

```php
if (Cambo::moduleEnabled('notifications')) {
    // Notifications module is active
}

if (Cambo::moduleEnabled('media')) {
    // Media module is active
}
```

### enabledModules()

```php
public function enabledModules(): array
```

Returns an array of all enabled module names.

**Example:**

```php
$modules = Cambo::enabledModules();
// ['auth', 'users', 'roles', 'notifications', ...]
```

### routePrefix()

```php
public function routePrefix(): string
```

Returns the route prefix for admin routes. Defaults to `'admin'`.

**Example:**

```php
$prefix = Cambo::routePrefix();
// 'admin'

// Use in routes
Route::prefix(Cambo::routePrefix())->group(function () {
    // Admin routes
});
```

### routeMiddleware()

```php
public function routeMiddleware(): array
```

Returns the middleware array for admin routes. Defaults to `['web', 'auth']`.

**Example:**

```php
$middleware = Cambo::routeMiddleware();
// ['web', 'auth', 'verified']
```

### primaryColor()

```php
public function primaryColor(): string
```

Returns the primary color hex code. Defaults to `'#6366f1'` (Indigo).

**Example:**

```php
$color = Cambo::primaryColor();
// '#6366f1'
```

### appName()

```php
public function appName(): string
```

Returns the application name. Falls back to Laravel's `config('app.name')`.

**Example:**

```php
$name = Cambo::appName();
// 'CamboAdmin'
```

### darkMode()

```php
public function darkMode(): string
```

Returns the dark mode setting. Defaults to `'auto'`.

**Possible values:**
- `'auto'` - Follow system preference
- `'light'` - Always light mode
- `'dark'` - Always dark mode

**Example:**

```php
$mode = Cambo::darkMode();
// 'auto', 'light', or 'dark'
```

### featureEnabled()

```php
public function featureEnabled(string $feature): bool
```

Checks if a specific feature is enabled.

**Parameters:**
- `$feature` - The feature name to check

**Example:**

```php
if (Cambo::featureEnabled('two_factor')) {
    // Two-factor authentication is enabled
}

if (Cambo::featureEnabled('registration')) {
    // User registration is enabled
}
```

### userModel()

```php
public function userModel(): string
```

Returns the User model class name. Defaults to `\App\Models\User::class`.

**Example:**

```php
$userClass = Cambo::userModel();
// 'App\Models\User'

$users = $userClass::all();
```

## Complete Usage Example

```php
use CamboSoftware\CamboAdmin\Facades\Cambo;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'appName' => Cambo::appName(),
            'primaryColor' => Cambo::primaryColor(),
            'darkMode' => Cambo::darkMode(),
            'modules' => Cambo::enabledModules(),
            'features' => [
                'twoFactor' => Cambo::featureEnabled('two_factor'),
                'registration' => Cambo::featureEnabled('registration'),
            ],
        ];

        return Inertia::render('Dashboard', $data);
    }
}
```

## Direct Service Access

You can also inject the service directly:

```php
use CamboSoftware\CamboAdmin\CamboAdmin;

class MyController extends Controller
{
    public function __construct(
        protected CamboAdmin $cambo
    ) {}

    public function index()
    {
        $appName = $this->cambo->appName();
        $modules = $this->cambo->enabledModules();
    }
}
```

## Source Code

**Location:** `src/Facades/Cambo.php` and `src/CamboAdmin.php`

The facade resolves to the `cambo-admin` binding in the service container:

```php
protected static function getFacadeAccessor(): string
{
    return 'cambo-admin';
}
```
