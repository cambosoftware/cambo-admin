<?php

namespace CamboSoftware\CamboAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WidgetType extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'component',
        'icon',
        'default_config',
        'config_schema',
        'min_width',
        'min_height',
        'default_width',
        'default_height',
        'active',
    ];

    protected $casts = [
        'default_config' => 'array',
        'config_schema' => 'array',
        'active' => 'boolean',
    ];

    /**
     * Get all widget instances of this type.
     */
    public function widgets(): HasMany
    {
        return $this->hasMany(DashboardWidget::class);
    }

    /**
     * Scope for active widgets.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Get widget by slug.
     */
    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Register a new widget type.
     */
    public static function register(array $data): self
    {
        return static::updateOrCreate(
            ['slug' => $data['slug']],
            $data
        );
    }
}
