<?php

namespace CamboSoftware\CamboAdmin\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Arr;

class TranslationService
{
    protected string $langPath;
    protected array $supportedLocales;

    public function __construct()
    {
        $this->langPath = resource_path('lang');
        $this->supportedLocales = config('app.supported_locales', ['en', 'fr']);
    }

    /**
     * Get all translations for a locale
     */
    public function getTranslations(string $locale): array
    {
        return Cache::remember("translations.{$locale}", 3600, function () use ($locale) {
            $translations = [];
            $path = "{$this->langPath}/{$locale}";

            if (!File::isDirectory($path)) {
                return $translations;
            }

            foreach (File::files($path) as $file) {
                $name = $file->getFilenameWithoutExtension();

                if ($file->getExtension() === 'php') {
                    $translations[$name] = require $file->getPathname();
                } elseif ($file->getExtension() === 'json') {
                    $translations[$name] = json_decode(File::get($file->getPathname()), true);
                }
            }

            // Also load JSON file directly in lang folder
            $jsonFile = "{$this->langPath}/{$locale}.json";
            if (File::exists($jsonFile)) {
                $jsonTranslations = json_decode(File::get($jsonFile), true);
                if ($jsonTranslations) {
                    $translations = array_merge($translations, $jsonTranslations);
                }
            }

            return $translations;
        });
    }

    /**
     * Get supported locales with labels
     */
    public function getSupportedLocales(): array
    {
        $locales = [
            'en' => ['name' => 'English', 'native' => 'English', 'rtl' => false, 'flag' => '🇬🇧'],
            'fr' => ['name' => 'French', 'native' => 'Français', 'rtl' => false, 'flag' => '🇫🇷'],
            'es' => ['name' => 'Spanish', 'native' => 'Español', 'rtl' => false, 'flag' => '🇪🇸'],
            'de' => ['name' => 'German', 'native' => 'Deutsch', 'rtl' => false, 'flag' => '🇩🇪'],
            'it' => ['name' => 'Italian', 'native' => 'Italiano', 'rtl' => false, 'flag' => '🇮🇹'],
            'pt' => ['name' => 'Portuguese', 'native' => 'Português', 'rtl' => false, 'flag' => '🇵🇹'],
            'nl' => ['name' => 'Dutch', 'native' => 'Nederlands', 'rtl' => false, 'flag' => '🇳🇱'],
            'ru' => ['name' => 'Russian', 'native' => 'Русский', 'rtl' => false, 'flag' => '🇷🇺'],
            'zh' => ['name' => 'Chinese', 'native' => '中文', 'rtl' => false, 'flag' => '🇨🇳'],
            'ja' => ['name' => 'Japanese', 'native' => '日本語', 'rtl' => false, 'flag' => '🇯🇵'],
            'ko' => ['name' => 'Korean', 'native' => '한국어', 'rtl' => false, 'flag' => '🇰🇷'],
            'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'rtl' => true, 'flag' => '🇸🇦'],
            'he' => ['name' => 'Hebrew', 'native' => 'עברית', 'rtl' => true, 'flag' => '🇮🇱'],
            'fa' => ['name' => 'Persian', 'native' => 'فارسی', 'rtl' => true, 'flag' => '🇮🇷'],
        ];

        $supported = [];
        foreach ($this->supportedLocales as $code) {
            if (isset($locales[$code])) {
                $supported[$code] = $locales[$code];
            }
        }

        return $supported;
    }

    /**
     * Check if locale is RTL
     */
    public function isRtl(string $locale): bool
    {
        $locales = $this->getSupportedLocales();
        return $locales[$locale]['rtl'] ?? false;
    }

    /**
     * Set current locale
     */
    public function setLocale(string $locale): void
    {
        if (in_array($locale, $this->supportedLocales)) {
            App::setLocale($locale);
            session(['locale' => $locale]);
        }
    }

    /**
     * Get current locale
     */
    public function getCurrentLocale(): string
    {
        return session('locale', config('app.locale', 'en'));
    }

    /**
     * Save translation
     */
    public function saveTranslation(string $locale, string $group, string $key, string $value): bool
    {
        $path = "{$this->langPath}/{$locale}";

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $file = "{$path}/{$group}.php";
        $translations = [];

        if (File::exists($file)) {
            $translations = require $file;
        }

        Arr::set($translations, $key, $value);

        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
        File::put($file, $content);

        // Clear cache
        Cache::forget("translations.{$locale}");

        return true;
    }

    /**
     * Delete translation
     */
    public function deleteTranslation(string $locale, string $group, string $key): bool
    {
        $file = "{$this->langPath}/{$locale}/{$group}.php";

        if (!File::exists($file)) {
            return false;
        }

        $translations = require $file;
        Arr::forget($translations, $key);

        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
        File::put($file, $content);

        // Clear cache
        Cache::forget("translations.{$locale}");

        return true;
    }

    /**
     * Get missing translations for a locale compared to reference locale
     */
    public function getMissingTranslations(string $locale, string $referenceLocale = 'en'): array
    {
        $reference = $this->getTranslations($referenceLocale);
        $target = $this->getTranslations($locale);

        $missing = [];

        $this->compareTrans($reference, $target, '', $missing);

        return $missing;
    }

    /**
     * Compare translations recursively
     */
    protected function compareTrans(array $reference, array $target, string $prefix, array &$missing): void
    {
        foreach ($reference as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $this->compareTrans(
                    $value,
                    $target[$key] ?? [],
                    $fullKey,
                    $missing
                );
            } elseif (!isset($target[$key])) {
                $missing[] = [
                    'key' => $fullKey,
                    'reference_value' => $value,
                ];
            }
        }
    }

    /**
     * Export translations as JSON
     */
    public function exportToJson(string $locale): string
    {
        return json_encode($this->getTranslations($locale), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Import translations from JSON
     */
    public function importFromJson(string $locale, string $json): int
    {
        $translations = json_decode($json, true);

        if (!$translations) {
            return 0;
        }

        $count = 0;

        foreach ($translations as $group => $items) {
            if (is_array($items)) {
                foreach ($items as $key => $value) {
                    $this->saveTranslation($locale, $group, $key, $value);
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Create base translations for new locale
     */
    public function createLocale(string $locale, string $copyFrom = 'en'): bool
    {
        $sourcePath = "{$this->langPath}/{$copyFrom}";
        $targetPath = "{$this->langPath}/{$locale}";

        if (!File::isDirectory($sourcePath)) {
            return false;
        }

        File::copyDirectory($sourcePath, $targetPath);

        // Clear cache
        Cache::forget("translations.{$locale}");

        return true;
    }
}
