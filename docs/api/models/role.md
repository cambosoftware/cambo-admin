# Role Model

The `Role` model represents user roles for role-based access control (RBAC) in CamboAdmin.

## Description

The Role model provides a complete role management system with support for permissions, user assignments, and default role functionality. It uses Laravel's `HasFactory` trait and `BelongsToMany` relationships.

## Usage

```php
use CamboSoftware\CamboAdmin\Models\Role;

$role = Role::create([
    'name' => 'Editor',
    'slug' => 'editor',
    'description' => 'Content editor role',
]);
```

## Properties

| Property | Type | Description |
|----------|------|-------------|
| `name` | `string` | Display name of the role |
| `slug` | `string` | Unique slug identifier |
| `description` | `string\|null` | Role description |
| `is_default` | `boolean` | Whether this is the default role for new users |

## Fillable Attributes

```php
protected $fillable = [
    'name',
    'slug',
    'description',
    'is_default',
];
```

## Casts

```php
protected $casts = [
    'is_default' => 'boolean',
];
```

## Methods

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `permissions()` | none | `BelongsToMany` | Get role permissions |
| `users()` | none | `BelongsToMany` | Get users with this role |
| `givePermissions()` | `array $permissions` | `self` | Assign permissions to role |
| `revokePermissions()` | `array $permissions` | `self` | Remove permissions from role |
| `syncPermissions()` | `array $permissions` | `self` | Sync permissions with role |
| `hasPermission()` | `string $permission` | `bool` | Check if role has permission |
| `hasAnyPermission()` | `array $permissions` | `bool` | Check if role has any permission |
| `hasAllPermissions()` | `array $permissions` | `bool` | Check if role has all permissions |
| `getDefault()` | none (static) | `?self` | Get the default role |

## Method Details

### permissions()

```php
public function permissions(): BelongsToMany
```

Returns the permissions relationship. Uses `role_permission` pivot table.

**Example:**

```php
$permissions = $role->permissions;
// or
$role->permissions()->where('group', 'posts')->get();
```

### users()

```php
public function users(): BelongsToMany
```

Returns the users relationship. Uses `user_role` pivot table.

**Example:**

```php
$users = $role->users;
$count = $role->users()->count();
```

### givePermissions()

```php
public function givePermissions(array $permissions): self
```

Assigns permissions to the role without removing existing ones.

**Parameters:**
- `$permissions` - Array of permission slugs

**Example:**

```php
$role->givePermissions(['posts.create', 'posts.edit', 'posts.delete']);
```

### revokePermissions()

```php
public function revokePermissions(array $permissions): self
```

Removes specific permissions from the role.

**Parameters:**
- `$permissions` - Array of permission slugs to remove

**Example:**

```php
$role->revokePermissions(['posts.delete']);
```

### syncPermissions()

```php
public function syncPermissions(array $permissions): self
```

Syncs permissions, removing any not in the provided array.

**Parameters:**
- `$permissions` - Array of permission slugs

**Example:**

```php
// Role will ONLY have these permissions after sync
$role->syncPermissions(['posts.view', 'posts.create', 'posts.edit']);
```

### hasPermission()

```php
public function hasPermission(string $permission): bool
```

Checks if the role has a specific permission.

**Parameters:**
- `$permission` - Permission slug

**Example:**

```php
if ($role->hasPermission('posts.edit')) {
    // Role can edit posts
}
```

### hasAnyPermission()

```php
public function hasAnyPermission(array $permissions): bool
```

Checks if the role has any of the given permissions.

**Parameters:**
- `$permissions` - Array of permission slugs

**Example:**

```php
if ($role->hasAnyPermission(['posts.edit', 'posts.delete'])) {
    // Role can edit OR delete posts
}
```

### hasAllPermissions()

```php
public function hasAllPermissions(array $permissions): bool
```

Checks if the role has all of the given permissions.

**Parameters:**
- `$permissions` - Array of permission slugs

**Example:**

```php
if ($role->hasAllPermissions(['posts.view', 'posts.edit', 'posts.delete'])) {
    // Role has all post management permissions
}
```

### getDefault()

```php
public static function getDefault(): ?self
```

Returns the role marked as default, or null if none exists.

**Example:**

```php
$defaultRole = Role::getDefault();
if ($defaultRole) {
    $user->roles()->attach($defaultRole);
}
```

## Complete Usage Example

```php
use CamboSoftware\CamboAdmin\Models\Role;
use CamboSoftware\CamboAdmin\Models\Permission;

// Create a new role
$role = Role::create([
    'name' => 'Content Manager',
    'slug' => 'content-manager',
    'description' => 'Manages all content on the site',
    'is_default' => false,
]);

// Add permissions
$role->givePermissions([
    'posts.view',
    'posts.create',
    'posts.edit',
    'posts.delete',
    'categories.view',
    'categories.create',
]);

// Check permissions
if ($role->hasPermission('posts.delete')) {
    // Can delete posts
}

// Sync permissions (replaces all existing)
$role->syncPermissions([
    'posts.view',
    'posts.create',
    'posts.edit',
]);

// Remove specific permissions
$role->revokePermissions(['posts.edit']);

// Get all users with this role
$managers = $role->users;

// Set as default role
$role->update(['is_default' => true]);

// Get default role
$default = Role::getDefault();
```

## Controller Example

```php
use CamboSoftware\CamboAdmin\Models\Role;
use CamboSoftware\CamboAdmin\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $role = Role::create($request->validated());

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json($role->load('permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->validated());

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json($role->load('permissions'));
    }

    public function destroy(Role $role)
    {
        // Prevent deleting default role
        if ($role->is_default) {
            return response()->json(['error' => 'Cannot delete default role'], 422);
        }

        $role->delete();
        return response()->json(['success' => true]);
    }
}
```

## Database Schema

```php
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->boolean('is_default')->default(false);
    $table->timestamps();
});

Schema::create('role_permission', function (Blueprint $table) {
    $table->foreignId('role_id')->constrained()->cascadeOnDelete();
    $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
    $table->primary(['role_id', 'permission_id']);
});

Schema::create('user_role', function (Blueprint $table) {
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('role_id')->constrained()->cascadeOnDelete();
    $table->primary(['user_id', 'role_id']);
});
```

## Source Code

**Location:** `src/Models/Role.php`

**Namespace:** `CamboSoftware\CamboAdmin\Models`
