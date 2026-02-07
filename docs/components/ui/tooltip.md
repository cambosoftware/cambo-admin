# Tooltip

A contextual popup that displays additional information when hovering over an element. Supports multiple positions and customizable delay.

## Import

```vue
<script setup>
import Tooltip from '@/Components/UI/Tooltip.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `text` | `String` | `''` | The tooltip content text |
| `position` | `String` | `'top'` | Tooltip position. Options: `'top'`, `'bottom'`, `'left'`, `'right'` |
| `delay` | `Number` | `200` | Delay in milliseconds before showing the tooltip |

## Slots

| Name | Description |
|------|-------------|
| `default` | The trigger element that activates the tooltip on hover |

## Basic Usage

```vue
<template>
    <Tooltip text="This is a tooltip">
        <button>Hover me</button>
    </Tooltip>
</template>
```

## Positions

```vue
<template>
    <div class="flex gap-8 items-center justify-center py-12">
        <Tooltip text="Tooltip on top" position="top">
            <button class="px-4 py-2 bg-gray-200 rounded">Top</button>
        </Tooltip>

        <Tooltip text="Tooltip on bottom" position="bottom">
            <button class="px-4 py-2 bg-gray-200 rounded">Bottom</button>
        </Tooltip>

        <Tooltip text="Tooltip on left" position="left">
            <button class="px-4 py-2 bg-gray-200 rounded">Left</button>
        </Tooltip>

        <Tooltip text="Tooltip on right" position="right">
            <button class="px-4 py-2 bg-gray-200 rounded">Right</button>
        </Tooltip>
    </div>
</template>
```

## Custom Delay

```vue
<template>
    <!-- No delay -->
    <Tooltip text="Instant tooltip" :delay="0">
        <button>Instant</button>
    </Tooltip>

    <!-- Short delay -->
    <Tooltip text="Quick tooltip" :delay="100">
        <button>Quick</button>
    </Tooltip>

    <!-- Default delay (200ms) -->
    <Tooltip text="Default delay">
        <button>Default</button>
    </Tooltip>

    <!-- Long delay -->
    <Tooltip text="Slow tooltip" :delay="500">
        <button>Slow</button>
    </Tooltip>
