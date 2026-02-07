# SetLocale Middleware

The `SetLocale` middleware automatically sets the application locale based on user preferences or browser settings.

## Registration

The middleware is automatically registered during package installation and applied to all admin routes.

## How It Works

The middleware determines the locale in this priority order:

1. **Session**: If `locale` is set in the session
2. **User Preference**: If the user has a `locale` attribute
3. **Browser**: From the `Accept-Language` header
4. **Default**: Falls back to `config('app.locale')`

## Usage

### Automatic (Default)

The middleware is automatically applied to all CamboAdmin routes:

```php
// config/cambo-admin.php
'routes' => [
    'middleware' => ['web', 'auth', 'set-locale'],
],
```

### Manual Application

Apply to specific routes:

```php
Route::middleware('set-locale')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

## Changing Locale

### Via Session

```php
// In a controller
public function setLocale(Request $request, string $locale)
{
    if (in_array($locale, ['en', 'fr', 'es', 'de'])) {
        session(['locale' => $locale]);
    }

    return back();
}
```

### Via User Model

Add a `locale` column to your users table:

```php
// Migration
$table->string('locale', 5)->default('en');

// Usage
$user->update(['locale' => 'fr']);
```

## Supported Locales

Configure supported locales in the config file:

```php
// config/cambo-admin.php
'locales' => [
    'en' => ['name' => 'English', 'native' => 'English', 'rtl' => false],
    'fr' => ['name' => 'French', 'native' => 'Francais', 'rtl' => false],
    'es' => ['name' => 'Spanish', 'native' => 'Espanol', 'rtl' => false],
    'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'rtl' => true],
],
```

## RTL Support

For right-to-left languages (Arabic, Hebrew), the middleware sets a `direction` attribute:

```php
// In your layout
<html lang="{{ app()->getLocale() }}" dir="{{ session('direction', 'ltr') }}">
```

## Frontend Integration

Use the locale in your Vue components:

```vue
<script setup>
import { usePage } from '@inertiajs/vue3'

const locale = usePage().props.locale
</script>
```

## Source Code

**Location:** `src/Http/Middleware/SetLocale.php`

```php
public function handle(Request $request, Closure $next): Response
{
    $locale = session('locale')
        ?? $request->user()?->locale
        ?? $request->getPreferredLanguage(array_keys(config('cambo-admin.locales')))
        ?? config('app.locale');

    app()->setLocale($locale);

    // Set direction for RTL languages
    $localeConfig = config("cambo-admin.locales.{$locale}");
    session(['direction' => $localeConfig['rtl'] ?? false ? 'rtl' : 'ltr']);

    return $next($request);
}
```
