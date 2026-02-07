# Roles & Permissions

CamboAdmin includes a complete Role-Based Access Control (RBAC) system for managing user authorization with roles and granular permissions.

## Introduction

The roles and permissions system allows you to:

- Create and manage roles with specific permissions
- Assign multiple roles to users
- Define granular permissions for specific actions
- Protect routes using middleware
- Use Blade directives for view-level authorization
- Organize permissions into groups

## Configuration

### Enable/Disable Modules

```php
// config/cambo-admin.php
'modules' => [
    'roles' => true,
    'permissions' => true,
],
```

### Custom Models

```php
// config/cambo-admin.php
'models' => [
    'role' => CamboSoftware\CamboAdmin\Models\Role::class,
    'permission' => CamboSoftware\CamboAdmin\Models\Permission::class,
],
```

## Usage Examples

### Setting Up Your User Model

Add the `HasRoles` trait to your User model:

```php
<?php

namespace App\Models;

use CamboSoftware\CamboAdmin\Models\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```

### Role Management

#### Create a Role

```php
use CamboSoftware\CamboAdmin\Models\Role;

// Basic role creation
$role = Role::create([
    'name' => 'Editor',
    'slug' => 'editor',
    'description' => 'Can edit and publish content',
]);

// Create with permissions
$role = Role::create([
    'name' => 'Content Manager',
    'slug' => 'content-manager',
    'description' => 'Manages all content',
]);
$role->givePermissions(['posts.view', 'posts.create', 'posts.edit', 'posts.delete']);
```

#### Assign Roles to Users

```php
// Assign by slug
$user->assignRole('editor');

// Assign by Role instance
$role = Role::findBySlug('editor');
$user->assignRole($role);

// Assign multiple roles at once
$user->assignRole(['editor', 'moderator']);

// Alternative syntax
$user->roles()->attach($role);
```

#### Remove Roles from Users

```php
// Remove by slug
$user->removeRole('editor');

// Remove by Role instance
$user->removeRole($role);

// Remove multiple roles
$user->removeRole(['editor', 'moderator']);
```

#### Sync Roles

Replace all existing roles with new ones:

```php
// Sync by slugs
$user->syncRoles(['editor', 'moderator']);

// This removes all roles not in the array
$user->syncRoles(['admin']); // User now only has 'admin' role
```

#### Check User Roles

```php
// Check for a specific role
if ($user->hasRole('admin')) {
    // User is an admin
}

// Check for any of the given roles (OR logic)
if ($user->hasAnyRole(['admin', 'editor'])) {
    // User has admin OR editor role
}

// Check for all given roles (AND logic)
if ($user->hasAllRoles(['editor', 'moderator'])) {
    // User has both editor AND moderator roles
}

// Get all role slugs
$roleSlugs = $user->roles->pluck('slug')->toArray();
```

### Permission Management

#### Create Permissions

```php
use CamboSoftware\CamboAdmin\Models\Permission;

// Single permission
$permission = Permission::create([
    'name' => 'Edit Posts',
    'slug' => 'posts.edit',
    'group' => 'posts',
]);

// Create grouped permissions for a resource
$permissions = [
    ['name' => 'View Posts', 'slug' => 'posts.view', 'group' => 'posts'],
    ['name' => 'Create Posts', 'slug' => 'posts.create', 'group' => 'posts'],
    ['name' => 'Edit Posts', 'slug' => 'posts.edit', 'group' => 'posts'],
    ['name' => 'Delete Posts', 'slug' => 'posts.delete', 'group' => 'posts'],
    ['name' => 'Publish Posts', 'slug' => 'posts.publish', 'group' => 'posts'],
];

foreach ($permissions as $perm) {
    Permission::create($perm);
}
```

#### Assign Permissions to Roles

```php
// Give permissions by slug
$role->givePermissions(['posts.view', 'posts.edit', 'posts.create']);

// Give a single permission
$role->givePermissions('posts.delete');

// Via relationship
$role->permissions()->attach($permission);
$role->permissions()->attach([$permission1->id, $permission2->id]);
```

#### Revoke Permissions

```php
// Revoke specific permissions
$role->revokePermissions(['posts.delete']);

// Revoke all permissions
$role->permissions()->detach();
```

#### Sync Permissions

```php
// Replace all role permissions with these
$role->syncPermissions(['posts.view', 'posts.edit']);
```

