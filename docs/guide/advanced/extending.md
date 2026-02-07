# Extending CamboAdmin

This guide covers how to extend CamboAdmin with custom functionality, new modules, plugins, and integrations.

## Creating Custom Modules

### Module Structure

Create a new module following this structure:

```
app/
├── Http/
│   └── Controllers/
│       └── MyModule/
│           └── MyModuleController.php
├── Models/
│   └── MyModel.php
└── Services/
    └── MyModuleService.php

resources/js/
└── Pages/
    └── MyModule/
        ├── Index.vue
        ├── Create.vue
        ├── Edit.vue
        └── Show.vue

database/
└── migrations/
    └── xxxx_xx_xx_create_my_module_table.php
```

### Step 1: Create Migration

```php
<?php
// database/migrations/xxxx_xx_xx_create_my_module_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('my_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('my_modules');
    }
};
```

### Step 2: Create Model

```php
<?php
// app/Models/MyModule.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MyModule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        return $query;
    }
}
```

### Step 3: Create Service

```php
<?php
// app/Services/MyModuleService.php

namespace App\Services;

use App\Models\MyModule;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MyModuleService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return MyModule::query()
            ->with('user')
            ->search($filters['search'] ?? null)
            ->when(isset($filters['active']), fn($q) => $q->where('active', $filters['active']))
            ->orderBy($filters['sort'] ?? 'created_at', $filters['direction'] ?? 'desc')
            ->paginate($filters['per_page'] ?? 25);
    }

    public function create(array $data): MyModule
    {
        return DB::transaction(function () use ($data) {
            $module = MyModule::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'user_id' => auth()->id(),
                'active' => $data['active'] ?? true,
            ]);

            // Additional logic (events, notifications, etc.)
            event(new \App\Events\MyModuleCreated($module));

            return $module;
        });
    }

    public function update(MyModule $module, array $data): MyModule
    {
        return DB::transaction(function () use ($module, $data) {
            $module->update($data);
            return $module->fresh();
        });
    }

    public function delete(MyModule $module): bool
    {
        return DB::transaction(function () use ($module) {
            return $module->delete();
        });
    }
}
```

### Step 4: Create Controller

```php
<?php
// app/Http/Controllers/MyModule/MyModuleController.php

namespace App\Http\Controllers\MyModule;

use App\Http\Controllers\Controller;
use App\Models\MyModule;
use App\Services\MyModuleService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MyModuleController extends Controller
{
    public function __construct(
        protected MyModuleService $service
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('MyModule/Index', [
            'items' => $this->service->list($request->all()),
            'filters' => $request->only(['search', 'active', 'sort']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('MyModule/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean',
        ]);

        $this->service->create($validated);

        return redirect()
            ->route('my-module.index')
            ->with('success', 'Item created successfully.');
    }

    public function show(MyModule $myModule): Response
    {
        return Inertia::render('MyModule/Show', [
            'item' => $myModule->load('user'),
        ]);
    }

    public function edit(MyModule $myModule): Response
    {
        return Inertia::render('MyModule/Edit', [
            'item' => $myModule,
        ]);
    }

    public function update(Request $request, MyModule $myModule)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean',
        ]);

        $this->service->update($myModule, $validated);

        return redirect()
            ->route('my-module.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(MyModule $myModule)
    {
        $this->service->delete($myModule);

        return redirect()
            ->route('my-module.index')
            ->with('success', 'Item deleted successfully.');
    }
}
```

### Step 5: Add Routes

```php
<?php
// routes/web.php

use App\Http\Controllers\MyModule\MyModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('my-module', MyModuleController::class);
});
```

### Step 6: Create Vue Pages

Generate pages using the CLI or create manually:

```bash
php artisan cambo:page MyModule/Index --with-table --with-card
php artisan cambo:page MyModule/Create --with-form --with-card
php artisan cambo:page MyModule/Edit --with-form --with-card
php artisan cambo:page MyModule/Show --with-card
```

## Creating Custom Commands

### Basic Command

