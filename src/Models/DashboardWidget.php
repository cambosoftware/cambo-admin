<?php

namespace CamboSoftware\CamboAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardWidget extends Model
{
    protected $fillable = [
        'layout_id',
        'widget_type_id',
        'x',
        'y',
        'width',
        'height',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    /**
     * Get the layout this widget belongs to.
     */
    public function layout(): BelongsTo
    {
        return $this->belongsTo(DashboardLayout::class, 'layout_id');
    }

    /**
     * Get the widget type.
     */
    public function widgetType(): BelongsTo
    {
        return $this->belongsTo(WidgetType::class);
    }

    /**
     * Get the merged config (defaults + instance config).
     */
    public function getMergedConfigAttribute(): array
    {
        $defaults = $this->widgetType?->default_config ?? [];
        $instance = $this->config ?? [];

        return array_merge($defaults, $instance);
    }

    /**
     * Get the Vue component name.
     */
    public function getComponentAttribute(): string
    {
        return $this->widgetType?->component ?? 'WidgetPlaceholder';
    }

    /**
     * Update position.
     */
    public function updatePosition(int $x, int $y): void
    {
        $this->update(['x' => $x, 'y' => $y]);
    }

    /**
     * Update size.
     */
    public function updateSize(int $width, int $height): void
    {
        $minWidth = $this->widgetType?->min_width ?? 1;
        $minHeight = $this->widgetType?->min_height ?? 1;

        $this->update([
            'width' => max($width, $minWidth),
            'height' => max($height, $minHeight),
        ]);
    }
}
