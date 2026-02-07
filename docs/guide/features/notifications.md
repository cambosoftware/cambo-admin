# Notifications

CamboAdmin provides a comprehensive notification system for sending and managing user notifications through multiple channels including database, email, and real-time broadcasts.

## Introduction

The notification module allows you to:

- Store notifications in the database
- Send email notifications
- Broadcast real-time notifications via WebSockets
- Mark notifications as read/unread
- Filter and query notifications
- Create custom notification types

## Configuration

### Enable/Disable Module

```php
// config/cambo-admin.php
'modules' => [
    'notifications' => true,
],
```

### Notification Channels

```php
// config/cambo-admin.php
'notifications' => [
    'database' => true,      // Store in database
    'email' => true,         // Send email notifications
    'real_time' => false,    // Requires Laravel Echo / Pusher
],
```

### Real-time Setup

For real-time notifications, configure Laravel Echo:

```env
# .env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
```

```javascript
// resources/js/bootstrap.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
```

## Usage Examples

### The Notification Model

```php
use CamboSoftware\CamboAdmin\Models\Notification;
```

#### Notification Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `user_id` | uuid | User who receives the notification |
| `type` | string | Notification type (info, success, warning, error) |
| `icon` | string | Icon name (optional) |
| `title` | string | Notification title |
| `body` | string | Notification body/content (optional) |
| `action_url` | string | URL to navigate on click (optional) |
| `action_text` | string | Action button text (optional) |
| `data` | array | Additional data (optional) |
| `read_at` | datetime | When notification was read |

### Creating Notifications

#### Basic Notification

```php
use CamboSoftware\CamboAdmin\Models\Notification;
use App\Models\User;

$user = User::find(1);

// Using the static notify method
$notification = Notification::notify($user, [
    'type' => 'info',
    'title' => 'Welcome!',
    'body' => 'Thank you for joining our platform.',
]);
```

#### With Action URL

```php
$notification = Notification::notify($user, [
    'type' => 'success',
    'icon' => 'check-circle',
    'title' => 'Order Confirmed',
    'body' => 'Your order #12345 has been confirmed.',
    'action_url' => '/orders/12345',
    'action_text' => 'View Order',
]);
```

#### With Additional Data

```php
$notification = Notification::notify($user, [
    'type' => 'warning',
    'icon' => 'exclamation-triangle',
    'title' => 'Subscription Expiring',
    'body' => 'Your subscription expires in 3 days.',
    'action_url' => '/billing',
    'action_text' => 'Renew Now',
    'data' => [
        'subscription_id' => 123,
        'expires_at' => '2024-12-31',
        'plan' => 'Pro',
    ],
]);
```

#### Notify Multiple Users

```php
$users = User::where('role', 'admin')->get();

Notification::notifyMany($users, [
    'type' => 'info',
    'icon' => 'bell',
    'title' => 'System Maintenance',
    'body' => 'Scheduled maintenance will occur tonight at 2 AM.',
]);
```

#### Using Eloquent Create

```php
$notification = Notification::create([
    'user_id' => $user->id,
    'type' => 'error',
    'icon' => 'x-circle',
    'title' => 'Payment Failed',
    'body' => 'We could not process your payment. Please update your payment method.',
    'action_url' => '/billing/payment-methods',
    'action_text' => 'Update Payment',
    'data' => [
        'order_id' => 456,
        'amount' => 99.99,
    ],
]);
```

### Notification Types

```php
// Info (default) - blue
Notification::notify($user, [
    'type' => 'info',
    'title' => 'Information',
    'body' => 'Here is some information.',
]);

// Success - green
Notification::notify($user, [
    'type' => 'success',
    'title' => 'Success!',
    'body' => 'Action completed successfully.',
]);

// Warning - yellow
Notification::notify($user, [
    'type' => 'warning',
    'title' => 'Warning',
    'body' => 'Please pay attention to this.',
]);

// Error - red
Notification::notify($user, [
    'type' => 'error',
    'title' => 'Error',
    'body' => 'Something went wrong.',
]);
```

### Reading Notifications

#### Mark as Read

```php
$notification = Notification::find($id);
$notification->markAsRead();
```

#### Mark as Unread

```php
$notification->markAsUnread();
```

#### Check if Read

