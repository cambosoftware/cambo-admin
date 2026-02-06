<?php

namespace CamboSoftware\CamboAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'label',
        'description',
        'type',
        'value',
        'default_value',
        'options',
        'validation',
        'is_public',
        'is_encrypted',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'validation' => 'array',
        'is_public' => 'boolean',
        'is_encrypted' => 'boolean',
    ];

    /**
     * Cache key for settings.
     */
    protected static string $cacheKey = 'app_settings';

    /**
     * Get the typed value.
     */
    public function getTypedValueAttribute()
    {
        $value = $this->is_encrypted && $this->value
            ? Crypt::decryptString($this->value)
            : $this->value;

        if ($value === null) {
            $value = $this->default_value;
        }

        return match ($this->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number', 'integer' => (int) $value,
            'float' => (float) $value,
            'json', 'array' => is_array($value) ? $value : json_decode($value, true),
            'multiselect' => is_array($value) ? $value : explode(',', $value ?? ''),
            default => $value,
        };
    }

    /**
     * Set the value with type handling.
     */
    public function setValueAttribute($value)
    {
        // Convert arrays to JSON
        if (is_array($value)) {
            $value = json_encode($value);
        }

        // Encrypt if needed
        if ($this->is_encrypted && $value !== null) {
            $value = Crypt::encryptString($value);
        }

        $this->attributes['value'] = $value;
    }

    /**
     * Get all settings, grouped.
     */
    public static function allGrouped(): array
    {
        return Cache::remember(static::$cacheKey, 3600, function () {
            return static::orderBy('group')
                ->orderBy('order')
                ->get()
                ->groupBy('group')
                ->toArray();
        });
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->typed_value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, $value): bool
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return false;
        }

        $setting->value = $value;
        $setting->save();

        static::clearCache();

        return true;
    }

    /**
     * Update multiple settings at once.
     */
    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            static::set($key, $value);
        }
    }

    /**
     * Get settings for a specific group.
     */
    public static function forGroup(string $group)
    {
        return static::where('group', $group)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get all public settings.
     */
    public static function getPublic(): array
    {
        return static::where('is_public', true)
            ->get()
            ->pluck('typed_value', 'key')
            ->toArray();
    }

    /**
     * Clear the settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(static::$cacheKey);
    }

    /**
     * Define a new setting.
     */
    public static function define(array $data): self
    {
        return static::updateOrCreate(
            ['key' => $data['key']],
            $data
        );
    }

    /**
     * Available setting groups.
     */
    public static function groups(): array
    {
        return [
            'general' => [
                'label' => 'General',
                'icon' => 'cog-6-tooth',
                'description' => 'General application settings',
            ],
            'appearance' => [
                'label' => 'Appearance',
                'icon' => 'paint-brush',
                'description' => 'Visual customization',
            ],
            'email' => [
                'label' => 'Email',
                'icon' => 'envelope',
                'description' => 'Email configuration',
            ],
            'security' => [
                'label' => 'Security',
                'icon' => 'shield-check',
                'description' => 'Security settings',
            ],
            'integrations' => [
                'label' => 'Integrations',
                'icon' => 'puzzle-piece',
                'description' => 'Third-party services and APIs',
            ],
            'advanced' => [
                'label' => 'Advanced',
                'icon' => 'wrench-screwdriver',
                'description' => 'Advanced options',
            ],
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            static::clearCache();
        });

        static::deleted(function () {
            static::clearCache();
        });
    }
}
