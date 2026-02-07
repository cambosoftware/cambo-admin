# Activity Log

CamboAdmin includes a comprehensive activity logging system to track all important actions within your application, providing a complete audit trail for security and debugging purposes.

## Introduction

The activity log module allows you to:

- Automatically log model events (create, update, delete)
- Manually log custom activities
- Track which user performed each action
- Store before/after values for changes
- Filter and search activity logs
- Group activities by batch
- Track IP addresses and user agents

## Configuration

### Enable/Disable Module

```php
// config/cambo-admin.php
'modules' => [
    'activity-log' => true,
],
```

### Activity Log Settings

```php
// config/cambo-admin.php
'activity_log' => [
    'enabled' => true,        // Global enable/disable
    'log_name' => 'default',  // Default log name
    'retention_days' => 90,   // Days to keep logs (null = forever)
],
```

### Excluded Attributes

Sensitive attributes are automatically excluded from logging:

- `password`
- `remember_token`
- `two_factor_secret`
- `two_factor_recovery_codes`

## Usage Examples

### The Activity Model

```php
use CamboSoftware\CamboAdmin\Models\Activity;
```

#### Activity Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | uuid | Unique identifier |
| `log_name` | string | Name of the log category |
| `description` | string | Human-readable description |
| `subject_type` | string | Model class name |
| `subject_id` | string | Model ID |
| `event` | string | Event type (created, updated, deleted) |
| `causer_type` | string | User model class |
| `causer_id` | string | User ID who caused the activity |
| `properties` | array | Old and new values |
| `batch_uuid` | string | Group related activities |
| `ip_address` | string | IP address of the request |
| `user_agent` | string | Browser user agent |
| `created_at` | datetime | When activity occurred |

### Automatic Model Logging

Add the `LogsActivity` trait to any model you want to track:

```php
<?php

namespace App\Models;

use CamboSoftware\CamboAdmin\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use LogsActivity;

    protected $fillable = ['title', 'content', 'status', 'author_id'];
}
```

Now all create, update, and delete events will be automatically logged.

### Customizing Logged Attributes

#### Specify Which Attributes to Log

```php
class Post extends Model
{
    use LogsActivity;

    // Only log these attributes
    protected $loggableAttributes = ['title', 'status', 'published_at'];
}
```

#### Exclude Specific Attributes

```php
class User extends Model
{
    use LogsActivity;

    // Exclude these from logging (in addition to defaults)
    protected $excludedFromLog = ['api_token', 'email_verified_at'];
}
```

### Custom Log Name

```php
class Order extends Model
{
    use LogsActivity;

    // Use custom log name instead of 'default'
    protected $logName = 'orders';
}
```

### Custom Activity Description

```php
class Product extends Model
{
    use LogsActivity;

    // Customize how the subject is described in logs
    public function getActivityDescription(): string
    {
        return "Product \"{$this->name}\" (SKU: {$this->sku})";
    }
}
```

### Manual Activity Logging

Use the fluent builder API for custom activities:

#### Basic Manual Log

```php
use CamboSoftware\CamboAdmin\Models\Activity;

Activity::log('User exported report')
    ->save();
```

#### With Log Category

```php
Activity::log('Settings updated')
    ->inLog('settings')
    ->save();
```

#### With Subject Model

```php
Activity::log('Post published')
    ->inLog('posts')
    ->event('published')
    ->on($post)
    ->save();
```

#### With Causer (User)

```php
Activity::log('Password changed')
    ->inLog('security')
    ->event('password_changed')
    ->on($user)
    ->by($admin) // The user who made the change
    ->save();
```

#### With Custom Properties

```php
Activity::log('Bulk import completed')
    ->inLog('imports')
    ->event('import_completed')
    ->withProperties([
        'file_name' => 'products.csv',
        'records_imported' => 150,
        'records_skipped' => 5,
        'errors' => [],
    ])
    ->save();
```

#### With Request Info

