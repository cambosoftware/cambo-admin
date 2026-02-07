# Security Best Practices

This guide covers security best practices for CamboAdmin applications, including authentication, authorization, data protection, and common vulnerability prevention.

## Authentication

### Password Requirements

Configure strong password requirements in `config/cambo-admin.php`:

```php
'auth' => [
    'password' => [
        'min_length' => 12,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'prevent_common' => true,
    ],
],
```

Implement in validation:

```php
use Illuminate\Validation\Rules\Password;

$request->validate([
    'password' => [
        'required',
        'confirmed',
        Password::min(12)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->uncompromised(),
    ],
]);
```

### Two-Factor Authentication

Enable 2FA for admin accounts:

```php
<?php
// app/Http/Controllers/Auth/TwoFactorController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function enable(Request $request)
    {
        $google2fa = new Google2FA();
        $user = $request->user();

        $secret = $google2fa->generateSecretKey();

        $user->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_enabled' => false,
        ]);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return response()->json([
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $google2fa = new Google2FA();
        $user = $request->user();

        $valid = $google2fa->verifyKey(
            decrypt($user->two_factor_secret),
            $request->code
        );

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        $user->update([
            'two_factor_enabled' => true,
            'two_factor_confirmed_at' => now(),
        ]);

        return redirect()->route('settings.security')
            ->with('success', 'Two-factor authentication enabled.');
    }
}
```

### Session Security

Configure secure session settings in `config/session.php`:

```php
return [
    'driver' => env('SESSION_DRIVER', 'database'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => true,
    'secure' => env('SESSION_SECURE_COOKIE', true),
    'http_only' => true,
    'same_site' => 'lax',
];
```

Implement session management:

```php
<?php
// app/Http/Controllers/Auth/SessionController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $sessions = DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($request) {
                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => $session->last_activity,
                    'is_current' => $session->id === $request->session()->getId(),
                ];
            });

        return response()->json(['sessions' => $sessions]);
    }

    public function destroy(Request $request, string $sessionId)
    {
        DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->where('id', $sessionId)
            ->delete();

        return response()->json(['message' => 'Session terminated.']);
    }

    public function destroyAll(Request $request)
    {
        DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return response()->json(['message' => 'All other sessions terminated.']);
    }
}
```

### Login Throttling

Implement rate limiting for login attempts:

```php
<?php
// app/Http/Controllers/Auth/LoginController.php

use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\RateLimiter as Limiter;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            Limiter::hit($this->throttleKey($request), 300); // 5 minutes

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        Limiter::clear($this->throttleKey($request));

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (!Limiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = Limiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}
```

## Authorization

### Role-Based Access Control

Implement RBAC with the HasRoles trait:

```php
<?php
// app/Models/Traits/HasRoles.php

namespace App\Models\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            return $this->roles->contains('name', $roles);
        }

        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }

    public function hasPermission(string $permission): bool
    {
        // Check direct permissions
        if ($this->permissions->contains('name', $permission)) {
            return true;
        }

        // Check role permissions
        return $this->roles->flatMap->permissions->contains('name', $permission);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }
}
```

### Policy-Based Authorization

Create policies for fine-grained control:

```php
<?php
// app/Policies/UserPolicy.php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users.view');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermission('users.view') || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('users.create');
    }

    public function update(User $user, User $model): bool
    {
        // Users can edit themselves
        if ($user->id === $model->id) {
            return true;
        }

        // Otherwise need permission
        if (!$user->hasPermission('users.update')) {
            return false;
        }

        // Cannot edit users with higher roles
        if ($model->isSuperAdmin() && !$user->isSuperAdmin()) {
            return false;
        }

        return true;
    }

    public function delete(User $user, User $model): bool
    {
        // Cannot delete yourself
        if ($user->id === $model->id) {
            return false;
        }

        // Cannot delete super admin
        if ($model->isSuperAdmin()) {
            return false;
        }

        return $user->hasPermission('users.delete');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasPermission('users.delete');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }
}
```

Register policies in `AuthServiceProvider`:

```php
protected $policies = [
    User::class => UserPolicy::class,
    Role::class => RolePolicy::class,
    // Add more policies
];
```

### Vue Authorization

Share permissions with Vue:

