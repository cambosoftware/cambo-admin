# TranslationService

The `TranslationService` manages multi-language translations and internationalization for CamboAdmin.

## Description

TranslationService provides complete translation management with support for multiple locales, RTL languages, translation import/export, missing translation detection, and cache management.

## Usage

```php
use CamboSoftware\CamboAdmin\Services\TranslationService;

$translationService = app(TranslationService::class);
```

## Methods

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `getTranslations()` | `string $locale` | `array` | Get all translations for a locale |
| `getSupportedLocales()` | none | `array` | Get supported locales with metadata |
| `isRtl()` | `string $locale` | `bool` | Check if locale is RTL |
| `setLocale()` | `string $locale` | `void` | Set the current locale |
| `getCurrentLocale()` | none | `string` | Get the current locale |
| `saveTranslation()` | `string $locale, string $group, string $key, string $value` | `bool` | Save a translation |
| `deleteTranslation()` | `string $locale, string $group, string $key` | `bool` | Delete a translation |
| `getMissingTranslations()` | `string $locale, string $referenceLocale = 'en'` | `array` | Get missing translations |
| `exportToJson()` | `string $locale` | `string` | Export translations as JSON |
| `importFromJson()` | `string $locale, string $json` | `int` | Import translations from JSON |
| `createLocale()` | `string $locale, string $copyFrom = 'en'` | `bool` | Create a new locale |

## Method Details

### getTranslations()

```php
public function getTranslations(string $locale): array
```

Returns all translations for a locale. Results are cached for 1 hour.

**Parameters:**
- `$locale` - Locale code (e.g., 'en', 'fr')

**Example:**

```php
$translations = $translationService->getTranslations('en');
// Returns: ['auth' => [...], 'validation' => [...], ...]
```

### getSupportedLocales()

```php
public function getSupportedLocales(): array
```

Returns supported locales with full metadata including name, native name, RTL flag, and emoji flag.

**Example:**

```php
$locales = $translationService->getSupportedLocales();
// Returns:
// [
//     'en' => ['name' => 'English', 'native' => 'English', 'rtl' => false, 'flag' => '...'],
//     'fr' => ['name' => 'French', 'native' => 'Francais', 'rtl' => false, 'flag' => '...'],
//     'ar' => ['name' => 'Arabic', 'native' => '...', 'rtl' => true, 'flag' => '...'],
// ]
```

**Supported Locales:**

| Code | Language | RTL |
|------|----------|-----|
| `en` | English | No |
| `fr` | French | No |
| `es` | Spanish | No |
| `de` | German | No |
| `it` | Italian | No |
| `pt` | Portuguese | No |
| `nl` | Dutch | No |
| `ru` | Russian | No |
| `zh` | Chinese | No |
| `ja` | Japanese | No |
| `ko` | Korean | No |
| `ar` | Arabic | Yes |
| `he` | Hebrew | Yes |
| `fa` | Persian | Yes |

### isRtl()

```php
public function isRtl(string $locale): bool
```

Checks if a locale uses right-to-left text direction.

**Parameters:**
- `$locale` - Locale code

**Example:**

```php
$isRtl = $translationService->isRtl('ar'); // true
$isRtl = $translationService->isRtl('en'); // false
```

### setLocale()

```php
public function setLocale(string $locale): void
```

Sets the current locale in the application and session.

**Parameters:**
- `$locale` - Locale code to set

**Example:**

```php
$translationService->setLocale('fr');
// App::getLocale() now returns 'fr'
```

### getCurrentLocale()

```php
public function getCurrentLocale(): string
```

Returns the current locale from session or app config.

**Example:**

```php
$locale = $translationService->getCurrentLocale();
// Returns: 'en'
```

### saveTranslation()

```php
public function saveTranslation(string $locale, string $group, string $key, string $value): bool
```

Saves a translation value. Creates the locale directory if it doesn't exist.

**Parameters:**
- `$locale` - Locale code
- `$group` - Translation group (e.g., 'auth', 'validation')
- `$key` - Translation key (supports dot notation)
- `$value` - Translation value

**Example:**