#### Check User Permissions

```php
// Check for a specific permission
if ($user->hasPermission('posts.edit')) {
    // User can edit posts
}

// Check for any permission (OR logic)
if ($user->hasAnyPermission(['posts.edit', 'posts.delete'])) {
    // User can edit OR delete posts
}

// Check for all permissions (AND logic)
if ($user->hasAllPermissions(['posts.view', 'posts.edit'])) {
    // User can view AND edit posts
}

// Get all permissions (including from roles)
$permissions = $user->getAllPermissions();
```

### Super Admin Role

The `super-admin` role automatically has all permissions:

```php
// Create super admin role
$superAdmin = Role::create([
    'name' => 'Super Admin',
    'slug' => 'super-admin',
    'description' => 'Full system access',
]);

// Assign to user
$user->assignRole('super-admin');

// Now all permission checks return true
$user->hasPermission('any.permission'); // true
$user->hasPermission('non.existent');   // true
```

### Default Role

Set a default role for new users:

```php
// Create default role
$role = Role::create([
    'name' => 'User',
    'slug' => 'user',
    'is_default' => true,
]);

// Get the default role
$defaultRole = Role::getDefault();

// Assign default role to new users (in registration logic)
$user = User::create([...]);
$defaultRole = Role::getDefault();
if ($defaultRole) {
    $user->assignRole($defaultRole);
}
```

### Grouped Permissions

Organize permissions into logical groups:

```php
// Create grouped permissions
Permission::create(['name' => 'View', 'slug' => 'users.view', 'group' => 'users']);
Permission::create(['name' => 'Create', 'slug' => 'users.create', 'group' => 'users']);
Permission::create(['name' => 'Edit', 'slug' => 'users.edit', 'group' => 'users']);
Permission::create(['name' => 'Delete', 'slug' => 'users.delete', 'group' => 'users']);

Permission::create(['name' => 'View', 'slug' => 'posts.view', 'group' => 'posts']);
Permission::create(['name' => 'Create', 'slug' => 'posts.create', 'group' => 'posts']);
Permission::create(['name' => 'Edit', 'slug' => 'posts.edit', 'group' => 'posts']);
Permission::create(['name' => 'Delete', 'slug' => 'posts.delete', 'group' => 'posts']);

// Get permissions grouped
$grouped = Permission::getGrouped();
// Returns:
// [
//     'users' => [Permission, Permission, ...],
//     'posts' => [Permission, Permission, ...],
// ]
```

## Route Protection Middleware

### Role Middleware

```php
// routes/web.php

// Single role required
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/settings', [SettingsController::class, 'index']);
});

// Any of these roles (OR logic)
Route::middleware('role:admin,editor')->group(function () {
    Route::get('/admin/posts', [PostController::class, 'index']);
});

// Controller-based middleware
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }
}
```

### Permission Middleware

```php
// Single permission required
Route::middleware('permission:posts.edit')->group(function () {
    Route::put('/posts/{post}', [PostController::class, 'update']);
});

// Any of these permissions (OR logic)
Route::middleware('permission:posts.edit,posts.delete')->group(function () {
    Route::resource('/posts', PostController::class)->except(['index', 'show']);
});

// On specific route
Route::delete('/posts/{post}', [PostController::class, 'destroy'])
    ->middleware('permission:posts.delete');
```

### Combining Middleware

```php
Route::middleware(['auth', 'role:admin', 'permission:settings.edit'])->group(function () {
    Route::get('/admin/settings', [SettingsController::class, 'index']);
});
```

## Blade Directives

### Role Directives

```blade
{{-- Check for a single role --}}
@role('admin')
    <a href="/admin/settings">Settings</a>
@endrole

{{-- Check for any role --}}
@hasanyrole(['admin', 'editor'])
    <a href="/admin/posts">Manage Posts</a>
@endhasanyrole

{{-- Check for all roles --}}
@hasallroles(['editor', 'moderator'])
    <span>You are an editor and moderator</span>
@endhasallroles

{{-- Inverse check --}}
@unlessrole('admin')
    <p>You are not an admin</p>
@endunlessrole
```

### Permission Directives

