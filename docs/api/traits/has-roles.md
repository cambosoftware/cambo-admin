# HasRoles Trait

The `HasRoles` trait provides role and permission management capabilities for your User model.

## Usage

Add the trait to your User model:

```php
<?php

namespace App\Models;

use CamboSoftware\CamboAdmin\Models\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasRoles;
}
```

## Available Methods

### Role Management

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| `assignRole()` | `string\|Role\|array $roles` | `void` | Assign one or more roles |
| `removeRole()` | `string\|Role\|array $roles` | `void` | Remove one or more roles |
| `syncRoles()` | `array $roles` | `void` | Sync roles (replace all) |
| `hasRole()` | `string\|Role $role` | `bool` | Check if user has a role |
| `hasAnyRole()` | `array $roles` | `bool` | Check if user has any of the roles |
| `hasAllRoles()` | `array $roles` | `bool` | Check if user has all roles |

### Permission Management

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| `hasPermission()` | `string\|Permission $permission` | `bool` | Check if user has permission |
| `hasAnyPermission()` | `array $permissions` | `bool` | Check if user has any permission |
| `hasAllPermissions()` | `array $permissions` | `bool` | Check if user has all permissions |
| `getAllPermissions()` | - | `Collection` | Get all user permissions |

## Examples

### Assigning Roles

```php
// Assign by slug
$user->assignRole('editor');

// Assign by Role instance
$role = Role::findBySlug('editor');
$user->assignRole($role);

// Assign multiple roles
$user->assignRole(['editor', 'moderator']);
```

### Checking Roles

```php
// Single role check
if ($user->hasRole('admin')) {
    // User is admin
}

// Any role check (OR logic)
if ($user->hasAnyRole(['admin', 'editor'])) {
    // User has admin OR editor role
}

// All roles check (AND logic)
if ($user->hasAllRoles(['editor', 'moderator'])) {
    // User has BOTH roles
}
```

### Checking Permissions

```php
// Direct permission check
if ($user->hasPermission('posts.edit')) {
    // User can edit posts
}

// Get all permissions (including from roles)
$permissions = $user->getAllPermissions();
```

### Syncing Roles

```php
// Replace all roles with these
$user->syncRoles(['editor', 'writer']);

// User now only has 'editor' and 'writer' roles
```

## Relationships

The trait adds these relationships to your model:

```php
// Get all roles
$user->roles;

// Get direct permissions (not from roles)
$user->permissions;
```

## Super Admin

Users with the `super-admin` role automatically pass all permission checks:

```php
$user->assignRole('super-admin');

$user->hasPermission('any.permission'); // Always true
$user->hasPermission('non.existent');   // Also true
```

## Source Code

**Location:** `src/Models/Traits/HasRoles.php`