</template>
```

## With Buttons

```vue
<script setup>
import Button from '@/Components/UI/Button.vue'
import { TrashIcon, PencilIcon, DocumentDuplicateIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex gap-2">
        <Tooltip text="Edit item">
            <Button variant="secondary" :icon="PencilIcon" />
        </Tooltip>

        <Tooltip text="Duplicate item">
            <Button variant="secondary" :icon="DocumentDuplicateIcon" />
        </Tooltip>

        <Tooltip text="Delete item" position="bottom">
            <Button variant="danger" :icon="TrashIcon" />
        </Tooltip>
    </div>
</template>
```

## With Icon Buttons

```vue
<script setup>
import IconButton from '@/Components/UI/IconButton.vue'
import { CogIcon, BellIcon, UserIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex gap-1">
        <Tooltip text="Settings">
            <IconButton :icon="CogIcon" />
        </Tooltip>

        <Tooltip text="Notifications">
            <IconButton :icon="BellIcon" />
        </Tooltip>

        <Tooltip text="Profile">
            <IconButton :icon="UserIcon" />
        </Tooltip>
    </div>
</template>
```

## With Icons

```vue
<script setup>
import { InformationCircleIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex items-center gap-2">
        <span>API Key</span>
        <Tooltip text="Your unique API key for authentication. Keep it secure!">
            <InformationCircleIcon class="w-4 h-4 text-gray-400 cursor-help" />
        </Tooltip>
    </div>
</template>
```

## Form Field Help

```vue
<template>
    <div class="space-y-1">
        <label class="flex items-center gap-1 text-sm font-medium">
            Password
            <Tooltip text="Must be at least 8 characters with one uppercase and one number">
                <span class="text-gray-400 cursor-help">(?)</span>
            </Tooltip>
        </label>
        <input type="password" class="w-full px-3 py-2 border rounded-lg" />
    </div>
</template>
```

## Disabled Button Explanation

```vue
<script setup>
const canSubmit = ref(false)
</script>

<template>
    <Tooltip
        :text="canSubmit ? '' : 'Please fill all required fields'"
        position="top"
    >
        <span>
            <Button :disabled="!canSubmit">
                Submit
            </Button>
        </span>
    </Tooltip>
</template>
```

Note: When wrapping a disabled button, wrap it in a `<span>` to ensure hover events work.

## Truncated Text

```vue
<template>
    <Tooltip :text="fullTitle" position="top">
        <span class="block truncate max-w-[200px]">
            {{ fullTitle }}
        </span>
    </Tooltip>
</template>
```

## Table Cell Actions

```vue
<template>
    <table class="w-full">
        <tbody>
            <tr v-for="item in items" :key="item.id">
                <td>{{ item.name }}</td>
                <td class="text-right">
                    <div class="flex justify-end gap-1">
                        <Tooltip text="View details">
                            <IconButton :icon="EyeIcon" size="sm" />
                        </Tooltip>
                        <Tooltip text="Edit">
                            <IconButton :icon="PencilIcon" size="sm" />
                        </Tooltip>
                        <Tooltip text="Delete">
                            <IconButton :icon="TrashIcon" size="sm" variant="danger" />
                        </Tooltip>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
```

## Status Explanation

```vue
<script setup>
import Badge from '@/Components/UI/Badge.vue'
</script>

<template>
    <Tooltip text="Order is being prepared for shipment">
        <Badge variant="warning" dot>Processing</Badge>
    </Tooltip>
</template>
```

## Toolbar with Tooltips

```vue
<script setup>
import {
    BoldIcon,
    ItalicIcon,
    UnderlineIcon,
    ListBulletIcon,
    PhotoIcon
} from '@heroicons/vue/24/outline'

const tools = [
    { icon: BoldIcon, label: 'Bold (Ctrl+B)' },
    { icon: ItalicIcon, label: 'Italic (Ctrl+I)' },
    { icon: UnderlineIcon, label: 'Underline (Ctrl+U)' },
    { icon: ListBulletIcon, label: 'Bullet list' },
    { icon: PhotoIcon, label: 'Insert image' },
]
</script>

<template>
    <div class="flex gap-1 p-2 bg-gray-100 rounded-lg">
        <Tooltip v-for="tool in tools" :key="tool.label" :text="tool.label">
            <button class="p-2 hover:bg-gray-200 rounded">
                <component :is="tool.icon" class="w-5 h-5" />
            </button>
        </Tooltip>
    </div>
</template>
```

## Avatar with Tooltip

```vue
<script setup>
import Avatar from '@/Components/UI/Avatar.vue'
</script>

<template>
    <div class="flex -space-x-2">
        <Tooltip v-for="user in users" :key="user.id" :text="user.name">
            <Avatar
                :src="user.avatar"
                :name="user.name"
                size="sm"
                class="ring-2 ring-white"
            />
        </Tooltip>
    </div>
</template>
```

## Keyboard Shortcut Hints

```vue
<template>
    <div class="flex gap-4">
        <Tooltip text="Save (Ctrl+S)" position="bottom">
            <Button variant="primary">Save</Button>
        </Tooltip>

        <Tooltip text="Cancel (Esc)" position="bottom">
            <Button variant="secondary">Cancel</Button>
        </Tooltip>
    </div>
</template>
```

## Conditional Tooltip

```vue
<script setup>
const showTooltip = computed(() => {
    return text.value.length > 50
})
</script>

<template>
    <Tooltip :text="showTooltip ? text : ''" position="top">
        <span :class="{ 'truncate max-w-[200px]': showTooltip }">
            {{ text }}
        </span>
    </Tooltip>
</template>
```

Note: When `text` is empty, the tooltip will not be displayed.

## Styling Details

- Background: `bg-gray-900` (dark tooltip)
- Text: `text-white text-xs font-medium`
- Padding: `px-2.5 py-1.5`
- Border radius: `rounded-md`
- Shadow: `shadow-lg`
- Arrow: CSS borders creating a triangle pointing toward the trigger

## Animation

The tooltip uses Vue's `<Transition>` component with:
- Enter: 150ms ease-out, scale from 95% to 100%
- Leave: 100ms ease-in, scale from 100% to 95%
- Opacity transitions for smooth fade effect

## Accessibility

- Tooltip has `role="tooltip"` for screen readers
- Content is only shown on hover (mouse interaction)
- Consider providing alternative text for keyboard users
- The delay helps prevent accidental tooltip display
- Pointer events are disabled on the tooltip itself

## Playground

Try the tooltip component with different props:

<LiveDemo>
  <div style="display: flex; gap: 1rem; justify-content: center; padding: 2rem;">
    <DemoTooltip text="Tooltip on top" position="top">
      <DemoButton variant="secondary">Top</DemoButton>
    </DemoTooltip>
    <DemoTooltip text="Tooltip on bottom" position="bottom">
      <DemoButton variant="secondary">Bottom</DemoButton>
    </DemoTooltip>
    <DemoTooltip text="Tooltip on left" position="left">
      <DemoButton variant="secondary">Left</DemoButton>
    </DemoTooltip>
    <DemoTooltip text="Tooltip on right" position="right">
      <DemoButton variant="secondary">Right</DemoButton>
    </DemoTooltip>
  </div>

  <template #code>

```vue
<Tooltip text="Tooltip on top" position="top">
  <Button>Top</Button>
</Tooltip>
<Tooltip text="Tooltip on bottom" position="bottom">
  <Button>Bottom</Button>
</Tooltip>
<Tooltip text="Tooltip on left" position="left">
  <Button>Left</Button>
</Tooltip>
<Tooltip text="Tooltip on right" position="right">
  <Button>Right</Button>
</Tooltip>
```

  </template>
</LiveDemo>
