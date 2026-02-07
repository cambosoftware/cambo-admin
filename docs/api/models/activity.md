# Activity Model

The `Activity` model stores activity logs for auditing and tracking changes in CamboAdmin.

## Description

The Activity model provides a complete activity logging system with support for tracking changes to models, recording user actions, storing old/new values, and request metadata. It uses UUIDs and includes a fluent builder for creating logs.

## Usage

```php
use CamboSoftware\CamboAdmin\Models\Activity;

// Using the fluent builder
Activity::log('User profile updated')
    ->on($user)
    ->by(auth()->user())
    ->withProperties(['old' => $old, 'attributes' => $new])
    ->save();
```

## Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | `uuid` | Unique identifier (UUID) |
| `log_name` | `string` | Log category name |
| `description` | `string` | Activity description |
| `subject_type` | `string\|null` | Subject model class |
| `subject_id` | `mixed\|null` | Subject model ID |
| `event` | `string\|null` | Event type (created, updated, deleted) |
| `causer_type` | `string\|null` | Causer model class (usually User) |
| `causer_id` | `mixed\|null` | Causer model ID |
| `properties` | `array` | Additional data (old/new values) |
| `batch_uuid` | `string\|null` | Batch identifier for grouped actions |
| `ip_address` | `string\|null` | Request IP address |
| `user_agent` | `string\|null` | Request user agent |

## Fillable Attributes

```php
protected $fillable = [
    'log_name',
    'description',
    'subject_type',
    'subject_id',
    'event',
    'causer_type',
    'causer_id',
    'properties',
    'batch_uuid',
    'ip_address',
    'user_agent',
];
```

## Casts

```php
protected $casts = [
    'properties' => 'array',
];
```

## Methods

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `subject()` | none | `MorphTo` | Get the subject model |
| `causer()` | none | `MorphTo` | Get the causer (user) |
| `getOldAttribute()` | none | `array` | Get old values |
| `getNewAttribute()` | none | `array` | Get new values |
| `getChangesAttribute()` | none | `array` | Get changes diff |
| `scopeInLog()` | `...$logNames` | `Builder` | Filter by log name |
| `scopeForEvent()` | `string $event` | `Builder` | Filter by event type |
| `scopeForSubject()` | `Model $subject` | `Builder` | Filter by subject |
| `scopeCausedBy()` | `Model $causer` | `Builder` | Filter by causer |
| `scopeForBatch()` | `string $batchUuid` | `Builder` | Filter by batch |
| `log()` | `string $description` (static) | `ActivityBuilder` | Start building an activity |

## Accessors

### getOldAttribute()

Returns the old values from properties.

```php
$old = $activity->old;
// Returns: ['name' => 'John', 'email' => 'john@old.com']
```

### getNewAttribute()

Returns the new values from properties.

```php
$new = $activity->new;
// Returns: ['name' => 'Jane', 'email' => 'jane@new.com']
```

### getChangesAttribute()

Returns a diff showing what changed.

```php
$changes = $activity->changes;
// Returns:
// [
//     'name' => ['old' => 'John', 'new' => 'Jane'],
//     'email' => ['old' => 'john@old.com', 'new' => 'jane@new.com'],
// ]
```

## Scope Methods

### scopeInLog()

```php
public function scopeInLog($query, ...$logNames)
```

Filter activities by log name(s).

**Example:**

```php
$activities = Activity::inLog('user', 'post')->get();
```

### scopeForEvent()

```php
public function scopeForEvent($query, string $event)
```

Filter activities by event type.

**Example:**

```php
$created = Activity::forEvent('created')->get();
$deleted = Activity::forEvent('deleted')->get();
```

### scopeForSubject()

```php
public function scopeForSubject($query, Model $subject)
```

Filter activities for a specific subject.

**Example:**

```php
$userActivities = Activity::forSubject($user)->get();
```

### scopeCausedBy()

```php
public function scopeCausedBy($query, Model $causer)
```

Filter activities caused by a specific user.

**Example:**

```php
$adminActions = Activity::causedBy($admin)->get();
```

