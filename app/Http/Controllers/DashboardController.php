<?php

namespace App\Http\Controllers;

use CamboSoftware\CamboAdmin\Models\DashboardLayout;
use CamboSoftware\CamboAdmin\Models\DashboardWidget;
use CamboSoftware\CamboAdmin\Models\WidgetType;
use CamboSoftware\CamboAdmin\Models\Role;
use CamboSoftware\CamboAdmin\Models\MediaFile;
use CamboSoftware\CamboAdmin\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $layout = DashboardLayout::getOrCreateDefault($user);

        $widgets = $layout->widgets()
            ->with('widgetType')
            ->get()
            ->map(fn ($widget) => [
                'id' => $widget->id,
                'component' => $widget->component,
                'x' => $widget->x,
                'y' => $widget->y,
                'width' => $widget->width,
                'height' => $widget->height,
                'config' => $widget->merged_config,
                'widgetType' => [
                    'slug' => $widget->widgetType->slug,
                    'name' => $widget->widgetType->name,
                    'icon' => $widget->widgetType->icon,
                    'min_width' => $widget->widgetType->min_width,
                    'min_height' => $widget->widgetType->min_height,
                ],
            ]);

        // Get stats for widgets
        $stats = $this->getStats();

        return Inertia::render('Dashboard', [
            'layout' => [
                'id' => $layout->id,
                'columns' => $layout->columns,
            ],
            'widgets' => $widgets,
            'stats' => $stats,
            'modules' => config('cambo-admin.modules', []),
            'editMode' => $request->boolean('edit'),
        ]);
    }

    /**
     * Get available widget types.
     */
    public function widgetTypes()
    {
        $types = WidgetType::active()->get();

        return response()->json([
            'widgetTypes' => $types,
        ]);
    }

    /**
     * Add a widget to the dashboard.
     */
    public function addWidget(Request $request)
    {
        $request->validate([
            'widget_type_slug' => 'required|exists:widget_types,slug',
            'x' => 'integer|min:0',
            'y' => 'integer|min:0',
        ]);

        $layout = DashboardLayout::getOrCreateDefault($request->user());
        $widgetType = WidgetType::findBySlug($request->widget_type_slug);

        $widget = $layout->widgets()->create([
            'widget_type_id' => $widgetType->id,
            'x' => $request->get('x', 0),
            'y' => $request->get('y', 0),
            'width' => $widgetType->default_width,
            'height' => $widgetType->default_height,
            'config' => $widgetType->default_config,
        ]);

        return response()->json([
            'widget' => [
                'id' => $widget->id,
                'component' => $widget->component,
                'x' => $widget->x,
                'y' => $widget->y,
                'width' => $widget->width,
                'height' => $widget->height,
                'config' => $widget->merged_config,
                'widgetType' => [
                    'slug' => $widgetType->slug,
                    'name' => $widgetType->name,
                    'icon' => $widgetType->icon,
                    'min_width' => $widgetType->min_width,
                    'min_height' => $widgetType->min_height,
                ],
            ],
        ]);
    }

    /**
     * Update a widget's position/size.
     */
    public function updateWidget(Request $request, DashboardWidget $widget)
    {
        // Ensure user owns this widget
        $layout = DashboardLayout::getOrCreateDefault($request->user());
        if ($widget->layout_id !== $layout->id) {
            abort(403);
        }

        $request->validate([
            'x' => 'sometimes|integer|min:0',
            'y' => 'sometimes|integer|min:0',
            'width' => 'sometimes|integer|min:1',
            'height' => 'sometimes|integer|min:1',
            'config' => 'sometimes|array',
        ]);

        $widget->update($request->only(['x', 'y', 'width', 'height', 'config']));

        return response()->json(['success' => true]);
    }

    /**
     * Update multiple widgets at once (for drag & drop).
     */
    public function updateWidgets(Request $request)
    {
        $request->validate([
            'widgets' => 'required|array',
            'widgets.*.id' => 'required|exists:dashboard_widgets,id',
            'widgets.*.x' => 'required|integer|min:0',
            'widgets.*.y' => 'required|integer|min:0',
            'widgets.*.width' => 'sometimes|integer|min:1',
            'widgets.*.height' => 'sometimes|integer|min:1',
        ]);

        $layout = DashboardLayout::getOrCreateDefault($request->user());

        foreach ($request->widgets as $widgetData) {
            $widget = DashboardWidget::find($widgetData['id']);
            if ($widget && $widget->layout_id === $layout->id) {
                $widget->update([
                    'x' => $widgetData['x'],
                    'y' => $widgetData['y'],
                    'width' => $widgetData['width'] ?? $widget->width,
                    'height' => $widgetData['height'] ?? $widget->height,
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove a widget.
     */
    public function removeWidget(Request $request, DashboardWidget $widget)
    {
        $layout = DashboardLayout::getOrCreateDefault($request->user());
        if ($widget->layout_id !== $layout->id) {
            abort(403);
        }

        $widget->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Reset dashboard to default.
     */
    public function resetLayout(Request $request)
    {
        $layout = DashboardLayout::getOrCreateDefault($request->user());

        // Delete all widgets
        $layout->widgets()->delete();

        // Re-add defaults
        $layout->addDefaultWidgets();

        return back()->with('success', 'Dashboard réinitialisé.');
    }

    /**
     * Get dashboard stats.
     */
    protected function getStats(): array
    {
        $stats = [
            'users_count' => User::count(),
        ];

        // Add roles count if module is enabled
        if (config('cambo-admin.modules.roles', true)) {
            $stats['roles_count'] = class_exists(Role::class) ? Role::count() : 0;
        }

        // Add media count if module is enabled
        if (config('cambo-admin.modules.media', true)) {
            $stats['media_count'] = class_exists(MediaFile::class) ? MediaFile::count() : 0;
        }

        // Add notifications count if module is enabled
        if (config('cambo-admin.modules.notifications', true)) {
            $stats['notifications_count'] = class_exists(Notification::class)
                ? Notification::where('read_at', null)->count()
                : 0;
        }

        return $stats;
    }
}
