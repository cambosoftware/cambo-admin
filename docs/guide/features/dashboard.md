# Dashboard

CamboAdmin provides a fully customizable dashboard system with drag-and-drop widgets that allow users to create personalized views of their most important data.

## Introduction

The dashboard module offers:

- Drag-and-drop widget positioning
- Resizable widgets
- Multiple dashboard layouts per user
- Pre-built widget types (stats, charts, lists)
- Custom widget creation
- Real-time data updates
- Responsive grid layout

## Configuration

### Enable/Disable Module

```php
// config/cambo-admin.php
'modules' => [
    'dashboard' => true,
],
```

### Default Dashboard Settings

The dashboard uses a 12-column grid system with configurable widget sizes and positions.

## Usage Examples

### Dashboard Layout

The `DashboardLayout` model represents a user's dashboard configuration:

```php
use CamboSoftware\CamboAdmin\Models\DashboardLayout;
```

#### Layout Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | integer | Unique identifier |
| `user_id` | integer | Owner user ID |
| `name` | string | Layout name |
| `is_default` | boolean | Default layout for user |
| `columns` | integer | Grid columns (default: 12) |

#### Get or Create Default Layout

```php
use CamboSoftware\CamboAdmin\Models\DashboardLayout;
use App\Models\User;

$user = User::find(1);

// Get user's default layout, creates one if not exists
$layout = DashboardLayout::getOrCreateDefault($user);
```

#### Create Custom Layout

```php
$layout = DashboardLayout::create([
    'user_id' => $user->id,
    'name' => 'Sales Dashboard',
    'is_default' => false,
    'columns' => 12,
]);
```

#### Set Layout as Default

```php
$layout->setAsDefault();
// Automatically unsets other layouts as default
```

### Widget Types

The `WidgetType` model defines available widget templates:

```php
use CamboSoftware\CamboAdmin\Models\WidgetType;
```

#### Widget Type Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `slug` | string | Unique identifier |
| `name` | string | Display name |
| `description` | string | Widget description |
| `component` | string | Vue component name |
| `icon` | string | Icon name |
| `default_config` | array | Default configuration |
| `config_schema` | array | Configuration form schema |
| `min_width` | integer | Minimum grid width |
| `min_height` | integer | Minimum grid height |
| `default_width` | integer | Default grid width |
| `default_height` | integer | Default grid height |
| `active` | boolean | Is widget available |

#### Register Widget Types

```php
// In a service provider or seeder
WidgetType::register([
    'slug' => 'stats-card',
    'name' => 'Statistics Card',
    'description' => 'Display a single statistic with trend',
    'component' => 'WidgetStatsCard',
    'icon' => 'chart-bar',
    'default_config' => [
        'title' => 'Users',
        'stat_key' => 'users',
        'icon' => 'users',
        'color' => 'primary',
    ],
    'config_schema' => [
        'title' => ['type' => 'text', 'label' => 'Title'],
        'stat_key' => ['type' => 'select', 'label' => 'Statistic', 'options' => ['users', 'orders', 'revenue']],
        'icon' => ['type' => 'icon', 'label' => 'Icon'],
        'color' => ['type' => 'color', 'label' => 'Color'],
    ],
    'min_width' => 2,
    'min_height' => 1,
    'default_width' => 3,
    'default_height' => 1,
    'active' => true,
]);

WidgetType::register([
    'slug' => 'chart-line',
    'name' => 'Line Chart',
    'description' => 'Display trends over time',
    'component' => 'WidgetLineChart',
    'icon' => 'chart-line',
    'default_config' => [
        'title' => 'Sales Trend',
        'endpoint' => '/api/stats/sales-trend',
    ],
    'min_width' => 4,
    'min_height' => 2,
    'default_width' => 6,
    'default_height' => 2,
    'active' => true,
]);

WidgetType::register([
    'slug' => 'recent-activity',
    'name' => 'Recent Activity',
    'description' => 'Display recent activity log',
    'component' => 'WidgetRecentActivity',
    'icon' => 'clock',
    'default_config' => [
        'title' => 'Recent Activity',
        'limit' => 5,
    ],
    'min_width' => 3,
    'min_height' => 2,
    'default_width' => 4,
    'default_height' => 2,
    'active' => true,
]);
```

#### Get Available Widget Types

```php
// Get all active widget types
$widgetTypes = WidgetType::active()->get();

// Find by slug
$statsCard = WidgetType::findBySlug('stats-card');
```

### Dashboard Widgets

The `DashboardWidget` model represents widget instances on a layout:

```php
use CamboSoftware\CamboAdmin\Models\DashboardWidget;
```

