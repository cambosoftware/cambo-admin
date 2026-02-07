# RelativeTime

Displays timestamps in human-readable relative format (e.g., "2 hours ago").

## Import

```vue
<script setup>
import RelativeTime from '@/Components/Utilities/RelativeTime.vue'
</script>
```

## Basic Usage

```vue
<template>
  <RelativeTime :date="post.createdAt" />
</template>
```

Output: "2 hours ago"

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `date` | `Date\|string\|number` | required | The date to display |
| `updateInterval` | `number` | `60000` | Auto-update interval (ms) |
| `showTooltip` | `boolean` | `true` | Show full date on hover |
| `format` | `string` | `'long'` | Format: `long`, `short`, `narrow` |

## Formats

### Long Format (Default)

```vue
<RelativeTime :date="date" format="long" />
```
- "2 hours ago"
- "3 days ago"
- "in 5 minutes"

### Short Format

```vue
<RelativeTime :date="date" format="short" />
```
- "2 hr ago"
- "3 days ago"
- "in 5 min"

### Narrow Format

```vue
<RelativeTime :date="date" format="narrow" />
```
- "2h ago"
- "3d ago"
- "in 5m"

## Examples

### In a List

```vue
<template>
  <ul>
    <li v-for="activity in activities" :key="activity.id">
      {{ activity.user }} {{ activity.action }}
      <RelativeTime :date="activity.timestamp" class="text-gray-500" />
    </li>
  </ul>
</template>
```

### With Tooltip

```vue
<RelativeTime
  :date="createdAt"
  show-tooltip
/>
```
Hovering shows: "February 6, 2026 at 3:45 PM"

### Without Auto-Update

```vue
<RelativeTime
  :date="date"
  :update-interval="0"
/>
```

### Different Date Inputs

```vue
<!-- Date object -->
<RelativeTime :date="new Date()" />

<!-- ISO string -->
<RelativeTime date="2026-02-06T10:30:00Z" />

<!-- Timestamp -->
<RelativeTime :date="1707216600000" />
```

## Real-World Example

```vue
<template>
  <Card>
    <template #header>
      <h3>Recent Activity</h3>
    </template>

    <div class="space-y-4">
      <div
        v-for="item in activities"
        :key="item.id"
        class="flex items-start gap-3"
      >
        <Avatar :src="item.user.avatar" size="sm" />
        <div class="flex-1">
          <p>
            <span class="font-medium">{{ item.user.name }}</span>
            {{ item.description }}
          </p>
          <RelativeTime
            :date="item.createdAt"
            class="text-sm text-gray-500"
          />
        </div>
      </div>
    </div>
  </Card>
</template>
```

## Localization

The component uses the browser's `Intl.RelativeTimeFormat` API and automatically respects the user's locale settings.

## Playground

Try the RelativeTime component:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 8px;">
    <div style="display: flex; align-items: center; gap: 8px;">
      <span style="font-weight: 500;">John Doe</span>
      <span>commented</span>
      <span style="color: #6b7280;" title="February 7, 2026 at 2:30 PM">2 hours ago</span>
    </div>
    <div style="display: flex; align-items: center; gap: 8px;">
      <span style="font-weight: 500;">Jane Smith</span>
      <span>updated the document</span>
      <span style="color: #6b7280;" title="February 6, 2026 at 10:15 AM">1 day ago</span>
    </div>
    <div style="display: flex; align-items: center; gap: 8px;">
      <span style="font-weight: 500;">Bob Johnson</span>
      <span>created the project</span>
      <span style="color: #6b7280;" title="February 1, 2026 at 9:00 AM">6 days ago</span>
    </div>
  </div>

  <template #code>

```vue
<template>
  <div class="space-y-2">
    <div v-for="activity in activities" :key="activity.id">
      <span class="font-medium">{{ activity.user }}</span>
      {{ activity.action }}
      <RelativeTime :date="activity.timestamp" class="text-gray-500" />
    </div>
  </div>
</template>

<script setup>
import RelativeTime from '@/Components/Utilities/RelativeTime.vue'

const activities = [
  { id: 1, user: 'John Doe', action: 'commented', timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000) },
  { id: 2, user: 'Jane Smith', action: 'updated the document', timestamp: new Date(Date.now() - 24 * 60 * 60 * 1000) },
  { id: 3, user: 'Bob Johnson', action: 'created the project', timestamp: new Date(Date.now() - 6 * 24 * 60 * 60 * 1000) }
]
</script>
```

  </template>
</LiveDemo>
