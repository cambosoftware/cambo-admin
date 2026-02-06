<?php

namespace CamboSoftware\CamboAdmin\Tests\Unit\Middleware;

use CamboSoftware\CamboAdmin\Http\Middleware\CheckPermission;
use CamboSoftware\CamboAdmin\Models\Role;
use CamboSoftware\CamboAdmin\Models\Permission;
use CamboSoftware\CamboAdmin\Models\Traits\HasRoles;
use CamboSoftware\CamboAdmin\Tests\TestCase;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddlewareTest extends TestCase
{
    protected CheckPermission $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new CheckPermission();
    }

    public function test_guest_is_redirected(): void
    {
        $request = Request::create('/users', 'GET');

        $response = $this->middleware->handle($request, function () {
            return response('OK');
        }, 'users.view');

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }

    public function test_user_with_permission_can_pass(): void
    {
        $user = PermissionTestUser::create([
            'name' => 'Manager',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
        ]);

        $role = Role::create(['name' => 'Manager', 'slug' => 'manager']);
        $permission = Permission::create([
            'name' => 'View Users',
            'slug' => 'users.view',
            'group' => 'users',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->actingAs($user);

        $request = Request::create('/users', 'GET');
        $request->setUserResolver(fn() => $user);

        $response = $this->middleware->handle($request, function () {
            return response('OK');
        }, 'users.view');

        $this->assertEquals('OK', $response->getContent());
    }

    public function test_user_without_permission_gets_403(): void
    {
        $user = PermissionTestUser::create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);

        $role = Role::create(['name' => 'User', 'slug' => 'user']);
        $user->roles()->attach($role);

        $this->actingAs($user);

        $request = Request::create('/users', 'DELETE');
        $request->setUserResolver(fn() => $user);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $this->middleware->handle($request, function () {
            return response('OK');
        }, 'users.delete');
    }

    public function test_user_with_any_of_multiple_permissions_can_pass(): void
    {
        $user = PermissionTestUser::create([
            'name' => 'Editor',
            'email' => 'editor@test.com',
            'password' => bcrypt('password'),
        ]);

        $role = Role::create(['name' => 'Editor', 'slug' => 'editor']);
        $permission = Permission::create([
            'name' => 'Edit Posts',
            'slug' => 'posts.edit',
            'group' => 'posts',
        ]);
        Permission::create([
            'name' => 'Delete Posts',
            'slug' => 'posts.delete',
            'group' => 'posts',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->actingAs($user);

        $request = Request::create('/posts/1', 'PUT');
        $request->setUserResolver(fn() => $user);

        $response = $this->middleware->handle($request, function () {
            return response('OK');
        }, 'posts.edit', 'posts.delete');

        $this->assertEquals('OK', $response->getContent());
    }

    public function test_super_admin_has_all_permissions(): void
    {
        $user = PermissionTestUser::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'password' => bcrypt('password'),
        ]);

        $superAdminRole = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin']);
        $user->roles()->attach($superAdminRole);

        $this->actingAs($user);

        $request = Request::create('/admin/settings', 'POST');
        $request->setUserResolver(fn() => $user);

        $response = $this->middleware->handle($request, function () {
            return response('OK');
        }, 'settings.manage');

        $this->assertEquals('OK', $response->getContent());
    }
}

/**
 * Test User model for permission middleware tests
 */
class PermissionTestUser extends Authenticatable
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