#### Widget Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `layout_id` | integer | Parent layout ID |
| `widget_type_id` | integer | Widget type ID |
| `x` | integer | Grid X position (0-11) |
| `y` | integer | Grid Y position |
| `width` | integer | Grid width (1-12) |
| `height` | integer | Grid height |
| `config` | array | Instance configuration |

#### Add Widget to Layout

```php
$layout = DashboardLayout::getOrCreateDefault($user);
$widgetType = WidgetType::findBySlug('stats-card');

$widget = $layout->widgets()->create([
    'widget_type_id' => $widgetType->id,
    'x' => 0,
    'y' => 0,
    'width' => 3,
    'height' => 1,
    'config' => [
        'title' => 'Total Users',
        'stat_key' => 'users_count',
        'icon' => 'users',
        'color' => 'primary',
    ],
]);
```

#### Update Widget Position

```php
$widget->updatePosition(3, 0); // Move to x=3, y=0
```

#### Update Widget Size

```php
$widget->updateSize(4, 2); // Resize to 4 columns, 2 rows
// Respects min_width and min_height from widget type
```

#### Get Merged Configuration

```php
// Combines widget type defaults with instance config
$config = $widget->mergedConfig;
```

#### Get Vue Component

```php
$componentName = $widget->component; // 'WidgetStatsCard'
```

### Default Widgets

Add default widgets when creating a layout:

```php
$layout = DashboardLayout::create([
    'user_id' => $user->id,
    'name' => 'Main Dashboard',
    'is_default' => true,
]);

// Add default widgets
$layout->addDefaultWidgets();
```

The `addDefaultWidgets()` method creates a standard layout:

```php
// In DashboardLayout model
public function addDefaultWidgets(): void
{
    $defaults = [
        ['slug' => 'stats-card', 'x' => 0, 'y' => 0, 'width' => 3, 'height' => 1, 'config' => ['title' => 'Users', 'stat_key' => 'users', 'icon' => 'users', 'color' => 'primary']],
        ['slug' => 'stats-card', 'x' => 3, 'y' => 0, 'width' => 3, 'height' => 1, 'config' => ['title' => 'Orders', 'stat_key' => 'orders', 'icon' => 'shopping-cart', 'color' => 'success']],
        ['slug' => 'stats-card', 'x' => 6, 'y' => 0, 'width' => 3, 'height' => 1, 'config' => ['title' => 'Revenue', 'stat_key' => 'revenue', 'icon' => 'currency-dollar', 'color' => 'warning']],
        ['slug' => 'stats-card', 'x' => 9, 'y' => 0, 'width' => 3, 'height' => 1, 'config' => ['title' => 'Products', 'stat_key' => 'products', 'icon' => 'cube', 'color' => 'info']],
        ['slug' => 'chart-line', 'x' => 0, 'y' => 1, 'width' => 8, 'height' => 2, 'config' => ['title' => 'Sales Evolution']],
        ['slug' => 'recent-activity', 'x' => 8, 'y' => 1, 'width' => 4, 'height' => 2, 'config' => ['title' => 'Recent Activity', 'limit' => 5]],
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
```

### Dashboard Controller

```php
<?php

namespace App\Http\Controllers\Admin;

use CamboSoftware\CamboAdmin\Models\DashboardLayout;
use CamboSoftware\CamboAdmin\Models\DashboardWidget;
use CamboSoftware\CamboAdmin\Models\WidgetType;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $layout = DashboardLayout::getOrCreateDefault(auth()->user());

        return inertia('Dashboard', [
            'layout' => $layout->load('widgets.widgetType'),
            'widgetTypes' => WidgetType::active()->get(),
        ]);
    }

    public function getStats()
    {
        return response()->json([
            'users' => \App\Models\User::count(),
            'orders' => \App\Models\Order::count(),
            'revenue' => \App\Models\Order::sum('total'),
            'products' => \App\Models\Product::count(),
        ]);
    }

    public function updateLayout(Request $request)
    {
        $layout = DashboardLayout::findOrFail($request->layout_id);

        $this->authorize('update', $layout);

        foreach ($request->widgets as $widgetData) {
            DashboardWidget::where('id', $widgetData['id'])
                ->where('layout_id', $layout->id)
                ->update([
                    'x' => $widgetData['x'],
                    'y' => $widgetData['y'],
                    'width' => $widgetData['width'],
                    'height' => $widgetData['height'],
                ]);
        }

        return response()->json(['success' => true]);
    }

    public function addWidget(Request $request)
    {
        $layout = DashboardLayout::findOrFail($request->layout_id);
        $widgetType = WidgetType::findOrFail($request->widget_type_id);

        $this->authorize('update', $layout);

        $widget = $layout->widgets()->create([
            'widget_type_id' => $widgetType->id,
            'x' => $request->x ?? 0,
            'y' => $request->y ?? 0,
            'width' => $request->width ?? $widgetType->default_width,
            'height' => $request->height ?? $widgetType->default_height,
            'config' => $request->config ?? [],
        ]);

        return response()->json($widget->load('widgetType'));
    }

    public function updateWidget(Request $request, DashboardWidget $widget)
    {
        $this->authorize('update', $widget->layout);

        $widget->update([
            'config' => $request->config,
        ]);

        return response()->json($widget);
    }

    public function removeWidget(DashboardWidget $widget)
    {
        $this->authorize('update', $widget->layout);

        $widget->delete();

        return response()->json(['success' => true]);
    }
}
```

