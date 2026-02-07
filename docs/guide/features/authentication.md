# Authentication

CamboAdmin provides a complete authentication system with modern security features including login, registration, two-factor authentication, password reset, and session management.

## Introduction

The authentication module is the foundation of CamboAdmin's security system. It offers:

- Email/password login
- User registration with email verification
- Two-factor authentication (2FA) with TOTP
- Password reset via email
- Session management
- Remember me functionality
- Social login integration (optional)

## Configuration

### Enable/Disable Module

```php
// config/cambo-admin.php
'modules' => [
    'auth' => true,
],
```

### Feature Toggles

```php
// config/cambo-admin.php
'features' => [
    'registration' => true,        // Allow new user registration
    'password_reset' => true,      // Enable password reset
    'two_factor' => true,          // Enable 2FA
    'email_verification' => true,  // Require email verification
    'remember_me' => true,         // Show "Remember me" checkbox
    'social_login' => false,       // Enable OAuth providers
],
```

### Environment Variables

```env
# .env
CAMBO_REGISTRATION=true
CAMBO_TWO_FACTOR=true
CAMBO_EMAIL_VERIFICATION=true
```

## Usage Examples

### Basic Login

The login form is available at `/admin/login` and handles authentication automatically.

```php
// Custom login controller if needed
use Illuminate\Support\Facades\Auth;

public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/admin');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}
```

### User Registration

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use CamboSoftware\CamboAdmin\Models\Role;

// Create a new user
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password'),
]);

// Assign default role
$defaultRole = Role::getDefault();
if ($defaultRole) {
    $user->assignRole($defaultRole);
}

// Send email verification
$user->sendEmailVerificationNotification();
```

### Two-Factor Authentication

#### Enable 2FA for a User

```php
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

// Generate secret key
$google2fa = new Google2FA();
$secret = $google2fa->generateSecretKey();

// Store encrypted secret
$user->update([
    'two_factor_secret' => encrypt($secret),
    'two_factor_recovery_codes' => encrypt(json_encode(
        collect(range(1, 8))->map(fn () => Str::random(10))->all()
    )),
]);
```

#### Verify 2FA Code

```php
$google2fa = new Google2FA();
$secret = decrypt($user->two_factor_secret);

$valid = $google2fa->verifyKey($secret, $request->code);

if ($valid) {
    // Code is valid, complete login
    session()->put('auth.two_factor_confirmed', true);
}
```

#### Generate QR Code

```php
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

$google2fa = new Google2FA();
$qrCodeUrl = $google2fa->getQRCodeUrl(
    config('app.name'),
    $user->email,
    decrypt($user->two_factor_secret)
);

$renderer = new ImageRenderer(
    new RendererStyle(200),
    new SvgImageBackEnd()
);

$writer = new Writer($renderer);
$qrCodeSvg = $writer->writeString($qrCodeUrl);
```

#### Recovery Codes

```php
// Get recovery codes
$recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

// Verify a recovery code
$validRecoveryCode = in_array($request->recovery_code, $recoveryCodes);

if ($validRecoveryCode) {
    // Remove used code
    $recoveryCodes = array_diff($recoveryCodes, [$request->recovery_code]);
    $user->update([
        'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes))),
    ]);
}
```

### Password Reset

```php
use Illuminate\Support\Facades\Password;

// Send reset link
$status = Password::sendResetLink(
    $request->only('email')
);

if ($status === Password::RESET_LINK_SENT) {
    return back()->with('status', __($status));
}