```php
<?php
// app/Console/Commands/MyCustomCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MyCustomCommand extends Command
{
    protected $signature = 'app:my-command
                            {name : The name to process}
                            {--force : Force the operation}';

    protected $description = 'Description of what this command does';

    public function handle(): int
    {
        $name = $this->argument('name');
        $force = $this->option('force');

        $this->info("Processing: {$name}");

        if ($force) {
            $this->warn('Force mode enabled');
        }

        // Your logic here

        $this->info('Command completed successfully!');

        return self::SUCCESS;
    }
}
```

### Generator Command

Create a custom generator command:

```php
<?php
// app/Console/Commands/MakeServiceCommand.php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeServiceCommand extends GeneratorCommand
{
    protected $signature = 'make:service {name : The name of the service}';
    protected $description = 'Create a new service class';
    protected $type = 'Service';

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/service.stub');
    }

    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\Services';
    }
}
```

Create the stub file:

```php
// stubs/service.stub
<?php

namespace {{ namespace }};

use Illuminate\Support\Facades\DB;

class {{ class }}
{
    public function __construct()
    {
        //
    }
}
```

## Creating Custom Middleware

### Basic Middleware

```php
<?php
// app/Http/Middleware/CheckModuleEnabled.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleEnabled
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (!config("cambo-admin.modules.{$module}", false)) {
            abort(404, "Module '{$module}' is not enabled.");
        }

        return $next($request);
    }
}
```

Register in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'module' => \App\Http\Middleware\CheckModuleEnabled::class,
    ]);
})
```

Usage:

```php
Route::middleware('module:users')->group(function () {
    Route::resource('users', UserController::class);
});
```

### Permission Middleware

```php
<?php
// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
```

## Creating Custom Plugins

### Vue Plugin

```javascript
// resources/js/Plugins/analytics.js
export default {
    install(app, options = {}) {
        // Add global property
        app.config.globalProperties.$analytics = {
            track(event, data = {}) {
                console.log('Tracking:', event, data)
                // Send to analytics service
            },
            page(name) {
                this.track('page_view', { page: name })
            },
        }

        // Add global directive
        app.directive('track-click', {
            mounted(el, binding) {
                el.addEventListener('click', () => {
                    app.config.globalProperties.$analytics.track('click', {
                        element: binding.value || el.textContent,
                    })
                })
            },
        })

        // Provide for composition API
        app.provide('analytics', app.config.globalProperties.$analytics)
    },
}
```

Register in `app.js`:

```javascript
import analytics from '@/Plugins/analytics'

createInertiaApp({
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(analytics, { debug: true })
            .mount(el)
    },
})
```

Usage:

```vue
<script setup>
import { inject } from 'vue'

const analytics = inject('analytics')

const handleClick = () => {
    analytics.track('button_clicked', { button: 'submit' })
}
</script>

<template>
    <button @click="handleClick" v-track-click="'primary-cta'">
        Click me
    </button>
</template>
```

### Composable Plugin

```javascript
// resources/js/Composables/useApi.js
import { ref } from 'vue'
import axios from 'axios'

export function useApi() {
    const loading = ref(false)
    const error = ref(null)
    const data = ref(null)

    const request = async (method, url, payload = null) => {
        loading.value = true
        error.value = null

        try {
            const response = await axios({ method, url, data: payload })
            data.value = response.data
            return response.data
        } catch (e) {
            error.value = e.response?.data?.message || e.message
            throw e
        } finally {
            loading.value = false
        }
    }

    const get = (url) => request('get', url)
    const post = (url, payload) => request('post', url, payload)
    const put = (url, payload) => request('put', url, payload)
    const del = (url) => request('delete', url)

    return {
        loading,
        error,
        data,
        get,
        post,
        put,
        delete: del,
    }
}
```

## Creating Custom Dashboard Widgets

### Widget Component

```vue
<!-- resources/js/Components/Widgets/CustomStatsWidget.vue -->
<script setup>
import { ref, onMounted } from 'vue'
import { useApi } from '@/Composables/useApi'
import Card from '@/Components/Containers/Card.vue'
import Spinner from '@/Components/Feedback/Spinner.vue'

const props = defineProps({
    endpoint: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        default: 'Statistics',
    },
    refreshInterval: {
        type: Number,
        default: 60000, // 1 minute
    },
})

const { loading, error, data, get } = useApi()
const stats = ref([])