## Available Options

### Built-in Widget Types

| Widget | Component | Description | Default Size |
|--------|-----------|-------------|--------------|
| Stats Card | WidgetStatsCard | Single statistic with icon | 3x1 |
| Line Chart | WidgetLineChart | Trend over time | 6x2 |
| Bar Chart | WidgetBarChart | Comparison chart | 6x2 |
| Pie Chart | WidgetPieChart | Distribution chart | 4x2 |
| Recent Activity | WidgetRecentActivity | Activity log list | 4x2 |
| Recent Orders | WidgetRecentOrders | Latest orders list | 4x2 |
| Quick Actions | WidgetQuickActions | Action buttons | 3x1 |
| Notes | WidgetNotes | User notes | 4x2 |

### Widget Configuration Schema Types

| Type | Description | Example |
|------|-------------|---------|
| `text` | Text input | Title field |
| `number` | Numeric input | Limit field |
| `select` | Dropdown selection | Data source |
| `multiselect` | Multiple selection | Columns to show |
| `color` | Color picker | Theme color |
| `icon` | Icon selector | Widget icon |
| `boolean` | Toggle switch | Show trend |
| `date` | Date picker | Start date |
| `endpoint` | API endpoint URL | Data source |

### Grid System

The dashboard uses a 12-column responsive grid:

| Screen Size | Columns | Widget Scaling |
|-------------|---------|----------------|
| Desktop (>1200px) | 12 | Full width |
| Tablet (768-1200px) | 8 | Proportional |
| Mobile (<768px) | 4 | Stacked |

## Creating Custom Widgets

### 1. Register Widget Type

```php
// In database seeder or service provider
WidgetType::register([
    'slug' => 'weather',
    'name' => 'Weather Widget',
    'description' => 'Display current weather',
    'component' => 'WidgetWeather',
    'icon' => 'cloud',
    'default_config' => [
        'city' => 'New York',
        'units' => 'metric',
    ],
    'config_schema' => [
        'city' => [
            'type' => 'text',
            'label' => 'City',
            'required' => true,
        ],
        'units' => [
            'type' => 'select',
            'label' => 'Units',
            'options' => [
                'metric' => 'Celsius',
                'imperial' => 'Fahrenheit',
            ],
        ],
    ],
    'min_width' => 2,
    'min_height' => 2,
    'default_width' => 3,
    'default_height' => 2,
    'active' => true,
]);
```

### 2. Create Vue Component

```vue
<!-- resources/js/Components/Widgets/WidgetWeather.vue -->
<template>
  <div class="widget-weather p-4 bg-white rounded-lg shadow">
    <h3 class="font-semibold text-lg mb-2">{{ config.city }}</h3>

    <div v-if="loading" class="flex justify-center py-8">
      <Spinner />
    </div>

    <div v-else-if="weather" class="text-center">
      <div class="text-5xl mb-2">{{ weatherIcon }}</div>
      <div class="text-3xl font-bold">{{ temperature }}</div>
      <div class="text-gray-500">{{ weather.description }}</div>
    </div>

    <div v-else class="text-center text-gray-500 py-8">
      Unable to load weather data
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
  config: Object,
  widget: Object,
});

const loading = ref(true);
const weather = ref(null);

const temperature = computed(() => {
  if (!weather.value) return '';
  const temp = weather.value.temp;
  const unit = props.config.units === 'metric' ? 'C' : 'F';
  return `${Math.round(temp)}°${unit}`;
});

const weatherIcon = computed(() => {
  if (!weather.value) return '';
  const icons = {
    clear: 'sunny',
    clouds: 'cloud',
    rain: 'rainy',
    snow: 'snow',
  };
  return icons[weather.value.main.toLowerCase()] || 'cloud';
});

const fetchWeather = async () => {
  try {
    loading.value = true;
    const response = await fetch(`/api/weather?city=${props.config.city}&units=${props.config.units}`);
    weather.value = await response.json();
  } catch (error) {
    console.error('Failed to fetch weather:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchWeather);
</script>
```

