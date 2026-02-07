<?php

namespace CamboSoftware\CamboAdmin\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DashboardLayout extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'is_default',
        'columns',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns this layout.
     */
    public function user(): BelongsTo
    {
        $userModel = config('cambo-admin.models.user') ?? \App\Models\User::class;
        return $this->belongsTo($userModel);
    }

    /**
     * Get the widgets in this layout.
     */
    public function widgets(): HasMany
    {
        return $this->hasMany(DashboardWidget::class, 'layout_id');
    }

    /**
     * Get the default layout for a user, creating if needed.
     */
    public static function getOrCreateDefault(Authenticatable $user): self
    {
        $layout = static::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();

        if (!$layout) {
            $layout = static::create([
                'user_id' => $user->id,
                'name' => 'default',
                'is_default' => true,
            ]);

            // Add default widgets
            $layout->addDefaultWidgets();
        }

        return $layout;
    }

    /**
     * Add default widgets to this layout.
     */
    public function addDefaultWidgets(): void
    {
        $defaults = [
            ['slug' => 'stats-card', 'x' => 0, 'y' => 0, 'width' => 3, 'height' => 1, 'config' => ['title' => 'Utilisateurs', 'stat_key' => 'users', 'icon' => 'users', 'color' => 'primary']],
            ['slug' => 'stats-card', 'x' => 3, 'y' => 0, 'width' => 3, 'height' => 1, 'config' => ['title' => 'Commandes', 'stat_key' => 'orders', 'icon' => 'shopping-cart', 'color' => 'success']],
            ['slug' => 'stats-card', 'x' => 6, 'y' => 0, 'width' => 3, 'height' => 1, 'config' => ['title' => 'Revenus', 'stat_key' => 'revenue', 'icon' => 'currency-dollar', 'color' => 'warning']],
            ['slug' => 'stats-card', 'x' => 9, 'y' => 0, 'width' => 3, 'height' => 1, 'config' => ['title' => 'Produits', 'stat_key' => 'products', 'icon' => 'cube', 'color' => 'info']],
            ['slug' => 'chart-line', 'x' => 0, 'y' => 1, 'width' => 8, 'height' => 2, 'config' => ['title' => 'Évolution des ventes']],
            ['slug' => 'recent-activity', 'x' => 8, 'y' => 1, 'width' => 4, 'height' => 2, 'config' => ['title' => 'Activité récente', 'limit' => 5]],
        ];

        foreach ($defaults as $widgetData) {
            $widgetType = WidgetType::findBySlug($widgetData['slug']);
            if ($widgetType) {
                $this->widgets()->create([
                    'widget_type_id' => $widgetType->id,
                    'x' => $widgetData['x'],
                    'y' => $widgetData['y'],
                    'width' => $widgetData['width'],
                    'height' => $widgetData['height'],
                    'config' => $widgetData['config'],
                ]);
            }
        }
    }

    /**
     * Set this layout as default (unset others).
     */
    public function setAsDefault(): void
    {
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }
}