// Reset password
$status = Password::reset(
    $request->only('email', 'password', 'password_confirmation', 'token'),
    function ($user, $password) {
        $user->forceFill([
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();
    }
);
```

### Session Management

```php
use Illuminate\Support\Facades\DB;

// Get all sessions for current user
$sessions = DB::table('sessions')
    ->where('user_id', auth()->id())
    ->orderBy('last_activity', 'desc')
    ->get()
    ->map(function ($session) {
        return [
            'id' => $session->id,
            'ip_address' => $session->ip_address,
            'user_agent' => $session->user_agent,
            'last_active' => Carbon::createFromTimestamp($session->last_activity),
            'is_current' => $session->id === session()->getId(),
        ];
    });

// Logout from specific session
DB::table('sessions')
    ->where('id', $sessionId)
    ->where('user_id', auth()->id())
    ->delete();

// Logout from all other sessions
DB::table('sessions')
    ->where('user_id', auth()->id())
    ->where('id', '!=', session()->getId())
    ->delete();
```

### Social Login (OAuth)

```php
// config/services.php
'github' => [
    'client_id' => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect' => env('GITHUB_REDIRECT_URI'),
],

// Controller
use Laravel\Socialite\Facades\Socialite;

// Redirect to provider
public function redirectToProvider($provider)
{
    return Socialite::driver($provider)->redirect();
}

// Handle callback
public function handleProviderCallback($provider)
{
    $socialUser = Socialite::driver($provider)->user();

    $user = User::firstOrCreate(
        ['email' => $socialUser->getEmail()],
        [
            'name' => $socialUser->getName(),
            'password' => Hash::make(Str::random(24)),
            'email_verified_at' => now(),
        ]
    );

    Auth::login($user);

    return redirect('/admin');
}
```

## Available Options

### Login Form Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `remember` | boolean | false | Enable "Remember me" |
| `throttle_attempts` | integer | 5 | Max login attempts before lockout |
| `throttle_decay` | integer | 60 | Lockout duration in seconds |

### Registration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `require_email_verification` | boolean | true | Require email verification |
| `default_role` | string | 'user' | Default role for new users |
| `password_min_length` | integer | 8 | Minimum password length |

### Two-Factor Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `window` | integer | 1 | Verification window (30-second intervals) |
| `recovery_codes_count` | integer | 8 | Number of recovery codes |
| `recovery_code_length` | integer | 10 | Length of each recovery code |

### Session Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `lifetime` | integer | 120 | Session lifetime in minutes |
| `expire_on_close` | boolean | false | Expire session on browser close |
| `encrypt` | boolean | false | Encrypt session data |

## Middleware

### Protect Routes with Authentication

```php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index']);
});
```

### Require Email Verification

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index']);
});
```

### Guest Only Routes

```php
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm']);
});
```

## Events

CamboAdmin fires events during authentication that you can listen to:

```php
// EventServiceProvider.php
protected $listen = [
    \Illuminate\Auth\Events\Login::class => [
        \App\Listeners\LogSuccessfulLogin::class,
    ],
    \Illuminate\Auth\Events\Failed::class => [
        \App\Listeners\LogFailedLogin::class,
    ],
    \Illuminate\Auth\Events\Logout::class => [
        \App\Listeners\LogSuccessfulLogout::class,
    ],
    \Illuminate\Auth\Events\Registered::class => [
        \Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
    ],
    \Illuminate\Auth\Events\PasswordReset::class => [
        \App\Listeners\LogPasswordReset::class,
    ],
];
```

## Customization

### Custom Login View

Publish and customize the login view:

```bash
php artisan vendor:publish --tag=cambo-admin-views
```

Then edit `resources/views/vendor/cambo-admin/auth/login.blade.php`.

### Custom Login Logic

Override the login controller:

```php
// app/Http/Controllers/Auth/LoginController.php
namespace App\Http\Controllers\Auth;

use CamboSoftware\CamboAdmin\Http\Controllers\Auth\LoginController as BaseLoginController;

class LoginController extends BaseLoginController
{
    protected function authenticated($request, $user)
    {
        // Custom post-login logic
        Activity::log('User logged in')
            ->by($user)
            ->withRequest()
            ->save();

        return redirect()->intended($this->redirectPath());
    }
}
```

## Security Best Practices

1. **Always use HTTPS** in production
2. **Enable 2FA** for admin users
3. **Set reasonable session timeouts**
4. **Use strong password policies**
5. **Monitor failed login attempts**
6. **Implement rate limiting**

```php
// Rate limiting in RouteServiceProvider
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});
```
