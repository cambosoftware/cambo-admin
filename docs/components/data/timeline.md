# Timeline

A component for displaying chronological events or activities in a vertical timeline format.

## Import

```js
import { Timeline, TimelineItem } from '@cambosoftware/cambo-admin'
```

## Timeline Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `items` | `Array` | `[]` | Array of timeline items: `[{ id, title, description?, date?, icon?, variant? }]` |
| `align` | `String` | `'left'` | Timeline alignment: `'left'`, `'right'`, `'alternate'` |
| `lineColor` | `String` | `'gray'` | Color of the timeline line |

## TimelineItem Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `String` | - | Item title |
| `description` | `String` | `null` | Item description |
| `date` | `String` | `null` | Date or time label |
| `icon` | `Component` | `null` | Custom icon component |
| `variant` | `String` | `'default'` | Color variant for the dot |
| `align` | `String` | `'left'` | Item alignment |

## Slots

| Slot | Props | Description |
|------|-------|-------------|
| `default` | `{ items }` | Custom timeline content |

## Basic Example

```vue
<template>
  <Timeline :items="events" />
</template>

<script setup>
import { Timeline } from '@cambosoftware/cambo-admin'

const events = [
  {
    id: 1,
    title: 'Order placed',
    description: 'Your order has been received',
    date: 'Feb 5, 2024 10:00 AM'
  },
  {
    id: 2,
    title: 'Payment confirmed',
    description: 'Payment processed successfully',
    date: 'Feb 5, 2024 10:05 AM'
  },
  {
    id: 3,
    title: 'Shipped',
    description: 'Package is on its way',
    date: 'Feb 6, 2024 2:30 PM'
  },
  {
    id: 4,
    title: 'Delivered',
    description: 'Package delivered to recipient',
    date: 'Feb 8, 2024 11:15 AM'
  }
]
</script>
```

## With Variants

```vue
<template>
  <Timeline :items="activities" />
</template>

<script setup>
const activities = [
  {
    id: 1,
    title: 'Task completed',
    description: 'Successfully finished the project review',
    date: '2 hours ago',
    variant: 'success'
  },
  {
    id: 2,
    title: 'Warning issued',
    description: 'Server CPU usage exceeded 80%',
    date: '4 hours ago',
    variant: 'warning'
  },
  {
    id: 3,
    title: 'Error detected',
    description: 'Database connection failed',
    date: 'Yesterday',
    variant: 'danger'
  },
  {
    id: 4,
    title: 'Information',
    description: 'Scheduled maintenance in 24 hours',
    date: 'Yesterday',
    variant: 'info'
  }
]
</script>
```

## Right Aligned

```vue
<template>
  <Timeline :items="events" align="right" />
</template>
```

## Alternating Layout

```vue
<template>
  <Timeline :items="milestones" align="alternate" />
</template>

<script setup>
const milestones = [
  { id: 1, title: 'Project Started', date: 'Jan 2024' },
  { id: 2, title: 'Phase 1 Complete', date: 'Mar 2024' },
  { id: 3, title: 'Beta Launch', date: 'Jun 2024' },
  { id: 4, title: 'Public Release', date: 'Sep 2024' },
]
</script>
```

## With Icons

```vue
<template>
  <Timeline :items="processSteps" />
</template>

<script setup>
import { CheckIcon, TruckIcon, CreditCardIcon, ShoppingCartIcon } from '@heroicons/vue/24/outline'

const processSteps = [
  {
    id: 1,
    title: 'Order Placed',
    description: 'Your order #12345 has been placed',
    date: 'Feb 5, 2024',
    icon: ShoppingCartIcon
  },
  {
    id: 2,
    title: 'Payment Processed',
    description: 'Payment of $299 was successful',
    date: 'Feb 5, 2024',
    icon: CreditCardIcon,
    variant: 'success'
  },
  {
    id: 3,
    title: 'Shipped',
    description: 'Package shipped via Express',
    date: 'Feb 6, 2024',
    icon: TruckIcon
  },
  {
    id: 4,
    title: 'Delivered',
    description: 'Package delivered successfully',
    date: 'Feb 8, 2024',
    icon: CheckIcon,
    variant: 'success'
  }
]
</script>
```

## Custom Timeline Items

```vue
<template>
  <Timeline>
    <TimelineItem
      v-for="event in events"
      :key="event.id"
      :title="event.title"
      :date="event.date"
      :variant="event.variant"
    >
      <p class="text-gray-600">{{ event.description }}</p>
      <div v-if="event.image" class="mt-2">
        <img :src="event.image" class="rounded-lg w-48" />
      </div>
      <div v-if="event.actions" class="mt-3 flex gap-2">
        <Button
          v-for="action in event.actions"
          :key="action.label"
          size="sm"
          :variant="action.variant || 'secondary'"
          @click="action.handler"
        >
          {{ action.label }}
        </Button>
      </div>
    </TimelineItem>
  </Timeline>
</template>
```

## Activity Feed

```vue
<template>
  <Card title="Recent Activity">
    <Timeline>
      <TimelineItem
        v-for="activity in recentActivity"
        :key="activity.id"
        :variant="activity.type"
      >
        <div class="flex items-center gap-2">
          <Avatar :src="activity.user.avatar" size="xs" />
          <span class="font-medium">{{ activity.user.name }}</span>
        </div>
        <p class="text-sm text-gray-600 mt-1">
          {{ activity.action }}
        </p>
        <p class="text-xs text-gray-400 mt-1">
          {{ activity.time }}
        </p>
      </TimelineItem>
    </Timeline>
  </Card>
</template>

<script setup>
const recentActivity = [
  {
    id: 1,
    user: { name: 'John Doe', avatar: '/avatars/john.jpg' },
    action: 'Created a new project "Website Redesign"',
    time: '5 minutes ago',
    type: 'success'
  },
  {
    id: 2,
    user: { name: 'Jane Smith', avatar: '/avatars/jane.jpg' },
    action: 'Commented on task #234',
    time: '1 hour ago',
    type: 'info'
  },
  {
    id: 3,
    user: { name: 'Bob Wilson', avatar: '/avatars/bob.jpg' },
    action: 'Completed milestone "Phase 1"',
    time: '3 hours ago',
    type: 'success'
  }
]
</script>
```

## Version History

```vue
<template>
  <Timeline :items="versions" align="left" />
</template>

<script setup>
const versions = [
  {
    id: 1,
    title: 'Version 2.0.0',
    description: 'Major release with new features and breaking changes',
    date: 'Feb 2024',
    variant: 'primary'
  },
  {
    id: 2,
    title: 'Version 1.5.0',
    description: 'Added dark mode support and performance improvements',
    date: 'Dec 2023'
  },
  {
    id: 3,
    title: 'Version 1.0.0',
    description: 'Initial stable release',
    date: 'Sep 2023',
    variant: 'success'
  }
]
</script>
```

## Playground

Try the timeline component:

<LiveDemo>
  <DemoTimeline />

  <template #code>

```vue
<Timeline :items="[
  { id: 1, title: 'Order placed', date: 'Feb 5, 2024' },
  { id: 2, title: 'Payment confirmed', date: 'Feb 5, 2024' },
  { id: 3, title: 'Shipped', date: 'Feb 6, 2024' },
  { id: 4, title: 'Delivered', date: 'Feb 8, 2024' }
]" />
```

  </template>
</LiveDemo>
