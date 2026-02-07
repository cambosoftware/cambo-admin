# LogsActivity Trait

The `LogsActivity` trait automatically logs model changes for audit trails.

## Usage

Add the trait to any Eloquent model:

```php
<?php

namespace App\Models;

use CamboSoftware\CamboAdmin\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use LogsActivity;
}
```

## Automatic Logging

Once added, the trait automatically logs:

- **Created** - When a new model is created
- **Updated** - When a model is modified (includes changed attributes)
- **Deleted** - When a model is deleted

## Configuration

### Customize Logged Attributes

Override which attributes to log:

```php
class Product extends Model
{
    use LogsActivity;

    // Only log these attributes
    protected static $logAttributes = ['name', 'price', 'status'];

    // Or log all except these
    protected static $logAttributesExcept = ['updated_at', 'password'];
}
```

### Custom Log Name

```php
class Product extends Model
{
    use LogsActivity;

    protected static $logName = 'products';
}
```

### Disable Logging Conditionally

```php
class Product extends Model
{
    use LogsActivity;

    public function shouldLogActivity(): bool
    {
        // Don't log if user is admin
        return !auth()->user()?->hasRole('admin');
    }
}
```

## Accessing Activity Logs

```php
use CamboSoftware\CamboAdmin\Models\Activity;

// Get all activities for a model
$activities = Activity::forModel($product)->get();

// Get recent activities
$recent = Activity::latest()->take(10)->get();

// Filter by event type
$creates = Activity::where('event', 'created')->get();

// Filter by user
$userActivities = Activity::causedBy($user)->get();
```

## Activity Log Structure

Each activity record contains:

| Field | Type | Description |
|-------|------|-------------|
| `log_name` | string | Log channel name |
| `description` | string | Human-readable description |
| `subject_type` | string | Model class name |
| `subject_id` | int | Model ID |
| `causer_type` | string | User class name |
| `causer_id` | int | User ID who caused the change |
| `properties` | json | Changed attributes (old/new values) |
| `event` | string | Event type (created, updated, deleted) |
| `created_at` | datetime | When the activity occurred |

## Example Output

```php
// After updating a product
$activity = Activity::latest()->first();

$activity->description; // "Product updated"
$activity->event;       // "updated"
$activity->properties;
// [
//     'old' => ['price' => 10.00],
//     'new' => ['price' => 15.00]
// ]
$activity->causer;      // User model who made the change
$activity->subject;     // Product model that was changed
```

## Disable Logging Temporarily

```php
// Disable for a single operation
Activity::withoutLogs(function () {
    $product->update(['price' => 100]);
});

// Disable globally
Activity::disableLogging();
$product->update(['price' => 100]);
Activity::enableLogging();
```

## Source Code

**Location:** `src/Models/Traits/LogsActivity.php`
