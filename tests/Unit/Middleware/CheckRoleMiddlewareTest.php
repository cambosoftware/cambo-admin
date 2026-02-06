<?php

namespace CamboSoftware\CamboAdmin\Tests\Unit\Middleware;

use CamboSoftware\CamboAdmin\Http\Middleware\CheckRole;
use CamboSoftware\CamboAdmin\Models\Role;
use CamboSoftware\CamboAdmin\Models\Traits\HasRoles;
use CamboSoftware\CamboAdmin\Tests\TestCase;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddlewareTest extends TestCase
{
    protected CheckRole $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new CheckRole();
    }

    public function test_guest_is_redirected(): void
    {
        $request = Request::create('/admin', 'GET');

        $response = $this->middleware->handle($request, function () {
            return response('OK');
        }, 'admin');

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }

    public function test_user_with_correct_role_can_pass(): void
    {
        $user = MiddlewareTestUser::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $user->roles()->attach($role);

        $this->actingAs($user);

        $request = Request::create('/admin', 'GET');
        $request->setUserResolver(fn() => $user);

        $response = $this->middleware->handle($request, function () {
            return response('OK');
        }, 'admin');

        $this->assertEquals('OK', $response->getContent());
    }

    public function test_user_without_role_gets_403(): void
    {
        $user = MiddlewareTestUser::create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        $request = Request::create('/admin', 'GET');
        $request->setUserResolver(fn() => $user);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $this->middleware->handle($request, function () {
            return response('OK');
        }, 'admin');
    }

    public function test_user_with_any_of_multiple_roles_can_pass(): void
    {
        $user = MiddlewareTestUser::create([
            'name' => 'Editor',
            'email' => 'editor@test.com',
            'password' => bcrypt('password'),
        ]);

        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $editor = Role::create(['name' => 'Editor', 'slug' => 'editor']);
        $user->roles()->attach($editor);

        $this->actingAs($user);

        $request = Request::create('/content', 'GET');
        $request->setUserResolver(fn() => $user);

        $response = $this->middleware->handle($request, function () {
            return response('OK');
        }, 'admin', 'editor');

        $this->assertEquals('OK', $response->getContent());
    }
}

/**
 * Test User model for middleware tests
 */
class MiddlewareTestUser extends Authenticatable
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
