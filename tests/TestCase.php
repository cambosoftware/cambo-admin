<?php

namespace CamboSoftware\CamboAdmin\Tests;

use CamboSoftware\CamboAdmin\CamboAdminServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create base tables needed for testing
        $this->setUpDatabase();
    }

    protected function setUpDatabase(): void
    {
        // Create users table first (required by other migrations)
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Create cache table
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        // Create sessions table
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // Create roles table
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        // Create permissions table
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('group')->nullable();
                $table->timestamps();
            });
        }

        // Create role_user pivot table
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        // Create permission_role pivot table (used by some code)
        if (!Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) {
                $table->id();
                $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
                $table->foreignId('role_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        // Create role_permission pivot table (used by Role model)
        if (!Schema::hasTable('role_permission')) {
            Schema::create('role_permission', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained()->cascadeOnDelete();
                $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        // Create user_role pivot table (used by Role model)
        if (!Schema::hasTable('user_role')) {
            Schema::create('user_role', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('role_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        // Create settings table (matching the Setting model)
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('group')->default('general');
                $table->string('key')->unique();
                $table->string('label')->nullable();
                $table->text('description')->nullable();
                $table->string('type')->default('text');
                $table->text('value')->nullable();
                $table->text('default_value')->nullable();
                $table->json('options')->nullable();
                $table->json('validation')->nullable();
                $table->boolean('is_public')->default(false);
                $table->boolean('is_encrypted')->default(false);
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        }

        // Create activity_logs table
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('log_name')->nullable();
                $table->text('description');
                $table->nullableMorphs('subject');
                $table->nullableMorphs('causer');
                $table->json('properties')->nullable();
                $table->timestamps();
            });
        }

        // Create notifications table
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // Create media table
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('file_name');
                $table->string('mime_type')->nullable();
                $table->string('path');
                $table->string('disk')->default('public');
                $table->unsignedBigInteger('size')->default(0);
                $table->nullableMorphs('model');
                $table->json('custom_properties')->nullable();
                $table->timestamps();
            });
        }
    }

    protected function getPackageProviders($app): array
    {
        return [
            CamboAdminServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Use SQLite in memory for testing
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set up CamboAdmin config
        $app['config']->set('cambo-admin.modules', [
            'auth' => true,
            'users' => true,
            'roles' => true,
            'permissions' => true,
            'notifications' => true,
            'activity-log' => true,
            'dashboard' => true,
            'media' => true,
            'settings' => true,
            'import-export' => true,
            'i18n' => true,
            'themes' => true,
        ]);

        $app['config']->set('cambo-admin.routes', [
            'enabled' => false, // Disable routes during testing
            'prefix' => 'admin',
            'middleware' => ['web', 'auth'],
            'as' => 'cambo.',
        ]);

        $app['config']->set('cambo-admin.migrations', [
            'enabled' => false, // Don't auto-run migrations in tests
        ]);

        // Set supported locales for translation tests
        $app['config']->set('app.supported_locales', ['en', 'fr', 'es', 'ar']);
        $app['config']->set('app.locale', 'en');
    }

    /**
     * Define routes setup for tests.
     */
    protected function defineRoutes($router): void
    {
        // Define a login route for middleware tests
        $router->get('/login', function () {
            return 'Login Page';
        })->name('login');
    }

    /**
     * Create a test user.
     */
    protected function createUser(array $attributes = []): \Illuminate\Foundation\Auth\User
    {
        $userClass = config('cambo-admin.models.user') ?? \Illuminate\Foundation\Auth\User::class;

        return $userClass::create(array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ], $attributes));
    }
}