```php
// app/Http/Middleware/HandleInertiaRequests.php
public function share(Request $request): array
{
    return array_merge(parent::share($request), [
        'auth' => [
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ] : null,
            'permissions' => $request->user()?->getAllPermissions()->pluck('name') ?? [],
            'roles' => $request->user()?->roles->pluck('name') ?? [],
        ],
    ]);
}
```

Create authorization composable:

```javascript
// resources/js/Composables/useAuth.js
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function useAuth() {
    const page = usePage()

    const user = computed(() => page.props.auth?.user)
    const permissions = computed(() => page.props.auth?.permissions ?? [])
    const roles = computed(() => page.props.auth?.roles ?? [])

    const can = (permission) => {
        return permissions.value.includes(permission)
    }

    const canAny = (perms) => {
        return perms.some((p) => can(p))
    }

    const canAll = (perms) => {
        return perms.every((p) => can(p))
    }

    const hasRole = (role) => {
        return roles.value.includes(role)
    }

    const isAdmin = computed(() => {
        return hasRole('admin') || hasRole('super-admin')
    })

    const isSuperAdmin = computed(() => {
        return hasRole('super-admin')
    })

    return {
        user,
        permissions,
        roles,
        can,
        canAny,
        canAll,
        hasRole,
        isAdmin,
        isSuperAdmin,
    }
}
```

Usage in components:

```vue
<script setup>
import { useAuth } from '@/Composables/useAuth'

const { can, isAdmin } = useAuth()
</script>

<template>
    <div>
        <Button v-if="can('users.create')" href="/users/create">
            Add User
        </Button>

        <div v-if="isAdmin">
            <!-- Admin-only content -->
        </div>
    </div>
</template>
```

## Input Validation

### Request Validation

Always validate input:

```php
<?php
// app/Http/Requests/CreateUserRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already registered.',
            'role_id.exists' => 'Please select a valid role.',
        ];
    }
}
```

### Sanitization

Sanitize user input:

```php
<?php
// app/Http/Requests/CreatePostRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => strip_tags($this->title),
            'slug' => \Str::slug($this->slug ?? $this->title),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:posts,slug'],
            'content' => ['required', 'string'],
        ];
    }
}
```

## XSS Prevention

### Vue Auto-Escaping

Vue automatically escapes content. Use `v-html` only when necessary:

```vue
<!-- SAFE: Auto-escaped -->
<p>{{ userInput }}</p>

<!-- DANGEROUS: Only use with trusted/sanitized content -->
<div v-html="sanitizedHtml"></div>
```

### HTML Sanitization

When displaying user HTML content:

```javascript
// resources/js/utils/sanitize.js
import DOMPurify from 'dompurify'

export function sanitizeHtml(html) {
    return DOMPurify.sanitize(html, {
        ALLOWED_TAGS: ['b', 'i', 'em', 'strong', 'a', 'p', 'br', 'ul', 'ol', 'li'],
        ALLOWED_ATTR: ['href', 'target', 'rel'],
    })
}
```

```vue
<script setup>
import { computed } from 'vue'
import { sanitizeHtml } from '@/utils/sanitize'

const props = defineProps({
    content: String,
})

const safeContent = computed(() => sanitizeHtml(props.content))
</script>

<template>
    <div v-html="safeContent"></div>
</template>
```

## CSRF Protection

### Automatic Protection

Laravel includes CSRF protection by default. Ensure all forms include the token:

```blade
<form method="POST" action="/users">
    @csrf
    <!-- form fields -->
</form>
```

Inertia.js handles this automatically for XHR requests.

### API Token Authentication

For API routes, use token authentication instead of CSRF:

```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
});
```

## SQL Injection Prevention

### Use Eloquent ORM

Always use Eloquent or Query Builder:

```php
// SAFE: Eloquent
$users = User::where('email', $email)->get();

// SAFE: Query Builder with bindings
$users = DB::table('users')
    ->where('email', '=', $email)
    ->get();

// DANGEROUS: Raw query with user input
$users = DB::select("SELECT * FROM users WHERE email = '$email'"); // NEVER DO THIS
```

### When Raw Queries Are Needed

Use parameter bindings:

```php
// SAFE: Named bindings
$users = DB::select(
    'SELECT * FROM users WHERE email = :email AND status = :status',
    ['email' => $email, 'status' => 'active']
);

// SAFE: Positional bindings
$users = DB::select(
    'SELECT * FROM users WHERE email = ? AND status = ?',
    [$email, 'active']
);
```

## File Upload Security

