# Badge

A small label component for displaying status indicators, tags, categories, or counts. Supports multiple variants, sizes, and optional features like dots and remove buttons.

## Import

```vue
<script setup>
import Badge from '@/Components/UI/Badge.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `String` | `'primary'` | Color variant. Options: `'primary'`, `'secondary'`, `'success'`, `'danger'`, `'warning'`, `'info'` |
| `size` | `String` | `'md'` | Badge size. Options: `'sm'`, `'md'`, `'lg'` |
| `pill` | `Boolean` | `false` | Renders badge with fully rounded corners |
| `dot` | `Boolean` | `false` | Shows a colored dot indicator before the content |
| `outline` | `Boolean` | `false` | Renders badge with outline style instead of solid background |
| `removable` | `Boolean` | `false` | Shows a remove button |

## Events

| Name | Payload | Description |
|------|---------|-------------|
| `remove` | - | Emitted when the remove button is clicked |

## Slots

| Name | Description |
|------|-------------|
| `default` | Badge content/text |

## Basic Usage

```vue
<template>
    <Badge>Default</Badge>
    <Badge variant="primary">Primary</Badge>
    <Badge variant="success">Active</Badge>
</template>
```

## Variants

### Solid Variants

```vue
<template>
    <div class="flex flex-wrap gap-2">
        <Badge variant="primary">Primary</Badge>
        <Badge variant="secondary">Secondary</Badge>
        <Badge variant="success">Success</Badge>
        <Badge variant="danger">Danger</Badge>
        <Badge variant="warning">Warning</Badge>
        <Badge variant="info">Info</Badge>
    </div>
</template>
```

### Outline Variants

```vue
<template>
    <div class="flex flex-wrap gap-2">
        <Badge variant="primary" outline>Primary</Badge>
        <Badge variant="secondary" outline>Secondary</Badge>
        <Badge variant="success" outline>Success</Badge>
        <Badge variant="danger" outline>Danger</Badge>
        <Badge variant="warning" outline>Warning</Badge>
        <Badge variant="info" outline>Info</Badge>
    </div>
</template>
```

## Sizes

```vue
<template>
    <div class="flex items-center gap-2">
        <Badge size="sm">Small</Badge>
        <Badge size="md">Medium</Badge>
        <Badge size="lg">Large</Badge>
    </div>
</template>
```

Size specifications:

| Size | Padding | Font Size |
|------|---------|-----------|
| `sm` | `px-1.5 py-0.5` | `text-xs` |
| `md` | `px-2 py-0.5` | `text-xs` |
| `lg` | `px-2.5 py-1` | `text-sm` |

## Pill Shape

```vue
<template>
    <Badge pill>Rounded Badge</Badge>
    <Badge variant="success" pill>Active</Badge>
    <Badge variant="danger" pill>99+</Badge>
</template>
```

## With Dot Indicator

The dot provides a visual status indicator:

```vue
<template>
    <div class="flex flex-wrap gap-2">
        <Badge dot variant="success">Online</Badge>
        <Badge dot variant="danger">Offline</Badge>
        <Badge dot variant="warning">Away</Badge>
        <Badge dot variant="secondary">Unknown</Badge>
    </div>
</template>
```

## Removable Badge

```vue
<script setup>
const tags = ref(['Vue', 'Laravel', 'Tailwind', 'Inertia'])

const removeTag = (tag) => {
    tags.value = tags.value.filter(t => t !== tag)
}
</script>

<template>
    <div class="flex flex-wrap gap-2">
        <Badge
            v-for="tag in tags"
            :key="tag"
            removable
            @remove="removeTag(tag)"
        >
            {{ tag }}
        </Badge>
    </div>
</template>
```

## Status Indicators

```vue
<template>
    <table>
        <tbody>
            <tr>
                <td>Order #1234</td>
                <td><Badge variant="warning" dot>Pending</Badge></td>
            </tr>
            <tr>
                <td>Order #1235</td>
                <td><Badge variant="info" dot>Processing</Badge></td>
            </tr>
            <tr>
                <td>Order #1236</td>
                <td><Badge variant="success" dot>Completed</Badge></td>
            </tr>
            <tr>
                <td>Order #1237</td>
                <td><Badge variant="danger" dot>Cancelled</Badge></td>
            </tr>
        </tbody>
    </table>
</template>
```

## Category Tags

```vue
<template>
    <article>
        <h3>Article Title</h3>
        <div class="flex gap-2 mt-2">
            <Badge variant="primary" pill>Technology</Badge>
            <Badge variant="secondary" pill>Tutorial</Badge>
            <Badge variant="info" pill>Vue.js</Badge>
        </div>
    </article>
