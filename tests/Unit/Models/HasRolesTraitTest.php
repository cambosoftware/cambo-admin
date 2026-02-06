<?php

namespace CamboSoftware\CamboAdmin\Tests\Unit\Models;

use CamboSoftware\CamboAdmin\Models\Role;
use CamboSoftware\CamboAdmin\Models\Permission;
use CamboSoftware\CamboAdmin\Models\Traits\HasRoles;
use CamboSoftware\CamboAdmin\Tests\TestCase;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HasRolesTraitTest extends TestCase
{
    protected function createTestUser(array $attributes = []): TestUser
    {
        return TestUser::create(array_merge([
            'name' => 'Test User',
            'email' => 'test' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
        ], $attributes));
    }

    public function test_user_can_have_roles(): void
    {
        $user = $this->createTestUser();
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);

        $user->roles()->attach($role);

        $this->assertTrue($user->roles->contains($role));
    }

    public function test_assign_role_by_slug(): void
    {
        $user = $this->createTestUser();
        Role::create(['name' => 'Editor', 'slug' => 'editor']);

        $user->assignRole('editor');

        $this->assertTrue($user->hasRole('editor'));
    }

    public function test_assign_role_by_instance(): void
    {
        $user = $this->createTestUser();
        $role = Role::create(['name' => 'Moderator', 'slug' => 'moderator']);

        $user->assignRole($role);

        $this->assertTrue($user->hasRole('moderator'));
    }

    public function test_remove_role(): void
    {
        $user = $this->createTestUser();
        $role = Role::create(['name' => 'Temporary', 'slug' => 'temp-role']);

        $user->assignRole($role);
        $this->assertTrue($user->hasRole('temp-role'));

        $user->removeRole($role);
        $user->refresh();

        $this->assertFalse($user->hasRole('temp-role'));
    }

    public function test_sync_roles(): void
    {
        $user = $this->createTestUser();
        $role1 = Role::create(['name' => 'Role 1', 'slug' => 'role1']);
        $role2 = Role::create(['name' => 'Role 2', 'slug' => 'role2']);
        $role3 = Role::create(['name' => 'Role 3', 'slug' => 'role3']);

        $user->assignRole($role1);
        $user->syncRoles(['role2', 'role3']); // Use slugs

        $this->assertFalse($user->hasRole('role1'));
        $this->assertTrue($user->hasRole('role2'));
        $this->assertTrue($user->hasRole('role3'));
    }

    public function test_has_any_role(): void
    {
        $user = $this->createTestUser();
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $editor = Role::create(['name' => 'Editor', 'slug' => 'editor']);

        $user->assignRole($editor);

        $this->assertTrue($user->hasAnyRole(['admin', 'editor']));
        $this->assertFalse($user->hasAnyRole(['admin', 'super']));
    }

    public function test_has_permission_through_role(): void
    {
        $user = $this->createTestUser();
        $role = Role::create(['name' => 'Writer', 'slug' => 'writer']);
        $permission = Permission::create([
            'name' => 'Create Posts',
            'slug' => 'posts.create',
            'group' => 'posts',
        ]);

        $role->permissions()->attach($permission);
        $user->assignRole($role);

        $this->assertTrue($user->hasPermission('posts.create'));
    }

    public function test_has_any_permission(): void
    {
        $user = $this->createTestUser();
        $role = Role::create(['name' => 'Content Editor', 'slug' => 'content-editor']);
        $permission1 = Permission::create([
            'name' => 'Edit Posts',
            'slug' => 'posts.edit',
            'group' => 'posts',
        ]);
        Permission::create([
            'name' => 'Delete Posts',
            'slug' => 'posts.delete',
            'group' => 'posts',
        ]);

        $role->permissions()->attach($permission1);
        $user->assignRole($role);

        $this->assertTrue($user->hasAnyPermission(['posts.edit', 'posts.delete']));
        $this->assertFalse($user->hasAnyPermission(['users.edit', 'users.delete']));
    }

    public function test_get_all_permissions(): void
    {
        $user = $this->createTestUser();
        $role1 = Role::create(['name' => 'Role A', 'slug' => 'role-a']);
        $role2 = Role::create(['name' => 'Role B', 'slug' => 'role-b']);
        $permission1 = Permission::create(['name' => 'Permission 1', 'slug' => 'perm1', 'group' => 'test']);
        $permission2 = Permission::create(['name' => 'Permission 2', 'slug' => 'perm2', 'group' => 'test']);
        $permission3 = Permission::create(['name' => 'Permission 3', 'slug' => 'perm3', 'group' => 'test']);

        $role1->permissions()->attach([$permission1->id, $permission2->id]);
        $role2->permissions()->attach([$permission2->id, $permission3->id]);

        $user->assignRole($role1);
        $user->assignRole($role2);

        $allPermissions = $user->getAllPermissions();

        // Should have 3 unique permissions
        $this->assertCount(3, $allPermissions);
    }

    public function test_super_admin_has_all_permissions(): void
    {
        $user = $this->createTestUser();
        $superAdminRole = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin']);

        $user->assignRole($superAdminRole);

        // Super admin should have any permission even if not assigned
        $this->assertTrue($user->hasPermission('any.permission'));
        $this->assertTrue($user->hasAnyPermission(['some.permission', 'other.permission']));
    }

    public function test_has_all_permissions(): void
    {
        $user = $this->createTestUser();
        $role = Role::create(['name' => 'Full Access', 'slug' => 'full-access']);
        $permission1 = Permission::create(['name' => 'Read', 'slug' => 'data.read', 'group' => 'data']);
        $permission2 = Permission::create(['name' => 'Write', 'slug' => 'data.write', 'group' => 'data']);
        Permission::create(['name' => 'Delete', 'slug' => 'data.delete', 'group' => 'data']);

        $role->permissions()->attach([$permission1->id, $permission2->id]);
        $user->assignRole($role);

        $this->assertTrue($user->hasAllPermissions(['data.read', 'data.write']));
        $this->assertFalse($user->hasAllPermissions(['data.read', 'data.delete']));
    }

    public function test_has_all_roles(): void
    {
        $user = $this->createTestUser();
        $role1 = Role::create(['name' => 'Manager', 'slug' => 'manager']);
        $role2 = Role::create(['name' => 'Reviewer', 'slug' => 'reviewer']);
        Role::create(['name' => 'Admin', 'slug' => 'admin']);

        $user->roles()->attach([$role1->id, $role2->id]);

        $this->assertTrue($user->hasAllRoles(['manager', 'reviewer']));
        $this->assertFalse($user->hasAllRoles(['manager', 'admin']));
    }
}

/**
 * Test User model with HasRoles trait
 */
class TestUser extends Authenticatable
{
    use HasRoles;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];

    // Disable automatic role assignment for tests
    public static function bootHasRoles(): void
    {
        // Empty - no auto role assignment in tests
    }
}