```php
Activity::log('Login successful')
    ->inLog('auth')
    ->event('login')
    ->on($user)
    ->by($user)
    ->withRequest() // Adds IP address and user agent
    ->save();
```

#### With Batch UUID

Group related activities:

```php
use Illuminate\Support\Str;

$batchUuid = Str::uuid()->toString();

Activity::log('Started order processing')
    ->inLog('orders')
    ->on($order)
    ->batch($batchUuid)
    ->save();

// Process items...
foreach ($order->items as $item) {
    Activity::log("Processed item: {$item->name}")
        ->inLog('orders')
        ->on($item)
        ->batch($batchUuid)
        ->save();
}

Activity::log('Order processing completed')
    ->inLog('orders')
    ->on($order)
    ->batch($batchUuid)
    ->save();
```

### Temporarily Disable Logging

```php
// Disable for a model instance
$user->disableActivityLog();
$user->update(['name' => 'New Name']); // Not logged
$user->enableActivityLog();

// Or use a closure
$post->disableActivityLog();
$post->update($data);
$post->enableActivityLog();
```

### Querying Activities

#### Get All Activities

```php
$activities = Activity::latest()->get();
```

#### Filter by Log Name

```php
// Single log
$authActivities = Activity::inLog('auth')->get();

// Multiple logs
$activities = Activity::inLog('auth', 'security')->get();
```

#### Filter by Event Type

```php
$creations = Activity::forEvent('created')->get();
$updates = Activity::forEvent('updated')->get();
$deletions = Activity::forEvent('deleted')->get();
```

#### Filter by Subject

```php
// Get all activities for a specific model
$postActivities = Activity::forSubject($post)->get();
```

#### Filter by Causer (User)

```php
// Get all activities caused by a specific user
$userActivities = Activity::causedBy($user)->get();
```

#### Filter by Batch

```php
// Get all activities in a batch
$batchActivities = Activity::forBatch($batchUuid)->get();
```

#### Get Changes

```php
$activity = Activity::find($id);

// Get old values
$oldValues = $activity->old;
// ['title' => 'Old Title', 'status' => 'draft']

// Get new values
$newValues = $activity->new;
// ['title' => 'New Title', 'status' => 'published']

// Get changes (diff)
$changes = $activity->changes;
// [
//     'title' => ['old' => 'Old Title', 'new' => 'New Title'],
//     'status' => ['old' => 'draft', 'new' => 'published'],
// ]
```

### Relationships

#### Get the Subject

```php
$activity = Activity::find($id);

// Get the model that was affected
$subject = $activity->subject; // Returns Post, User, etc.
```

#### Get the Causer

```php
$activity = Activity::find($id);

// Get the user who caused the activity
$user = $activity->causer;
```

#### Get Activities for a Model

```php
class Post extends Model
{
    use LogsActivity;

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}

// Usage
$post->activities()->latest()->get();
```

### Activity Log Controller

```php
<?php

namespace App\Http\Controllers\Admin;

use CamboSoftware\CamboAdmin\Models\Activity;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['subject', 'causer'])
            ->latest();

        // Filter by log name
        if ($request->log) {
            $query->inLog($request->log);
        }

        // Filter by event
        if ($request->event) {
            $query->forEvent($request->event);
        }

        // Filter by causer
        if ($request->user_id) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter by date range
        if ($request->from) {
            $query->where('created_at', '>=', $request->from);
        }
        if ($request->to) {
            $query->where('created_at', '<=', $request->to);
        }

        // Search in description
        if ($request->search) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        return $query->paginate(25);
    }

    public function show(Activity $activity)
    {
        return $activity->load(['subject', 'causer']);
    }

    public function forSubject(Request $request, string $type, string $id)
    {
        return Activity::where('subject_type', $type)
            ->where('subject_id', $id)
            ->with('causer')
            ->latest()
            ->paginate(25);
    }

    public function forUser(Request $request, $userId)
    {
        return Activity::causedBy(User::find($userId))
            ->with('subject')
            ->latest()
            ->paginate(25);
    }
}
```

## Available Options

