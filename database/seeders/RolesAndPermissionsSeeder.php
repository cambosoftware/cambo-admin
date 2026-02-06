<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Users
            ['name' => 'View users', 'slug' => 'users.view', 'group' => 'Users'],
            ['name' => 'Create users', 'slug' => 'users.create', 'group' => 'Users'],
            ['name' => 'Edit users', 'slug' => 'users.edit', 'group' => 'Users'],
            ['name' => 'Delete users', 'slug' => 'users.delete', 'group' => 'Users'],

            // Roles
            ['name' => 'View roles', 'slug' => 'roles.view', 'group' => 'Roles'],
            ['name' => 'Create roles', 'slug' => 'roles.create', 'group' => 'Roles'],
            ['name' => 'Edit roles', 'slug' => 'roles.edit', 'group' => 'Roles'],
            ['name' => 'Delete roles', 'slug' => 'roles.delete', 'group' => 'Roles'],

            // Settings
            ['name' => 'View settings', 'slug' => 'settings.view', 'group' => 'Settings'],
            ['name' => 'Edit settings', 'slug' => 'settings.edit', 'group' => 'Settings'],

            // Reports
            ['name' => 'View reports', 'slug' => 'reports.view', 'group' => 'Reports'],
            ['name' => 'Export reports', 'slug' => 'reports.export', 'group' => 'Reports'],

            // Dashboard
            ['name' => 'View dashboard', 'slug' => 'dashboard.view', 'group' => 'Dashboard'],
            ['name' => 'Customize dashboard', 'slug' => 'dashboard.customize', 'group' => 'Dashboard'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['slug' => $permission['slug']], $permission);
        }

        // Create roles
        $superAdmin = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Administrator',
                'description' => 'Full access to all features',
                'is_default' => false,
            ]
        );

        $admin = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'User and settings management',
                'is_default' => false,
            ]
        );

        $editor = Role::firstOrCreate(
            ['slug' => 'editor'],
            [
                'name' => 'Editor',
                'description' => 'Can edit content',
                'is_default' => false,
            ]
        );

        $user = Role::firstOrCreate(
            ['slug' => 'user'],
            [
                'name' => 'User',
                'description' => 'Basic read access',
                'is_default' => true,
            ]
        );

        // Assign permissions to roles
        // Super Admin gets all permissions automatically (handled in trait)

        // Admin permissions
        $admin->syncPermissions([
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.view',
            'settings.view', 'settings.edit',
            'reports.view', 'reports.export',
            'dashboard.view', 'dashboard.customize',
        ]);

        // Editor permissions
        $editor->syncPermissions([
            'users.view',
            'reports.view',
            'dashboard.view',
        ]);

        // User permissions
        $user->syncPermissions([
            'dashboard.view',
        ]);

        // Assign super-admin role to first user if exists
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasRole('super-admin')) {
            $firstUser->assignRole('super-admin');
        }
    }
}
