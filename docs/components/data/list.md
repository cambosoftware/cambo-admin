# List

A component for displaying items in a vertical list format with optional dividers, borders, and hover effects.

## Import

```js
import { List, ListItem } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `divided` | `Boolean` | `true` | Add divider lines between items |
| `hoverable` | `Boolean` | `false` | Apply hover effect on items |
| `bordered` | `Boolean` | `false` | Add border and rounded corners |
| `compact` | `Boolean` | `false` | Use compact padding |

## Slots

| Slot | Description |
|------|-------------|
| `default` | List content (typically ListItem components) |

## Basic Example

```vue
<template>
  <List>
    <ListItem v-for="item in items" :key="item.id">
      {{ item.name }}
    </ListItem>
  </List>
</template>

<script setup>
import { List, ListItem } from '@cambosoftware/cambo-admin'

const items = [
  { id: 1, name: 'First item' },
  { id: 2, name: 'Second item' },
  { id: 3, name: 'Third item' },
]
</script>
```

## Bordered List

```vue
<template>
  <List bordered divided>
    <ListItem>Item 1</ListItem>
    <ListItem>Item 2</ListItem>
    <ListItem>Item 3</ListItem>
  </List>
</template>
```

## Hoverable List

```vue
<template>
  <List bordered hoverable>
    <ListItem
      v-for="user in users"
      :key="user.id"
      @click="selectUser(user)"
      class="cursor-pointer"
    >
      <div class="flex items-center gap-3">
        <Avatar :src="user.avatar" :name="user.name" size="sm" />
        <div>
          <p class="font-medium text-gray-900">{{ user.name }}</p>
          <p class="text-sm text-gray-500">{{ user.email }}</p>
        </div>
      </div>
    </ListItem>
  </List>
</template>
```

## Compact List

```vue
<template>
  <List compact divided bordered>
    <ListItem v-for="item in items" :key="item.id">
      {{ item.name }}
    </ListItem>
  </List>
</template>
```

## List with Actions

```vue
<template>
  <List bordered divided>
    <ListItem
      v-for="notification in notifications"
      :key="notification.id"
      class="flex items-center justify-between"
    >
      <div class="flex items-center gap-3">
        <div :class="[
          'w-2 h-2 rounded-full',
          notification.read ? 'bg-gray-300' : 'bg-primary-500'
        ]" />
        <div>
          <p class="text-sm font-medium text-gray-900">{{ notification.title }}</p>
          <p class="text-xs text-gray-500">{{ notification.time }}</p>
        </div>
      </div>
      <Button size="xs" variant="ghost" @click="markAsRead(notification)">
        Mark as read
      </Button>
    </ListItem>
  </List>
</template>
```

## List with Icons

```vue
<template>
  <List divided>
    <ListItem class="flex items-center gap-3">
      <HomeIcon class="h-5 w-5 text-gray-400" />
      <span>Dashboard</span>
    </ListItem>
    <ListItem class="flex items-center gap-3">
      <UsersIcon class="h-5 w-5 text-gray-400" />
      <span>Team</span>
    </ListItem>
    <ListItem class="flex items-center gap-3">
      <CogIcon class="h-5 w-5 text-gray-400" />
      <span>Settings</span>
    </ListItem>
  </List>
</template>
```

## Nested List

```vue
<template>
  <List bordered>
    <ListItem>
      <div class="flex items-center justify-between">
        <span class="font-medium">Category A</span>
        <ChevronDownIcon class="h-5 w-5 text-gray-400" />
      </div>
      <List class="mt-2 ml-4" :divided="false">
        <ListItem class="text-sm text-gray-600">Sub-item 1</ListItem>
        <ListItem class="text-sm text-gray-600">Sub-item 2</ListItem>
      </List>
    </ListItem>
    <ListItem>
      <div class="flex items-center justify-between">
        <span class="font-medium">Category B</span>
        <ChevronDownIcon class="h-5 w-5 text-gray-400" />
      </div>
    </ListItem>
  </List>
</template>
```

## List without Dividers

```vue
<template>
  <List :divided="false" bordered>
    <ListItem class="py-1">Item 1</ListItem>
    <ListItem class="py-1">Item 2</ListItem>
    <ListItem class="py-1">Item 3</ListItem>
  </List>
</template>
```

## Selectable List

```vue
<template>
  <List bordered hoverable>
    <ListItem
      v-for="item in items"
      :key="item.id"
      :class="[
        'cursor-pointer transition-colors',
        selectedId === item.id ? 'bg-primary-50' : ''
      ]"
      @click="selectedId = item.id"
    >
      <div class="flex items-center gap-3">
        <input
          type="radio"
          :checked="selectedId === item.id"
          class="h-4 w-4 text-primary-600"
        />
        <span>{{ item.name }}</span>
      </div>
    </ListItem>
  </List>
</template>

<script setup>
import { ref } from 'vue'

const selectedId = ref(null)
const items = [
  { id: 1, name: 'Option 1' },
  { id: 2, name: 'Option 2' },
  { id: 3, name: 'Option 3' },
]
</script>
```

## Playground

Try the List component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
    <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
      <div style="width: 32px; height: 32px; background: #6366f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px;">JD</div>
      <div>
        <div style="font-weight: 500; font-size: 14px;">John Doe</div>
        <div style="font-size: 12px; color: #64748b;">john@example.com</div>
      </div>
    </div>
    <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px; background: #f8fafc;">
      <div style="width: 32px; height: 32px; background: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px;">JS</div>
      <div>
        <div style="font-weight: 500; font-size: 14px;">Jane Smith</div>
        <div style="font-size: 12px; color: #64748b;">jane@example.com</div>
      </div>
    </div>
    <div style="padding: 12px 16px; display: flex; align-items: center; gap: 12px;">
      <div style="width: 32px; height: 32px; background: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px;">BW</div>
      <div>
        <div style="font-weight: 500; font-size: 14px;">Bob Wilson</div>
        <div style="font-size: 12px; color: #64748b;">bob@example.com</div>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <List bordered divided hoverable>
    <ListItem
      v-for="user in users"
      :key="user.id"
      class="cursor-pointer"
    >
      <div class="flex items-center gap-3">
        <Avatar :name="user.name" size="sm" />
        <div>
          <p class="font-medium">{{ user.name }}</p>
          <p class="text-sm text-gray-500">{{ user.email }}</p>
        </div>
      </div>
    </ListItem>
  </List>
</template>

<script setup>
import { List, ListItem, Avatar } from '@cambosoftware/cambo-admin'

const users = [
  { id: 1, name: 'John Doe', email: 'john@example.com' },
  { id: 2, name: 'Jane Smith', email: 'jane@example.com' },
  { id: 3, name: 'Bob Wilson', email: 'bob@example.com' }
]
</script>
```

  </template>
</LiveDemo>