### LogsActivity Trait Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `$logName` | string | Model snake_case name | Log category name |
| `$loggableAttributes` | array | `['*']` | Attributes to log |
| `$excludedFromLog` | array | `[]` | Attributes to exclude |
| `$activityLogDisabled` | bool | false | Disable logging for instance |

### LogsActivity Trait Methods

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| `logActivity()` | `string $event, ?string $description` | Activity | Log a custom activity |
| `disableActivityLog()` | - | self | Disable logging |
| `enableActivityLog()` | - | self | Enable logging |
| `activities()` | - | MorphMany | Get all activities |

### Activity Builder Methods

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| `log()` | `string $description` | ActivityBuilder | Start building activity |
| `inLog()` | `string $logName` | self | Set log name |
| `event()` | `string $event` | self | Set event type |
| `on()` | `Model $subject` | self | Set subject model |
| `by()` | `?Model $causer` | self | Set causer (user) |
| `withProperties()` | `array $properties` | self | Set custom properties |
| `batch()` | `string $uuid` | self | Set batch UUID |
| `withRequest()` | - | self | Add IP and user agent |
| `save()` | - | Activity | Save the activity |

### Activity Model Scopes

| Scope | Parameters | Description |
|-------|------------|-------------|
| `inLog()` | `string ...$logNames` | Filter by log name(s) |
| `forEvent()` | `string $event` | Filter by event type |
| `forSubject()` | `Model $subject` | Filter by subject model |
| `causedBy()` | `Model $causer` | Filter by causer |
| `forBatch()` | `string $batchUuid` | Filter by batch |

## Management Interface

CamboAdmin provides a built-in activity log viewer:

- `/admin/activity-log` - List all activities
- `/admin/activity-log/{id}` - View activity details

### Filtering Options

- **Log Name**: Filter by category (auth, orders, etc.)
- **Event Type**: Filter by created, updated, deleted
- **User**: Filter by who performed the action
- **Date Range**: Filter by date range
- **Search**: Search in descriptions

## Cleanup Command

Create a command to clean old activity logs:

```php
<?php

namespace App\Console\Commands;

use CamboSoftware\CamboAdmin\Models\Activity;
use Illuminate\Console\Command;

class CleanActivityLogs extends Command
{
    protected $signature = 'activity-log:clean {--days=90}';
    protected $description = 'Delete old activity logs';

    public function handle()
    {
        $days = $this->option('days');

        if (!$days) {
            $days = config('cambo-admin.activity_log.retention_days', 90);
        }

        if (!$days) {
            $this->warn('No retention period set. Skipping cleanup.');
            return;
        }

        $deleted = Activity::where('created_at', '<', now()->subDays($days))->delete();

        $this->info("Deleted {$deleted} activity logs older than {$days} days.");
    }
}
```

Schedule in Kernel:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('activity-log:clean')->daily();
}
```

## Best Practices

1. **Use meaningful log names**: Categorize activities (auth, orders, users, settings)
2. **Use batch UUIDs**: Group related activities for complex operations
3. **Include request info**: Always call `withRequest()` for security-sensitive activities
4. **Set retention policies**: Don't keep logs forever; clean old entries
5. **Exclude sensitive data**: Never log passwords or tokens
6. **Use custom descriptions**: Make logs human-readable

```php
// Good: Descriptive and categorized
Activity::log("User exported {$count} orders to CSV")
    ->inLog('exports')
    ->event('export')
    ->by(auth()->user())
    ->withProperties(['format' => 'csv', 'count' => $count])
    ->withRequest()
    ->save();

// Bad: Vague and uncategorized
Activity::log('Export done')->save();
```

## Security Considerations

1. **Access Control**: Restrict activity log access to admins
2. **PII Handling**: Be careful about logging personal information
3. **Log Integrity**: Consider using append-only storage for audit requirements
4. **Encryption**: Consider encrypting sensitive property data

```php
// Protect activity log routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/activity-log', [ActivityLogController::class, 'index']);
});
```
