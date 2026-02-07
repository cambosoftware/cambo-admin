# CheckRole Middleware

The `CheckRole` middleware protects routes based on user roles.

## Registration

The middleware is automatically registered as `role` during package installation.

## Usage

### Single Role

```php
// In routes
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/settings', [SettingsController::class, 'index']);
});

// On specific route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('role:admin');
```

### Multiple Roles (OR Logic)

Allow access if user has **any** of the specified roles:

```php
Route::middleware('role:admin,editor,moderator')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
});
```

### In Controllers

```php
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }
}

// Or on specific methods
class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:editor')->only(['create', 'store']);
        $this->middleware('role:admin')->only(['destroy']);
    }
}
```

## Response Behavior

When a user doesn't have the required role:

- **Web requests**: Redirects to 403 Forbidden page
- **API requests**: Returns JSON with 403 status

```json
{
    "message": "You do not have the required role to access this resource."
}
```

## Combining with Permission Middleware

```php
Route::middleware(['role:admin', 'permission:settings.edit'])->group(function () {
    // User must have 'admin' role AND 'settings.edit' permission
});
```

## Custom Redirect

To customize the unauthorized response, publish and modify the middleware:

```bash
php artisan vendor:publish --tag=cambo-admin-middleware
```

## Source Code

**Location:** `src/Http/Middleware/CheckRole.php`

```php
public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (!$request->user()) {
        abort(403, 'Unauthenticated.');
    }

    foreach ($roles as $role) {
        if ($request->user()->hasRole($role)) {
            return $next($request);
        }
    }

    abort(403, 'You do not have the required role.');
}
```
