# CamboAdmin API Reference

Complete PHP API documentation for CamboAdmin package.

## Overview

CamboAdmin provides a robust set of services, models, traits, and middleware for building admin panels in Laravel applications.

## Namespace

All CamboAdmin classes are under the `CamboSoftware\CamboAdmin` namespace.

```php
use CamboSoftware\CamboAdmin\Facades\Cambo;
use CamboSoftware\CamboAdmin\Services\ThemeService;
use CamboSoftware\CamboAdmin\Models\Role;
```

## Services

| Service | Description |
|---------|-------------|
| [Cambo Facade](./facade.md) | Main facade for configuration access |
| [ThemeService](./theme-service.md) | Theme management and customization |
| [TranslationService](./translation-service.md) | Multi-language translation management |
| [ImportExportService](./import-export-service.md) | Data import/export (CSV, Excel, PDF) |

### Quick Example

```php
use CamboSoftware\CamboAdmin\Facades\Cambo;

$appName = Cambo::appName();
$isEnabled = Cambo::moduleEnabled('notifications');
```

## Models

| Model | Description |
|-------|-------------|
| [Role](./models/role.md) | Role model for RBAC |
| [Permission](./models/permission.md) | Permission model for RBAC |
| [Setting](./models/setting.md) | Application settings management |
| [Activity](./models/activity.md) | Activity logging model |
| [MediaFile](./models/media-file.md) | Media file management |

### Quick Example

```php
use CamboSoftware\CamboAdmin\Models\Role;

$role = Role::create(['name' => 'Editor', 'slug' => 'editor']);
$role->givePermissions(['posts.edit', 'posts.create']);
```

## Traits

| Trait | Description |
|-------|-------------|
| [HasRoles](./traits/has-roles.md) | Add role/permission capabilities to User model |
| [LogsActivity](./traits/logs-activity.md) | Automatic activity logging for models |

### Quick Example

```php
use CamboSoftware\CamboAdmin\Models\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}

$user->assignRole('editor');
$user->hasPermission('posts.edit');
```

## Middleware

| Middleware | Description |
|------------|-------------|
| [CheckRole](./middleware/check-role.md) | Role-based route protection |
| [CheckPermission](./middleware/check-permission.md) | Permission-based route protection |
| [SetLocale](./middleware/set-locale.md) | Automatic locale detection and setting |

### Quick Example

```php
Route::middleware('role:admin')->group(function () {
    // Admin-only routes
});

Route::middleware('permission:posts.edit')->group(function () {
    // Routes requiring specific permission
});
```

## Artisan Commands

| Command | Description |
|---------|-------------|
| `cambo:install` | Interactive installation |
| `cambo:crud` | Generate complete CRUD |
| `cambo:page` | Generate Vue page |
| `cambo:component` | Generate Vue component |
| `cambo:add` | Add a module |

## Service Container Bindings

CamboAdmin registers the following bindings:

```php
// Main CamboAdmin instance
app('cambo-admin');

// Services
app(ThemeService::class);
app(TranslationService::class);
app(ImportExportService::class);
```

## Error Handling

All services throw standard Laravel exceptions:

- `Illuminate\Auth\Access\AuthorizationException` - Unauthorized access
- `Illuminate\Database\Eloquent\ModelNotFoundException` - Model not found
- `Illuminate\Validation\ValidationException` - Validation failed
- `RuntimeException` - Missing dependencies (e.g., PDF export without DomPDF)

## Events

CamboAdmin dispatches the following events:

| Event | Description |
|-------|-------------|
| `RoleAssigned` | A role was assigned |
| `RoleRemoved` | A role was removed |
| `PermissionGranted` | A permission was granted |
| `SettingChanged` | A setting changed |
| `UserLoggedIn` | User logged in |

## Best Practices

1. **Use the Facade**: Access configuration through `Cambo::` facade for consistency
2. **Cache Management**: Services automatically handle caching; use `clearCache()` methods when needed
3. **Transaction Safety**: Wrap bulk operations in database transactions
4. **Permission Checks**: Always verify permissions before performing sensitive operations

```php
// Example: Safe permission check
if ($user->hasPermission('users.delete')) {
    $user->delete();
}
```
