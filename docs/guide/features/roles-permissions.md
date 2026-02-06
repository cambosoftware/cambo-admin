# Roles & Permissions

CamboAdmin includes a complete role and permission management system (RBAC).

## Basic Concepts

- **Role**: A set of permissions assigned to a user
- **Permission**: A right to perform a specific action
- **Super Admin**: Special role with all permissions

## Using the HasRoles Trait

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

## Role Management

### Create a Role

```php
use CamboSoftware\CamboAdmin\Models\Role;

$role = Role::create([
    'name' => 'Editor',
    'slug' => 'editor',
    'description' => 'Can edit content',
]);
```

### Assign a Role to a User

```php
// By slug
$user->assignRole('editor');

// By instance
$user->assignRole($role);

// Multiple roles
$user->assignRole(['editor', 'moderator']);
```

### Remove a Role

```php
$user->removeRole('editor');
$user->removeRole($role);
```

### Sync Roles

```php
// Replaces all existing roles
$user->syncRoles(['editor', 'moderator']);
```

### Check Roles

```php
// A specific role
if ($user->hasRole('admin')) {
    // ...
}

// Any of the roles
if ($user->hasAnyRole(['admin', 'editor'])) {
    // ...
}

// All roles
if ($user->hasAllRoles(['editor', 'moderator'])) {
    // ...
}
```

## Permission Management

### Create a Permission

```php
use CamboSoftware\CamboAdmin\Models\Permission;

$permission = Permission::create([
    'name' => 'Edit posts',
    'slug' => 'posts.edit',
    'group' => 'posts',
]);
```

### Assign Permissions to a Role

```php
$role->givePermissions(['posts.view', 'posts.edit', 'posts.create']);

// Or via the relationship
$role->permissions()->attach($permission);
```

### Revoke Permissions

```php
$role->revokePermissions(['posts.delete']);
```

### Sync Permissions

```php
$role->syncPermissions(['posts.view', 'posts.edit']);
```

### Check Permissions

```php
// A specific permission
if ($user->hasPermission('posts.edit')) {
    // ...
}

// Any permission
if ($user->hasAnyPermission(['posts.edit', 'posts.delete'])) {
    // ...
}

// All permissions
if ($user->hasAllPermissions(['posts.view', 'posts.edit'])) {
    // ...
}
```

## Protection Middleware

### Role Protection

```php
// routes/web.php
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/settings', [SettingsController::class, 'index']);
});

// Multiple roles (OR)
Route::middleware('role:admin,editor')->group(function () {
    // Accessible if admin OR editor
});
```

### Permission Protection

```php
Route::middleware('permission:posts.edit')->group(function () {
    Route::put('/posts/{post}', [PostController::class, 'update']);
});

// Multiple permissions (OR)
Route::middleware('permission:posts.edit,posts.delete')->group(function () {
    // Accessible if posts.edit OR posts.delete
});
```

## Blade Directives

```blade
{{-- Check a role --}}
@role('admin')
    <a href="/admin/settings">Settings</a>
@endrole

{{-- Check a permission --}}
@can('posts.edit')
    <button>Edit</button>
@endcan

{{-- Super admin --}}
@role('super-admin')
    <a href="/admin/danger-zone">Danger Zone</a>
@endrole
```

## Super Admin

The `super-admin` role automatically has all permissions:

```php
$superAdmin = Role::create([
    'name' => 'Super Admin',
    'slug' => 'super-admin',
]);

$user->assignRole('super-admin');

// Always returns true
$user->hasPermission('any.permission'); // true
```

## Default Role

Set a default role for new users:

```php
$role = Role::create([
    'name' => 'User',
    'slug' => 'user',
    'is_default' => true,
]);

// Get the default role
$defaultRole = Role::getDefault();
```

## Grouped Permissions

Organize your permissions by group:

```php
Permission::create(['name' => 'View', 'slug' => 'users.view', 'group' => 'users']);
Permission::create(['name' => 'Create', 'slug' => 'users.create', 'group' => 'users']);
Permission::create(['name' => 'Edit', 'slug' => 'users.edit', 'group' => 'users']);
Permission::create(['name' => 'Delete', 'slug' => 'users.delete', 'group' => 'users']);

// Get grouped permissions
$grouped = Permission::getGrouped();
// ['users' => [...], 'posts' => [...]]
```

## Management Interface

CamboAdmin provides Vue pages to manage roles and permissions:

- `/admin/roles` - Role list
- `/admin/roles/create` - Create a role
- `/admin/roles/{id}/edit` - Edit a role
- `/admin/permissions` - Permission list
