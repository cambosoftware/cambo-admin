# Popover

A floating card that appears on click, useful for displaying additional information or controls.

## Import

```vue
<script setup>
import Popover from '@/Components/Overlays/Popover.vue'
</script>
```

## Basic Usage

```vue
<template>
  <Popover>
    <template #trigger>
      <Button>Show Info</Button>
    </template>

    <div class="p-4">
      <h3 class="font-semibold mb-2">Additional Information</h3>
      <p class="text-sm text-gray-600">
        This is some helpful information about this feature.
      </p>
    </div>
  </Popover>
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `position` | `string` | `'bottom'` | Position: `top`, `bottom`, `left`, `right` |
| `align` | `string` | `'center'` | Alignment: `start`, `center`, `end` |
| `width` | `string` | `'auto'` | Width: `auto`, `sm`, `md`, `lg`, `full` |
| `arrow` | `boolean` | `true` | Show arrow pointer |
| `closeOnClick` | `boolean` | `false` | Close when clicking inside |
| `closeOnClickOutside` | `boolean` | `true` | Close when clicking outside |

## Positions

```vue
<Popover position="top" />    <!-- Above trigger -->
<Popover position="bottom" /> <!-- Below trigger (default) -->
<Popover position="left" />   <!-- Left of trigger -->
<Popover position="right" />  <!-- Right of trigger -->
```

## Alignments

```vue
<Popover position="bottom" align="start" />  <!-- Left-aligned -->
<Popover position="bottom" align="center" /> <!-- Centered (default) -->
<Popover position="bottom" align="end" />    <!-- Right-aligned -->
```

## Slots

| Slot | Description |
|------|-------------|
| `trigger` | Element that triggers the popover |
| `default` | Popover content |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `open` | - | Popover opened |
| `close` | - | Popover closed |

## Examples

### Info Popover

```vue
<Popover position="right" width="md">
  <template #trigger>
    <button class="text-gray-400 hover:text-gray-600">
      <InformationCircleIcon class="w-5 h-5" />
    </button>
  </template>

  <div class="p-4">
    <h4 class="font-medium mb-2">What is this?</h4>
    <p class="text-sm text-gray-600">
      This field determines how your product appears in search results.
      A good description can improve your visibility.
    </p>
    <a href="/docs" class="text-sm text-indigo-600 hover:underline mt-2 block">
      Learn more
    </a>
  </div>
</Popover>
```

### User Card Popover

```vue
<Popover position="bottom" align="start" width="sm">
  <template #trigger>
    <button class="flex items-center gap-2">
      <Avatar :src="user.avatar" size="sm" />
      <span class="text-sm font-medium">{{ user.name }}</span>
    </button>
  </template>

  <div class="p-4">
    <div class="flex items-center gap-3 mb-3">
      <Avatar :src="user.avatar" size="lg" />
      <div>
        <p class="font-semibold">{{ user.name }}</p>
        <p class="text-sm text-gray-500">{{ user.role }}</p>
      </div>
    </div>
    <div class="space-y-1 text-sm">
      <p><strong>Email:</strong> {{ user.email }}</p>
      <p><strong>Joined:</strong> {{ user.joinedAt }}</p>
    </div>
    <div class="mt-3 pt-3 border-t flex gap-2">
      <Button size="sm" variant="secondary" class="flex-1">Message</Button>
      <Button size="sm" variant="primary" class="flex-1">View Profile</Button>
    </div>
  </div>
</Popover>
```

### Color Picker Popover

```vue
<Popover :close-on-click="false">
  <template #trigger>
    <button
      class="w-8 h-8 rounded border"
      :style="{ backgroundColor: selectedColor }"
    />
  </template>

  <div class="p-3">
    <div class="grid grid-cols-6 gap-2">
      <button
        v-for="color in colors"
        :key="color"
        class="w-6 h-6 rounded"
        :style="{ backgroundColor: color }"
        @click="selectedColor = color"
      />
    </div>
  </div>
</Popover>
```

### Date Quick Select

```vue
<Popover width="md">
  <template #trigger>
    <Button variant="secondary">
      <CalendarIcon class="w-4 h-4 mr-2" />
      Select Date
    </Button>
  </template>

  <div class="p-2">
    <button class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded">
      Today
    </button>
    <button class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded">
      Yesterday
    </button>
    <button class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded">
      Last 7 days
    </button>
    <button class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded">
      Last 30 days
    </button>
    <button class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded">
      This month
    </button>
    <hr class="my-2" />
    <button class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded">
      Custom range...
    </button>
  </div>
</Popover>
```

## Accessibility

- Proper ARIA attributes
- Focus management
- Escape key to close
- Click outside to close

## Playground

Try the Popover component:

<LiveDemo>
  <div style="display: flex; align-items: flex-start; gap: 32px;">
    <div style="position: relative;">
      <button style="padding: 8px 16px; border: 1px solid #d1d5db; border-radius: 6px; background: white; cursor: pointer; font-size: 14px;">Show Info</button>
      <div style="position: absolute; top: 100%; left: 50%; transform: translateX(-50%); margin-top: 8px; background: white; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; padding: 16px; width: 250px;">
        <div style="position: absolute; top: -6px; left: 50%; transform: translateX(-50%) rotate(45deg); width: 12px; height: 12px; background: white; border-left: 1px solid #e5e7eb; border-top: 1px solid #e5e7eb;"></div>
        <h4 style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #111827;">What is this?</h4>
        <p style="margin: 0; font-size: 13px; color: #6b7280;">This is some helpful information about this feature.</p>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <Popover position="bottom" width="md">
    <template #trigger>
      <Button>Show Info</Button>
    </template>

    <div class="p-4">
      <h4 class="font-medium mb-2">What is this?</h4>
      <p class="text-sm text-gray-600">
        This is some helpful information about this feature.
      </p>
    </div>
  </Popover>
</template>
```

  </template>
</LiveDemo>