```php
if ($notification->isRead()) {
    // Notification has been read
}
```

#### Mark All as Read

```php
Notification::where('user_id', $user->id)
    ->whereNull('read_at')
    ->update(['read_at' => now()]);
```

### Querying Notifications

#### Get User Notifications

```php
// All notifications
$notifications = Notification::where('user_id', $user->id)
    ->latest()
    ->get();

// Unread only
$unreadNotifications = Notification::where('user_id', $user->id)
    ->unread()
    ->latest()
    ->get();

// Read only
$readNotifications = Notification::where('user_id', $user->id)
    ->read()
    ->latest()
    ->get();
```

#### Paginated Notifications

```php
$notifications = Notification::where('user_id', auth()->id())
    ->latest()
    ->paginate(10);
```

#### Count Unread

```php
$unreadCount = Notification::where('user_id', $user->id)
    ->unread()
    ->count();
```

#### Filter by Type

```php
$errorNotifications = Notification::where('user_id', $user->id)
    ->where('type', 'error')
    ->latest()
    ->get();
```

### User Relationship

Add a notifications relationship to your User model:

```php
// app/Models/User.php
use CamboSoftware\CamboAdmin\Models\Notification;

class User extends Authenticatable
{
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    public function unreadNotificationsCount()
    {
        return $this->unreadNotifications()->count();
    }
}
```

Usage:

```php
$user->notifications; // All notifications
$user->unreadNotifications; // Unread only
$user->unreadNotificationsCount(); // Count
```

### Email Notifications

Create a notification class that sends email:

```php
<?php

namespace App\Notifications;

use CamboSoftware\CamboAdmin\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification;

class OrderConfirmed extends BaseNotification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Confirmed')
            ->line('Your order #' . $this->order->id . ' has been confirmed.')
            ->action('View Order', url('/orders/' . $this->order->id))
            ->line('Thank you for your purchase!');
    }

    public function toDatabase($notifiable)
    {
        // Create CamboAdmin notification
        Notification::notify($notifiable, [
            'type' => 'success',
            'icon' => 'check-circle',
            'title' => 'Order Confirmed',
            'body' => 'Your order #' . $this->order->id . ' has been confirmed.',
            'action_url' => '/orders/' . $this->order->id,
            'action_text' => 'View Order',
            'data' => [
                'order_id' => $this->order->id,
            ],
        ]);

        return [];
    }
}
```

Send the notification:

```php
use App\Notifications\OrderConfirmed;

$user->notify(new OrderConfirmed($order));
```

### Real-time Notifications

For real-time broadcasting, create a notification event:

```php
<?php

namespace App\Events;

use CamboSoftware\CamboAdmin\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->notification->user_id);
    }

    public function broadcastAs()
    {
        return 'notification.created';
    }
}
```

Listen in Vue:

```javascript
// Notification bell component
mounted() {
    Echo.private(`user.${this.userId}`)
        .listen('.notification.created', (data) => {
            this.notifications.unshift(data.notification);
            this.unreadCount++;
            this.showToast(data.notification);
        });
}
```

### Notification Controller

```php
<?php

namespace App\Http\Controllers;

use CamboSoftware\CamboAdmin\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);
    }

    public function unread()
    {
        return Notification::where('user_id', auth()->id())
            ->unread()
            ->latest()
            ->get();
    }

    public function markAsRead(Notification $notification)
    {
        $this->authorize('update', $notification);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    public function destroyAll()
    {
        Notification::where('user_id', auth()->id())->delete();

        return response()->json(['success' => true]);
    }
}
```

Routes:

```php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);
    Route::delete('/notifications', [NotificationController::class, 'destroyAll']);
});
```

## Available Options

### Notification Types

| Type | Color | Use Case |
|------|-------|----------|
| `info` | Blue | General information |
| `success` | Green | Successful actions |
| `warning` | Yellow/Orange | Warnings, attention needed |
| `error` | Red | Errors, failures |

### Common Icons

| Icon | Description |
|------|-------------|
| `bell` | General notification |
| `check-circle` | Success |
| `exclamation-triangle` | Warning |
| `x-circle` | Error |
| `information-circle` | Info |
| `user` | User related |
| `shopping-cart` | Order related |
| `currency-dollar` | Payment related |
| `envelope` | Message related |
| `calendar` | Event related |