```blade
{{-- Check for a permission --}}
@can('posts.edit')
    <button>Edit Post</button>
@endcan

{{-- Alternative syntax --}}
@if(auth()->user()->hasPermission('posts.delete'))
    <button>Delete Post</button>
@endif

{{-- Check any permission --}}
@canany(['posts.edit', 'posts.delete'])
    <div class="actions">
        @can('posts.edit')
            <button>Edit</button>
        @endcan
        @can('posts.delete')
            <button>Delete</button>
        @endcan
    </div>
@endcanany
```

### Super Admin Directive

```blade
@role('super-admin')
    <div class="danger-zone">
        <h3>Danger Zone</h3>
        <button>Delete Everything</button>
    </div>
@endrole
```

## Available Options

### Role Model Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Display name of the role |
| `slug` | string | URL-friendly identifier (unique) |
| `description` | string | Optional description |
| `is_default` | boolean | Auto-assign to new users |
| `created_at` | datetime | Creation timestamp |
| `updated_at` | datetime | Last update timestamp |

### Permission Model Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Display name of the permission |
| `slug` | string | Unique identifier (e.g., 'posts.edit') |
| `group` | string | Group name for organization |
| `description` | string | Optional description |
| `created_at` | datetime | Creation timestamp |
| `updated_at` | datetime | Last update timestamp |

### HasRoles Trait Methods

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| `assignRole()` | `string\|Role\|array` | `void` | Assign role(s) to user |
| `removeRole()` | `string\|Role\|array` | `void` | Remove role(s) from user |
| `syncRoles()` | `array` | `void` | Sync roles (replace all) |
| `hasRole()` | `string\|Role` | `bool` | Check if user has role |
| `hasAnyRole()` | `array` | `bool` | Check if user has any role |
| `hasAllRoles()` | `array` | `bool` | Check if user has all roles |
| `hasPermission()` | `string\|Permission` | `bool` | Check if user has permission |
| `hasAnyPermission()` | `array` | `bool` | Check if user has any permission |
| `hasAllPermissions()` | `array` | `bool` | Check if user has all permissions |
| `getAllPermissions()` | - | `Collection` | Get all user permissions |

## Management Interface

CamboAdmin provides a built-in UI for managing roles and permissions:

- `/admin/roles` - List all roles
- `/admin/roles/create` - Create a new role
- `/admin/roles/{id}/edit` - Edit a role and its permissions
- `/admin/permissions` - List all permissions
- `/admin/permissions/create` - Create a new permission

## Seeding Default Roles and Permissions

Create a seeder for initial roles and permissions:

```php
<?php

namespace Database\Seeders;

use CamboSoftware\CamboAdmin\Models\Permission;
use CamboSoftware\CamboAdmin\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Users
            ['name' => 'View Users', 'slug' => 'users.view', 'group' => 'users'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'group' => 'users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'group' => 'users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'group' => 'users'],

            // Posts
            ['name' => 'View Posts', 'slug' => 'posts.view', 'group' => 'posts'],
            ['name' => 'Create Posts', 'slug' => 'posts.create', 'group' => 'posts'],
            ['name' => 'Edit Posts', 'slug' => 'posts.edit', 'group' => 'posts'],
            ['name' => 'Delete Posts', 'slug' => 'posts.delete', 'group' => 'posts'],
            ['name' => 'Publish Posts', 'slug' => 'posts.publish', 'group' => 'posts'],

            // Settings
            ['name' => 'View Settings', 'slug' => 'settings.view', 'group' => 'settings'],
            ['name' => 'Edit Settings', 'slug' => 'settings.edit', 'group' => 'settings'],
        ];

        foreach ($permissions as $perm) {
            Permission::create($perm);
        }

        // Create roles
        $superAdmin = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'description' => 'Full system access',
        ]);

        $admin = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrative access',
        ]);
        $admin->givePermissions(Permission::pluck('slug')->toArray());

        $editor = Role::create([
            'name' => 'Editor',
            'slug' => 'editor',
            'description' => 'Can manage content',
        ]);
        $editor->givePermissions([
            'posts.view', 'posts.create', 'posts.edit', 'posts.publish',
        ]);

        $user = Role::create([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'Basic user access',
            'is_default' => true,
        ]);
        $user->givePermissions(['posts.view']);
    }
}
```

Run the seeder:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

## Caching

For performance optimization, roles and permissions are cached. Clear the cache after changes:

```php
// Clear permission cache manually
use Illuminate\Support\Facades\Cache;

Cache::forget('roles');
Cache::forget('permissions');

// Or use artisan
// php artisan cache:clear
```