</template>
```

## Notification Counts

```vue
<script setup>
import { BellIcon, EnvelopeIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex gap-4">
        <div class="relative">
            <BellIcon class="w-6 h-6" />
            <Badge
                variant="danger"
                size="sm"
                pill
                class="absolute -top-2 -right-2"
            >
                3
            </Badge>
        </div>

        <div class="relative">
            <EnvelopeIcon class="w-6 h-6" />
            <Badge
                variant="primary"
                size="sm"
                pill
                class="absolute -top-2 -right-2"
            >
                12
            </Badge>
        </div>
    </div>
</template>
```

## User Roles

```vue
<template>
    <div class="flex items-center gap-2">
        <span>John Doe</span>
        <Badge variant="primary" size="sm">Admin</Badge>
    </div>

    <div class="flex items-center gap-2">
        <span>Jane Smith</span>
        <Badge variant="secondary" size="sm">Editor</Badge>
    </div>

    <div class="flex items-center gap-2">
        <span>Bob Wilson</span>
        <Badge variant="info" size="sm">Viewer</Badge>
    </div>
</template>
```

## Priority Levels

```vue
<template>
    <div class="space-y-2">
        <div class="flex items-center gap-2">
            <Badge variant="danger" outline>High Priority</Badge>
            <span>Fix critical security bug</span>
        </div>
        <div class="flex items-center gap-2">
            <Badge variant="warning" outline>Medium Priority</Badge>
            <span>Update documentation</span>
        </div>
        <div class="flex items-center gap-2">
            <Badge variant="success" outline>Low Priority</Badge>
            <span>Refactor helper functions</span>
        </div>
    </div>
</template>
```

## Combined Features

```vue
<template>
    <!-- Removable pill with dot -->
    <Badge
        variant="success"
        pill
        dot
        removable
        @remove="handleRemove"
    >
        Active User
    </Badge>

    <!-- Large outline removable -->
    <Badge
        variant="primary"
        size="lg"
        outline
        removable
        @remove="handleRemove"
    >
        Selected Filter
    </Badge>
</template>
```

## Filter Tags Example

```vue
<script setup>
const filters = ref([
    { id: 1, label: 'Status: Active', variant: 'success' },
    { id: 2, label: 'Category: Electronics', variant: 'primary' },
    { id: 3, label: 'Price: $100-$500', variant: 'secondary' },
])

const removeFilter = (id) => {
    filters.value = filters.value.filter(f => f.id !== id)
}

const clearAll = () => {
    filters.value = []
}
</script>

<template>
    <div class="flex flex-wrap items-center gap-2">
        <span class="text-sm text-gray-500">Active filters:</span>
        <Badge
            v-for="filter in filters"
            :key="filter.id"
            :variant="filter.variant"
            removable
            pill
            @remove="removeFilter(filter.id)"
        >
            {{ filter.label }}
        </Badge>
        <button
            v-if="filters.length > 0"
            class="text-sm text-gray-500 hover:text-gray-700"
            @click="clearAll"
        >
            Clear all
        </button>
    </div>
</template>
```

## Accessibility

- Badges use semantic `<span>` elements
- Remove buttons have proper click handling with `@click.stop`
- Color variants provide sufficient contrast
- Remove icon is visually clear and appropriately sized

## Playground

Try the badge component with different props:

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <DemoBadge variant="default">Default</DemoBadge>
    <DemoBadge variant="primary">Primary</DemoBadge>
    <DemoBadge variant="success">Success</DemoBadge>
    <DemoBadge variant="danger">Danger</DemoBadge>
    <DemoBadge variant="warning">Warning</DemoBadge>
    <DemoBadge variant="info">Info</DemoBadge>
  </div>

  <template #code>

```vue
<Badge variant="default">Default</Badge>
<Badge variant="primary">Primary</Badge>
<Badge variant="success">Success</Badge>
<Badge variant="danger">Danger</Badge>
<Badge variant="warning">Warning</Badge>
<Badge variant="info">Info</Badge>
```

  </template>
</LiveDemo>

### Outline Badges

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <DemoBadge variant="primary" outline>Primary</DemoBadge>
    <DemoBadge variant="success" outline>Success</DemoBadge>
    <DemoBadge variant="danger" outline>Danger</DemoBadge>
  </div>

  <template #code>

```vue
<Badge variant="primary" outline>Primary</Badge>
<Badge variant="success" outline>Success</Badge>
<Badge variant="danger" outline>Danger</Badge>
```

  </template>
</LiveDemo>

### Badge Sizes and Styles

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
    <DemoBadge size="sm">Small</DemoBadge>
    <DemoBadge size="md">Medium</DemoBadge>
    <DemoBadge size="lg">Large</DemoBadge>
    <DemoBadge pill>Pill</DemoBadge>
    <DemoBadge dot variant="success">Online</DemoBadge>
  </div>

  <template #code>

```vue
<Badge size="sm">Small</Badge>
<Badge size="md">Medium</Badge>
<Badge size="lg">Large</Badge>
<Badge pill>Pill</Badge>
<Badge dot variant="success">Online</Badge>
```

  </template>
</LiveDemo>

### Removable Badge

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <DemoBadge variant="primary" removable>Removable</DemoBadge>
    <DemoBadge variant="success" pill removable>Tag</DemoBadge>
  </div>

  <template #code>

```vue
<Badge variant="primary" removable @remove="handleRemove">
  Removable
</Badge>
<Badge variant="success" pill removable @remove="handleRemove">
  Tag
</Badge>
```

  </template>
</LiveDemo>