### Notification Model Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `isRead()` | bool | Check if notification is read |
| `markAsRead()` | self | Mark notification as read |
| `markAsUnread()` | self | Mark notification as unread |
| `notify($user, $data)` | Notification | Create notification for user |
| `notifyMany($users, $data)` | void | Create notifications for multiple users |

### Query Scopes

| Scope | Description |
|-------|-------------|
| `unread()` | Filter unread notifications |
| `read()` | Filter read notifications |

## Vue Component Example

```vue
<template>
  <div class="relative">
    <button @click="open = !open" class="relative p-2">
      <BellIcon class="h-6 w-6" />
      <span
        v-if="unreadCount > 0"
        class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
      >
        {{ unreadCount > 9 ? '9+' : unreadCount }}
      </span>
    </button>

    <div v-if="open" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border">
      <div class="p-4 border-b flex justify-between items-center">
        <h3 class="font-semibold">Notifications</h3>
        <button @click="markAllAsRead" class="text-sm text-primary hover:underline">
          Mark all as read
        </button>
      </div>

      <div class="max-h-96 overflow-y-auto">
        <div v-if="notifications.length === 0" class="p-4 text-center text-gray-500">
          No notifications
        </div>

        <div
          v-for="notification in notifications"
          :key="notification.id"
          class="p-4 border-b hover:bg-gray-50 cursor-pointer"
          :class="{ 'bg-blue-50': !notification.read_at }"
          @click="handleClick(notification)"
        >
          <div class="flex items-start gap-3">
            <div
              class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
              :class="getTypeClass(notification.type)"
            >
              <component :is="getIcon(notification)" class="w-4 h-4" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-medium text-sm">{{ notification.title }}</p>
              <p class="text-sm text-gray-500 truncate">{{ notification.body }}</p>
              <p class="text-xs text-gray-400 mt-1">{{ formatDate(notification.created_at) }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="p-4 border-t text-center">
        <a href="/notifications" class="text-sm text-primary hover:underline">
          View all notifications
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { BellIcon, CheckCircleIcon, ExclamationTriangleIcon, XCircleIcon, InformationCircleIcon } from '@heroicons/vue/24/outline';

const open = ref(false);
const notifications = ref([]);
const unreadCount = ref(0);

const fetchNotifications = async () => {
  const response = await fetch('/notifications/unread');
  notifications.value = await response.json();
  unreadCount.value = notifications.value.length;
};

const markAllAsRead = async () => {
  await fetch('/notifications/mark-all-read', { method: 'POST' });
  notifications.value.forEach(n => n.read_at = new Date());
  unreadCount.value = 0;
};

const handleClick = async (notification) => {
  if (!notification.read_at) {
    await fetch(`/notifications/${notification.id}/read`, { method: 'POST' });
    notification.read_at = new Date();
    unreadCount.value--;
  }
  if (notification.action_url) {
    window.location.href = notification.action_url;
  }
};

const getTypeClass = (type) => {
  const classes = {
    info: 'bg-blue-100 text-blue-600',
    success: 'bg-green-100 text-green-600',
    warning: 'bg-yellow-100 text-yellow-600',
    error: 'bg-red-100 text-red-600',
  };
  return classes[type] || classes.info;
};

const getIcon = (notification) => {
  const icons = {
    info: InformationCircleIcon,
    success: CheckCircleIcon,
    warning: ExclamationTriangleIcon,
    error: XCircleIcon,
  };
  return icons[notification.type] || icons.info;
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

onMounted(fetchNotifications);
</script>
```

## Database Cleanup

Clean up old notifications periodically:

```php
// app/Console/Commands/CleanOldNotifications.php
namespace App\Console\Commands;

use CamboSoftware\CamboAdmin\Models\Notification;
use Illuminate\Console\Command;

class CleanOldNotifications extends Command
{
    protected $signature = 'notifications:clean {--days=30}';
    protected $description = 'Delete old read notifications';

    public function handle()
    {
        $days = $this->option('days');

        $deleted = Notification::whereNotNull('read_at')
            ->where('read_at', '<', now()->subDays($days))
            ->delete();

        $this->info("Deleted {$deleted} old notifications.");
    }
}
```

Schedule in Kernel:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('notifications:clean --days=30')->weekly();
}
```
