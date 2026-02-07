# Permission Model

The `Permission` model represents individual permissions for role-based access control (RBAC) in CamboAdmin.

## Description

The Permission model stores granular permissions that can be assigned to roles. Permissions are organized by groups and use a slug-based identification system (e.g., `posts.edit`, `users.delete`).

## Usage

```php
use CamboSoftware\CamboAdmin\Models\Permission;

$permission = Permission::create([
    'name' => 'Edit Posts',
    'slug' => 'posts.edit',
    'description' => 'Can edit existing posts',
    'group' => 'posts',
]);
```

## Properties

| Property | Type | Description |
|----------|------|-------------|
| `name` | `string` | Display name of the permission |
| `slug` | `string` | Unique slug identifier (e.g., `posts.edit`) |
| `description` | `string\|null` | Permission description |
| `group` | `string\|null` | Permission group for organization |

## Fillable Attributes

```php
protected $fillable = [
    'name',
    'slug',
    'description',
    'group',
];
```

## Methods

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `roles()` | none | `BelongsToMany` | Get roles with this permission |
| `getGrouped()` | none (static) | `array` | Get all permissions grouped |

## Method Details

### roles()

```php
public function roles(): BelongsToMany
```

Returns the roles relationship. Uses `role_permission` pivot table.

**Example:**

```php
$roles = $permission->roles;
$roleCount = $permission->roles()->count();
```

### getGrouped()

```php
public static function getGrouped(): array
```

Returns all permissions grouped by their `group` field.

**Example:**

```php
$grouped = Permission::getGrouped();
// Returns:
// [
//     'posts' => [
//         ['id' => 1, 'name' => 'View Posts', 'slug' => 'posts.view', 'description' => '...'],
//         ['id' => 2, 'name' => 'Create Posts', 'slug' => 'posts.create', 'description' => '...'],
//         ['id' => 3, 'name' => 'Edit Posts', 'slug' => 'posts.edit', 'description' => '...'],
//         ['id' => 4, 'name' => 'Delete Posts', 'slug' => 'posts.delete', 'description' => '...'],
//     ],
//     'users' => [
//         ['id' => 5, 'name' => 'View Users', 'slug' => 'users.view', 'description' => '...'],
//         ...
//     ],
// ]
```

## Permission Naming Convention

CamboAdmin follows a consistent naming convention for permissions:

```
{resource}.{action}
```

### Common Actions

| Action | Description |
|--------|-------------|
| `view` | View/list resources |
| `create` | Create new resources |
| `edit` | Edit existing resources |
| `delete` | Delete resources |
| `manage` | Full management access |

### Examples

```php
// Post permissions
'posts.view'
'posts.create'
'posts.edit'
'posts.delete'
'posts.publish'

// User permissions
'users.view'
'users.create'
'users.edit'
'users.delete'
'users.impersonate'

// Settings permissions
'settings.view'
'settings.edit'

// Role permissions
'roles.view'
'roles.create'
'roles.edit'
'roles.delete'
'roles.assign'
```

## Complete Usage Example

```php
use CamboSoftware\CamboAdmin\Models\Permission;
use CamboSoftware\CamboAdmin\Models\Role;

// Create permissions for a new module
$permissions = [
    ['name' => 'View Products', 'slug' => 'products.view', 'group' => 'products'],
    ['name' => 'Create Products', 'slug' => 'products.create', 'group' => 'products'],
    ['name' => 'Edit Products', 'slug' => 'products.edit', 'group' => 'products'],
    ['name' => 'Delete Products', 'slug' => 'products.delete', 'group' => 'products'],
];

foreach ($permissions as $permission) {
    Permission::create($permission);
}

// Get grouped permissions for UI
$grouped = Permission::getGrouped();

// Find roles that have a specific permission
$permission = Permission::where('slug', 'products.edit')->first();
$rolesWithPermission = $permission->roles;

// Check if any role has this permission
$hasRoles = $permission->roles()->exists();
```

## Controller Example

```php
use CamboSoftware\CamboAdmin\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        // Return grouped permissions for role management UI
        return response()->json([
            'permissions' => Permission::getGrouped(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:permissions,slug',
            'description' => 'nullable|string',
            'group' => 'required|string|max:50',
        ]);

        $permission = Permission::create($validated);

        return response()->json($permission, 201);
    }

    public function destroy(Permission $permission)
    {
        // Detach from all roles first
        $permission->roles()->detach();
        $permission->delete();

        return response()->json(['success' => true]);
    }
}
```

## Seeder Example

```php
use CamboSoftware\CamboAdmin\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'group' => 'dashboard'],

            // Users
            ['name' => 'View Users', 'slug' => 'users.view', 'group' => 'users'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'group' => 'users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'group' => 'users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'group' => 'users'],

            // Roles
            ['name' => 'View Roles', 'slug' => 'roles.view', 'group' => 'roles'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'group' => 'roles'],
            ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'group' => 'roles'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'group' => 'roles'],

            // Settings
            ['name' => 'View Settings', 'slug' => 'settings.view', 'group' => 'settings'],
            ['name' => 'Edit Settings', 'slug' => 'settings.edit', 'group' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}
```

## Database Schema

```php
Schema::create('permissions', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('group')->nullable()->index();
    $table->timestamps();
});
```

## Source Code

**Location:** `src/Models/Permission.php`

**Namespace:** `CamboSoftware\CamboAdmin\Models`
