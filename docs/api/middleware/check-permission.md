# CheckPermission Middleware

The `CheckPermission` middleware protects routes based on user permissions.

## Registration

The middleware is automatically registered as `permission` during package installation.

## Usage

### Single Permission

```php
Route::middleware('permission:users.create')->group(function () {
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users', [UserController::class, 'store']);
});
```

### Multiple Permissions (OR Logic)

Allow access if user has **any** of the specified permissions:

```php
Route::middleware('permission:posts.edit,posts.delete')->group(function () {
    Route::resource('/posts', PostController::class)->except(['index', 'show']);
});
```

### On Specific Routes

```php
Route::delete('/posts/{post}', [PostController::class, 'destroy'])
    ->middleware('permission:posts.delete');
```

### In Controllers

```php
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.edit')->only(['edit', 'update']);
        $this->middleware('permission:users.delete')->only(['destroy']);
    }
}
```

## Permission Naming Convention

We recommend using the `resource.action` naming pattern:

| Permission | Description |
|------------|-------------|
| `users.view` | View users list and details |
| `users.create` | Create new users |
| `users.edit` | Edit existing users |
| `users.delete` | Delete users |
| `settings.view` | View settings |
| `settings.edit` | Modify settings |

## Response Behavior

When a user doesn't have the required permission:

- **Web requests**: Redirects to 403 Forbidden page
- **API requests**: Returns JSON with 403 status

```json
{
    "message": "You do not have the required permission to perform this action."
}
```

## Super Admin Bypass

Users with the `super-admin` role automatically pass all permission checks.

## Combining Middleware

```php
// User must be authenticated, have admin role, AND have the permission
Route::middleware(['auth', 'role:admin', 'permission:users.delete'])->group(function () {
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});
```

## Source Code

**Location:** `src/Http/Middleware/CheckPermission.php`

```php
public function handle(Request $request, Closure $next, ...$permissions): Response
{
    if (!$request->user()) {
        abort(403, 'Unauthenticated.');
    }

    foreach ($permissions as $permission) {
        if ($request->user()->hasPermission($permission)) {
            return $next($request);
        }
    }

    abort(403, 'You do not have the required permission.');
}
```