```php
$translationService->saveTranslation('fr', 'auth', 'login', 'Connexion');
$translationService->saveTranslation('fr', 'validation', 'required', 'Le champ :attribute est requis.');
```

### deleteTranslation()

```php
public function deleteTranslation(string $locale, string $group, string $key): bool
```

Deletes a translation from a locale.

**Parameters:**
- `$locale` - Locale code
- `$group` - Translation group
- `$key` - Translation key

**Returns:** `true` if deleted, `false` if not found

**Example:**

```php
$translationService->deleteTranslation('fr', 'auth', 'deprecated_key');
```

### getMissingTranslations()

```php
public function getMissingTranslations(string $locale, string $referenceLocale = 'en'): array
```

Compares a locale against a reference locale and returns missing translations.

**Parameters:**
- `$locale` - Locale to check
- `$referenceLocale` - Reference locale (default: 'en')

**Example:**

```php
$missing = $translationService->getMissingTranslations('fr', 'en');
// Returns:
// [
//     ['key' => 'auth.verify', 'reference_value' => 'Verify Email'],
//     ['key' => 'validation.new_rule', 'reference_value' => 'The :attribute is invalid.'],
// ]
```

### exportToJson()

```php
public function exportToJson(string $locale): string
```

Exports all translations for a locale as formatted JSON.

**Parameters:**
- `$locale` - Locale code

**Example:**

```php
$json = $translationService->exportToJson('en');
// Returns formatted JSON string with all translations
```

### importFromJson()

```php
public function importFromJson(string $locale, string $json): int
```

Imports translations from a JSON string.

**Parameters:**
- `$locale` - Target locale
- `$json` - JSON string with translations

**Returns:** Number of imported translations

**Example:**

```php
$json = file_get_contents('translations_fr.json');
$count = $translationService->importFromJson('fr', $json);
// Returns: 150 (number of imported translations)
```

### createLocale()

```php
public function createLocale(string $locale, string $copyFrom = 'en'): bool
```

Creates a new locale by copying translations from an existing locale.

**Parameters:**
- `$locale` - New locale code
- `$copyFrom` - Source locale to copy from (default: 'en')

**Example:**

```php
$translationService->createLocale('es', 'en');
// Creates resources/lang/es/ with copies of English translations
```

## Complete Usage Example

```php
use CamboSoftware\CamboAdmin\Services\TranslationService;

class LocalizationController extends Controller
{
    public function __construct(
        protected TranslationService $translationService
    ) {}

    public function index()
    {
        return response()->json([
            'locales' => $this->translationService->getSupportedLocales(),
            'current' => $this->translationService->getCurrentLocale(),
        ]);
    }

    public function setLocale(Request $request)
    {
        $this->translationService->setLocale($request->locale);
        return back();
    }

    public function translations(string $locale)
    {
        return response()->json([
            'translations' => $this->translationService->getTranslations($locale),
            'isRtl' => $this->translationService->isRtl($locale),
        ]);
    }

    public function save(Request $request)
    {
        $this->translationService->saveTranslation(
            $request->locale,
            $request->group,
            $request->key,
            $request->value
        );

        return response()->json(['success' => true]);
    }

    public function missing(string $locale)
    {
        $missing = $this->translationService->getMissingTranslations($locale);
        return response()->json(['missing' => $missing]);
    }

    public function export(string $locale)
    {
        $json = $this->translationService->exportToJson($locale);

        return response($json)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', "attachment; filename={$locale}.json");
    }

    public function import(Request $request, string $locale)
    {
        $json = $request->file('file')->get();
        $count = $this->translationService->importFromJson($locale, $json);

        return response()->json([
            'success' => true,
            'imported' => $count,
        ]);
    }
}
```

## Cache Management

Translations are cached for 1 hour using Laravel's cache system:

```php
// Cache is automatically cleared when saving or deleting translations
$translationService->saveTranslation('fr', 'auth', 'login', 'Connexion');

// Manual cache clear
Cache::forget("translations.{$locale}");
```

## Source Code

**Location:** `src/Services/TranslationService.php`

**Namespace:** `CamboSoftware\CamboAdmin\Services`

**Default language path:** `resources/lang`