### 3. Register Component

```javascript
// resources/js/app.js
import WidgetWeather from './Components/Widgets/WidgetWeather.vue';

app.component('WidgetWeather', WidgetWeather);
```

### 4. Create API Endpoint

```php
// routes/api.php
Route::get('/weather', function (Request $request) {
    // Fetch from weather API
    $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
        'q' => $request->city,
        'units' => $request->units,
        'appid' => config('services.openweather.key'),
    ]);

    return $response->json();
});
```

## Vue Dashboard Component Example

```vue
<!-- resources/js/Pages/Dashboard.vue -->
<template>
  <div class="dashboard">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Dashboard</h1>
      <button @click="showAddWidget = true" class="btn btn-primary">
        Add Widget
      </button>
    </div>

    <GridLayout
      v-model:layout="widgets"
      :col-num="12"
      :row-height="100"
      :is-draggable="true"
      :is-resizable="true"
      :margin="[16, 16]"
      @layout-updated="saveLayout"
    >
      <GridItem
        v-for="widget in widgets"
        :key="widget.id"
        :x="widget.x"
        :y="widget.y"
        :w="widget.width"
        :h="widget.height"
        :i="widget.id"
        :min-w="widget.widget_type.min_width"
        :min-h="widget.widget_type.min_height"
      >
        <div class="widget-container relative group">
          <div class="widget-actions absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
            <button @click="editWidget(widget)" class="p-1 hover:bg-gray-100 rounded">
              <CogIcon class="w-4 h-4" />
            </button>
            <button @click="removeWidget(widget)" class="p-1 hover:bg-gray-100 rounded text-red-500">
              <TrashIcon class="w-4 h-4" />
            </button>
          </div>

          <component
            :is="widget.widget_type.component"
            :config="widget.merged_config"
            :widget="widget"
          />
        </div>
      </GridItem>
    </GridLayout>

    <!-- Add Widget Modal -->
    <Modal v-model="showAddWidget" title="Add Widget">
      <div class="grid grid-cols-2 gap-4">
        <div
          v-for="type in widgetTypes"
          :key="type.id"
          @click="addWidget(type)"
          class="p-4 border rounded-lg cursor-pointer hover:border-primary hover:bg-primary-50"
        >
          <div class="text-2xl mb-2">
            <component :is="type.icon" />
          </div>
          <h4 class="font-semibold">{{ type.name }}</h4>
          <p class="text-sm text-gray-500">{{ type.description }}</p>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { GridLayout, GridItem } from 'vue-grid-layout';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  layout: Object,
  widgetTypes: Array,
});

const widgets = ref(props.layout.widgets);
const showAddWidget = ref(false);

const saveLayout = async (newLayout) => {
  const widgetData = newLayout.map(item => ({
    id: item.i,
    x: item.x,
    y: item.y,
    width: item.w,
    height: item.h,
  }));

  await fetch('/admin/dashboard/layout', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      layout_id: props.layout.id,
      widgets: widgetData,
    }),
  });
};

const addWidget = async (type) => {
  const response = await fetch('/admin/dashboard/widgets', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      layout_id: props.layout.id,
      widget_type_id: type.id,
    }),
  });

  const widget = await response.json();
  widgets.value.push(widget);
  showAddWidget.value = false;
};

const removeWidget = async (widget) => {
  if (!confirm('Remove this widget?')) return;

  await fetch(`/admin/dashboard/widgets/${widget.id}`, {
    method: 'DELETE',
  });

  widgets.value = widgets.value.filter(w => w.id !== widget.id);
};
</script>
```

## Best Practices

1. **Performance**: Use lazy loading for widget data
2. **Caching**: Cache expensive widget queries
3. **Responsiveness**: Test widgets at all screen sizes
4. **Error Handling**: Show fallback UI when data fails to load
5. **Loading States**: Show skeletons while data loads
6. **Permissions**: Check permissions before showing sensitive widgets

```php
// Cache expensive stats
$stats = Cache::remember('dashboard_stats', 300, function () {
    return [
        'users' => User::count(),
        'orders' => Order::whereDate('created_at', today())->count(),
        'revenue' => Order::whereMonth('created_at', now()->month)->sum('total'),
    ];
});
```