const fetchStats = async () => {
    try {
        const response = await get(props.endpoint)
        stats.value = response.stats
    } catch (e) {
        console.error('Failed to fetch stats:', e)
    }
}

onMounted(() => {
    fetchStats()

    if (props.refreshInterval > 0) {
        setInterval(fetchStats, props.refreshInterval)
    }
})
</script>

<template>
    <Card>
        <template #header>
            <h3 class="text-lg font-semibold">{{ title }}</h3>
        </template>

        <div v-if="loading" class="flex justify-center p-8">
            <Spinner />
        </div>

        <div v-else-if="error" class="p-4 text-red-600">
            {{ error }}
        </div>

        <div v-else class="grid grid-cols-2 gap-4 p-4">
            <div v-for="stat in stats" :key="stat.label" class="text-center">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ stat.value }}
                </div>
                <div class="text-sm text-gray-500">
                    {{ stat.label }}
                </div>
            </div>
        </div>
    </Card>
</template>
```

### Widget Registration

```php
<?php
// app/Providers/WidgetServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WidgetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('widgets', function () {
            return new \App\Services\WidgetRegistry();
        });
    }

    public function boot(): void
    {
        app('widgets')->register('custom-stats', [
            'name' => 'Custom Statistics',
            'component' => 'CustomStatsWidget',
            'props' => [
                'endpoint' => '/api/dashboard/stats',
                'title' => 'My Stats',
            ],
        ]);
    }
}
```

## Extending Models

### Using Traits

```php
<?php
// app/Models/Traits/HasUuid.php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getUuidColumn()})) {
                $model->{$model->getUuidColumn()} = (string) Str::uuid();
            }
        });
    }

    public function getUuidColumn(): string
    {
        return 'uuid';
    }

    public function getRouteKeyName(): string
    {
        return $this->getUuidColumn();
    }
}
```

### Model Observers

```php
<?php
// app/Observers/MyModuleObserver.php

namespace App\Observers;

use App\Models\MyModule;
use Illuminate\Support\Facades\Cache;

class MyModuleObserver
{
    public function created(MyModule $module): void
    {
        Cache::tags(['my-modules'])->flush();

        activity()
            ->performedOn($module)
            ->causedBy(auth()->user())
            ->log('created');
    }

    public function updated(MyModule $module): void
    {
        Cache::tags(['my-modules'])->flush();

        activity()
            ->performedOn($module)
            ->causedBy(auth()->user())
            ->withProperties(['changes' => $module->getChanges()])
            ->log('updated');
    }

    public function deleted(MyModule $module): void
    {
        Cache::tags(['my-modules'])->flush();

        activity()
            ->performedOn($module)
            ->causedBy(auth()->user())
            ->log('deleted');
    }
}
```

Register in `AppServiceProvider`:

```php
public function boot(): void
{
    MyModule::observe(MyModuleObserver::class);
}
```

## API Extensions

### Custom API Resources

```php
<?php
// app/Http/Resources/MyModuleResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyModuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'active' => $this->active,
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'links' => [
                'self' => route('api.my-module.show', $this),
                'edit' => route('my-module.edit', $this),
            ],
        ];
    }
}
```

### API Controller

```php
<?php
// app/Http/Controllers/Api/MyModuleController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MyModuleResource;
use App\Models\MyModule;
use App\Services\MyModuleService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MyModuleController extends Controller
{
    public function __construct(
        protected MyModuleService $service
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $items = $this->service->list($request->all());

        return MyModuleResource::collection($items);
    }

    public function store(Request $request): MyModuleResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $item = $this->service->create($validated);

        return new MyModuleResource($item);
    }

    public function show(MyModule $myModule): MyModuleResource
    {
        return new MyModuleResource($myModule->load('user'));
    }

    public function update(Request $request, MyModule $myModule): MyModuleResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $item = $this->service->update($myModule, $validated);

        return new MyModuleResource($item);
    }

    public function destroy(MyModule $myModule): \Illuminate\Http\Response
    {
        $this->service->delete($myModule);

        return response()->noContent();
    }
}
```

## See Also

- [Customization](/guide/advanced/customization.md) - Customizing existing features
- [Security](/guide/advanced/security.md) - Security best practices
- [CLI Commands](/guide/cli/) - Available CLI commands