### scopeForBatch()

```php
public function scopeForBatch($query, string $batchUuid)
```

Filter activities by batch UUID.

**Example:**

```php
$batchActivities = Activity::forBatch($batchUuid)->get();
```

## ActivityBuilder

The static `log()` method returns an `ActivityBuilder` for fluent activity creation.

### Builder Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `inLog()` | `string $logName` | Set the log name |
| `event()` | `string $event` | Set the event type |
| `on()` | `Model $subject` | Set the subject model |
| `by()` | `?Model $causer` | Set the causer (user) |
| `withProperties()` | `array $properties` | Set properties |
| `batch()` | `string $uuid` | Set batch UUID |
| `withRequest()` | none | Add IP and user agent |
| `save()` | none | Save the activity |

### Builder Example

```php
Activity::log('User was promoted to admin')
    ->inLog('user')
    ->event('promoted')
    ->on($user)
    ->by(auth()->user())
    ->withProperties([
        'old' => ['role' => 'user'],
        'attributes' => ['role' => 'admin'],
    ])
    ->withRequest()
    ->save();
```

## Complete Usage Example

```php
use CamboSoftware\CamboAdmin\Models\Activity;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::query()
            ->with(['subject', 'causer'])
            ->when($request->log, fn($q) => $q->inLog($request->log))
            ->when($request->event, fn($q) => $q->forEvent($request->event))
            ->when($request->user_id, function ($q) use ($request) {
                $user = User::find($request->user_id);
                return $q->causedBy($user);
            })
            ->latest()
            ->paginate(20);

        return response()->json($activities);
    }

    public function show(Activity $activity)
    {
        return response()->json([
            'activity' => $activity->load(['subject', 'causer']),
            'changes' => $activity->changes,
        ]);
    }

    public function forSubject(Request $request, string $type, int $id)
    {
        $model = app($type)->find($id);

        $activities = Activity::forSubject($model)
            ->with('causer')
            ->latest()
            ->get();

        return response()->json($activities);
    }
}
```

## Manual Logging Example

```php
use CamboSoftware\CamboAdmin\Models\Activity;
use Illuminate\Support\Str;

class OrderService
{
    public function processOrder(Order $order): void
    {
        $batchUuid = Str::uuid();

        // Log order processing
        Activity::log('Order processing started')
            ->inLog('order')
            ->event('processing')
            ->on($order)
            ->batch($batchUuid)
            ->withRequest()
            ->save();

        // Process payment
        $this->processPayment($order);

        Activity::log('Payment processed successfully')
            ->inLog('order')
            ->event('payment')
            ->on($order)
            ->batch($batchUuid)
            ->withProperties(['amount' => $order->total])
            ->save();

        // Update inventory
        $this->updateInventory($order);

        Activity::log('Order completed')
            ->inLog('order')
            ->event('completed')
            ->on($order)
            ->batch($batchUuid)
            ->save();
    }
}
```

## Viewing Activity History

```php
// Get all activities for a user
$userHistory = Activity::forSubject($user)
    ->with('causer')
    ->latest()
    ->get();

// Get all actions performed by an admin
$adminActions = Activity::causedBy($admin)
    ->with('subject')
    ->latest()
    ->take(50)
    ->get();

// Get recent deletions
$deletions = Activity::forEvent('deleted')
    ->with(['subject', 'causer'])
    ->latest()
    ->take(20)
    ->get();

// Get activities in a batch
$batchActivities = Activity::forBatch($batchUuid)
    ->orderBy('created_at')
    ->get();
```

## Database Schema

```php
Schema::create('activity_logs', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('log_name')->nullable()->index();
    $table->text('description');
    $table->nullableMorphs('subject');
    $table->string('event')->nullable();
    $table->nullableMorphs('causer');
    $table->json('properties')->nullable();
    $table->uuid('batch_uuid')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->timestamps();

    $table->index('created_at');
});
```

## Source Code

**Location:** `src/Models/Activity.php`

**Namespace:** `CamboSoftware\CamboAdmin\Models`

**Table:** `activity_logs`