### Validation

```php
$request->validate([
    'file' => [
        'required',
        'file',
        'mimes:jpg,jpeg,png,pdf',
        'max:10240', // 10MB
    ],
]);
```

### Secure Storage

```php
<?php
// app/Services/FileUploadService.php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function upload(UploadedFile $file, string $directory = 'uploads'): string
    {
        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Store file
        $path = Storage::disk('private')->putFileAs(
            $directory,
            $file,
            $filename
        );

        return $path;
    }

    public function validateMimeType(UploadedFile $file, array $allowedTypes): bool
    {
        // Check actual MIME type, not just extension
        $mimeType = $file->getMimeType();

        return in_array($mimeType, $allowedTypes, true);
    }

    public function scanForMalware(UploadedFile $file): bool
    {
        // Implement virus scanning if needed
        // Example using ClamAV
        // exec("clamscan {$file->getRealPath()}", $output, $returnCode);
        // return $returnCode === 0;

        return true;
    }
}
```

### Serving Private Files

```php
// routes/web.php
Route::get('/files/{path}', [FileController::class, 'show'])
    ->where('path', '.*')
    ->middleware('auth');

// FileController.php
public function show(Request $request, string $path)
{
    // Check authorization
    $this->authorize('viewFile', $path);

    // Serve file
    return Storage::disk('private')->response($path);
}
```

## Data Protection

### Encryption

Encrypt sensitive data:

```php
<?php
// app/Models/User.php

use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    protected function ssn(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => decrypt($value),
            set: fn ($value) => encrypt($value),
        );
    }
}
```

### Data Masking

Mask sensitive data in logs and displays:

```php
<?php
// app/Helpers/DataMasking.php

namespace App\Helpers;

class DataMasking
{
    public static function email(string $email): string
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';

        $maskedName = substr($name, 0, 2) . str_repeat('*', max(0, strlen($name) - 2));

        return "{$maskedName}@{$domain}";
    }

    public static function phone(string $phone): string
    {
        return substr($phone, 0, 3) . '****' . substr($phone, -4);
    }

    public static function creditCard(string $number): string
    {
        return '**** **** **** ' . substr($number, -4);
    }
}
```

## Activity Logging

### Log Security Events

```php
<?php
// app/Listeners/LogSecurityEvent.php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;

class LogSecurityEvent
{
    public function handleLogin(Login $event): void
    {
        Log::channel('security')->info('User logged in', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function handleLogout(Logout $event): void
    {
        Log::channel('security')->info('User logged out', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip' => request()->ip(),
        ]);
    }

    public function handleFailed(Failed $event): void
    {
        Log::channel('security')->warning('Login failed', [
            'email' => $event->credentials['email'] ?? 'unknown',
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
```

Register in `EventServiceProvider`:

```php
protected $listen = [
    \Illuminate\Auth\Events\Login::class => [
        \App\Listeners\LogSecurityEvent::class . '@handleLogin',
    ],
    \Illuminate\Auth\Events\Logout::class => [
        \App\Listeners\LogSecurityEvent::class . '@handleLogout',
    ],
    \Illuminate\Auth\Events\Failed::class => [
        \App\Listeners\LogSecurityEvent::class . '@handleFailed',
    ],
];
```

## Security Headers

Configure security headers in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\SecurityHeaders::class,
    ]);
})
```

```php
<?php
// app/Http/Middleware/SecurityHeaders.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        if (app()->environment('production')) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
```

## Security Checklist

### Before Production

- [ ] Environment is set to `production`
- [ ] Debug mode is disabled (`APP_DEBUG=false`)
- [ ] HTTPS is enforced
- [ ] All dependencies are up to date
- [ ] Sensitive data is encrypted
- [ ] Database backups are configured
- [ ] Rate limiting is enabled
- [ ] File permissions are restricted
- [ ] Error pages don't expose sensitive info
- [ ] Security headers are configured

### Regular Maintenance

- [ ] Review access logs weekly
- [ ] Update dependencies monthly
- [ ] Audit user permissions quarterly
- [ ] Test backup restoration quarterly
- [ ] Run security scans monthly
- [ ] Review and rotate API keys

## See Also

- [Customization](/guide/advanced/customization.md) - Customizing components
- [Extending](/guide/advanced/extending.md) - Adding features
- [Laravel Security](https://laravel.com/docs/security) - Official documentation
