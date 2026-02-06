<?php

namespace CamboSoftware\CamboAdmin\Tests\Unit\Models;

use CamboSoftware\CamboAdmin\Models\Role;
use CamboSoftware\CamboAdmin\Models\Permission;
use CamboSoftware\CamboAdmin\Tests\TestCase;

class RoleTest extends TestCase
{
    public function test_role_can_be_created(): void
    {
        $role = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator role',
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Admin',
            'slug' => 'admin',
        ]);
    }

    public function test_role_has_permissions_relationship(): void
    {
        $role = Role::create(['name' => 'Editor', 'slug' => 'editor']);
        $permission = Permission::create([
            'name' => 'Edit Posts',
            'slug' => 'posts.edit',
            'group' => 'posts',
        ]);

        $role->permissions()->attach($permission);

        $this->assertTrue($role->permissions->contains($permission));
    }

    public function test_role_can_sync_permissions(): void
    {
        $role = Role::create(['name' => 'Writer', 'slug' => 'writer']);
        $permission1 = Permission::create([
            'name' => 'Create Posts',
            'slug' => 'posts.create',
            'group' => 'posts',
        ]);
        $permission2 = Permission::create([
            'name' => 'Edit Posts',
            'slug' => 'posts.edit',
            'group' => 'posts',
        ]);

        // syncPermissions expects slugs, not ids
        $role->syncPermissions(['posts.create', 'posts.edit']);

        $this->assertCount(2, $role->fresh()->permissions);
    }

    public function test_role_has_permission_method(): void
    {
        $role = Role::create(['name' => 'Moderator', 'slug' => 'moderator']);
        $permission = Permission::create([
            'name' => 'Delete Comments',
            'slug' => 'comments.delete',
            'group' => 'comments',
        ]);

        $role->permissions()->attach($permission);

        $this->assertTrue($role->hasPermission('comments.delete'));
        $this->assertFalse($role->hasPermission('posts.delete'));
    }

    public function test_role_can_be_set_as_default(): void
    {
        $role1 = Role::create(['name' => 'User', 'slug' => 'user', 'is_default' => true]);
        $role2 = Role::create(['name' => 'Guest', 'slug' => 'guest', 'is_default' => false]);

        $this->assertTrue($role1->is_default);
        $this->assertFalse($role2->is_default);
    }

    public function test_get_default_role(): void
    {
        Role::create(['name' => 'Admin', 'slug' => 'admin', 'is_default' => false]);
        $defaultRole = Role::create(['name' => 'User', 'slug' => 'user', 'is_default' => true]);

        $this->assertEquals($defaultRole->id, Role::getDefault()->id);
    }

    public function test_give_permissions_to_role(): void
    {
        $role = Role::create(['name' => 'Manager', 'slug' => 'manager']);
        Permission::create(['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'group' => 'dashboard']);
        Permission::create(['name' => 'Edit Settings', 'slug' => 'settings.edit', 'group' => 'settings']);

        $role->givePermissions(['dashboard.view', 'settings.edit']);

        $this->assertTrue($role->hasPermission('dashboard.view'));
        $this->assertTrue($role->hasPermission('settings.edit'));
    }

    public function test_revoke_permissions_from_role(): void
    {
        $role = Role::create(['name' => 'Staff', 'slug' => 'staff']);
        $permission1 = Permission::create(['name' => 'View Users', 'slug' => 'users.view', 'group' => 'users']);
        $permission2 = Permission::create(['name' => 'Edit Users', 'slug' => 'users.edit', 'group' => 'users']);

        $role->givePermissions(['users.view', 'users.edit']);
        $this->assertCount(2, $role->fresh()->permissions);

        $role->revokePermissions(['users.edit']);
        $this->assertCount(1, $role->fresh()->permissions);
        $this->assertTrue($role->hasPermission('users.view'));
        $this->assertFalse($role->hasPermission('users.edit'));
    }

    public function test_has_any_permission(): void
    {
        $role = Role::create(['name' => 'Operator', 'slug' => 'operator']);
        Permission::create(['name' => 'View Reports', 'slug' => 'reports.view', 'group' => 'reports']);

        $role->givePermissions(['reports.view']);

        $this->assertTrue($role->hasAnyPermission(['reports.view', 'reports.edit']));
        $this->assertFalse($role->hasAnyPermission(['reports.delete', 'reports.create']));
    }

    public function test_has_all_permissions(): void
    {
        $role = Role::create(['name' => 'Supervisor', 'slug' => 'supervisor']);
        Permission::create(['name' => 'View Orders', 'slug' => 'orders.view', 'group' => 'orders']);
        Permission::create(['name' => 'Edit Orders', 'slug' => 'orders.edit', 'group' => 'orders']);

        $role->givePermissions(['orders.view', 'orders.edit']);

        $this->assertTrue($role->hasAllPermissions(['orders.view', 'orders.edit']));
        $this->assertFalse($role->hasAllPermissions(['orders.view', 'orders.delete']));
    }
}
