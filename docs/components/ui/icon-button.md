# IconButton

A compact button component designed for icon-only actions. Ideal for toolbars, action menus, and space-constrained interfaces.

## Import

```vue
<script setup>
import IconButton from '@/Components/UI/IconButton.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `icon` | `Object \| Function` | **required** | Icon component to display |
| `variant` | `String` | `'ghost'` | Visual style variant. Options: `'primary'`, `'secondary'`, `'success'`, `'danger'`, `'warning'`, `'ghost'` |
| `size` | `String` | `'md'` | Button size. Options: `'xs'`, `'sm'`, `'md'`, `'lg'` |
| `pill` | `Boolean` | `false` | Renders button with fully rounded corners |
| `loading` | `Boolean` | `false` | Shows loading spinner instead of icon |
| `disabled` | `Boolean` | `false` | Disables the button |
| `href` | `String` | `null` | When provided, renders as an Inertia Link |
| `label` | `String` | `null` | Accessible label for the button (used for aria-label and title) |
| `type` | `String` | `'button'` | HTML button type attribute |

## Events

| Name | Payload | Description |
|------|---------|-------------|
| `click` | `MouseEvent` | Emitted when the button is clicked |

## Basic Usage

```vue
<script setup>
import { PencilIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <IconButton :icon="PencilIcon" label="Edit" />
</template>
```

## Variants

```vue
<script setup>
import {
    HeartIcon,
    StarIcon,
    TrashIcon,
    BellIcon,
    CogIcon,
    PlusIcon
} from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex gap-2">
        <IconButton :icon="HeartIcon" variant="primary" label="Like" />
        <IconButton :icon="StarIcon" variant="secondary" label="Favorite" />
        <IconButton :icon="PlusIcon" variant="success" label="Add" />
        <IconButton :icon="TrashIcon" variant="danger" label="Delete" />
        <IconButton :icon="BellIcon" variant="warning" label="Notifications" />
        <IconButton :icon="CogIcon" variant="ghost" label="Settings" />
    </div>
</template>
```

## Sizes

```vue
<script setup>
import { PlusIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex items-center gap-2">
        <IconButton :icon="PlusIcon" size="xs" label="Add" />
        <IconButton :icon="PlusIcon" size="sm" label="Add" />
        <IconButton :icon="PlusIcon" size="md" label="Add" />
        <IconButton :icon="PlusIcon" size="lg" label="Add" />
    </div>
</template>
```

Size dimensions:

| Size | Padding | Icon Size |
|------|---------|-----------|
| `xs` | `p-1` | 14px (3.5) |
| `sm` | `p-1.5` | 16px (4) |
| `md` | `p-2` | 20px (5) |
| `lg` | `p-3` | 24px (6) |

## Pill Shape

```vue
<script setup>
import { XMarkIcon, CheckIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <IconButton :icon="CheckIcon" variant="success" pill label="Approve" />
    <IconButton :icon="XMarkIcon" variant="danger" pill label="Reject" />
</template>
```

## Loading State

```vue
<script setup>
import { ArrowPathIcon } from '@heroicons/vue/24/outline'
const isRefreshing = ref(false)

const refresh = async () => {
    isRefreshing.value = true
    await fetchData()
    isRefreshing.value = false
}
</script>

<template>
    <IconButton
        :icon="ArrowPathIcon"
        :loading="isRefreshing"
        label="Refresh"
        @click="refresh"
    />
</template>
```

## Disabled State

```vue
<script setup>
import { TrashIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <IconButton
        :icon="TrashIcon"
        variant="danger"
        disabled
        label="Delete (disabled)"
    />
</template>
```

## As Link

```vue
<script setup>
import { HomeIcon, UserIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <IconButton :icon="HomeIcon" href="/dashboard" label="Go to Dashboard" />
    <IconButton :icon="UserIcon" href="/profile" label="View Profile" />
</template>
```

## Toolbar Example

```vue
<script setup>
import {
    BoldIcon,
    ItalicIcon,
    UnderlineIcon,
    StrikethroughIcon,
    CodeBracketIcon
} from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex items-center gap-1 p-2 bg-gray-100 rounded-lg">
        <IconButton :icon="BoldIcon" label="Bold" />
        <IconButton :icon="ItalicIcon" label="Italic" />
        <IconButton :icon="UnderlineIcon" label="Underline" />
        <IconButton :icon="StrikethroughIcon" label="Strikethrough" />
        <div class="w-px h-6 bg-gray-300 mx-1" />
        <IconButton :icon="CodeBracketIcon" label="Code" />
    </div>
</template>
```

## Action Menu Example

```vue
<script setup>
import {
    PencilIcon,
    DocumentDuplicateIcon,
    TrashIcon,
    EllipsisVerticalIcon
} from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex gap-1">
        <IconButton :icon="PencilIcon" variant="secondary" label="Edit" />
        <IconButton :icon="DocumentDuplicateIcon" variant="secondary" label="Duplicate" />
        <IconButton :icon="TrashIcon" variant="danger" label="Delete" />
        <IconButton :icon="EllipsisVerticalIcon" variant="ghost" label="More options" />
    </div>
</template>
```

## Card Actions Example

```vue
<script setup>
import { HeartIcon, ChatBubbleLeftIcon, ShareIcon, BookmarkIcon } from '@heroicons/vue/24/outline'
import { HeartIcon as HeartSolidIcon } from '@heroicons/vue/24/solid'

const liked = ref(false)
const bookmarked = ref(false)
</script>

<template>
    <div class="flex items-center justify-between p-4 border-t">
        <div class="flex gap-2">
            <IconButton
                :icon="liked ? HeartSolidIcon : HeartIcon"
                :variant="liked ? 'danger' : 'ghost'"
                label="Like"
                @click="liked = !liked"
            />
            <IconButton :icon="ChatBubbleLeftIcon" label="Comment" />
            <IconButton :icon="ShareIcon" label="Share" />
        </div>
        <IconButton
            :icon="BookmarkIcon"
            :variant="bookmarked ? 'primary' : 'ghost'"
            label="Bookmark"
            @click="bookmarked = !bookmarked"
        />
    </div>
</template>
```

## Table Row Actions

```vue
<script setup>
import { EyeIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <table>
        <tbody>
            <tr v-for="item in items" :key="item.id">
                <td>{{ item.name }}</td>
                <td class="text-right">
                    <div class="flex justify-end gap-1">
                        <IconButton
                            :icon="EyeIcon"
                            size="sm"
                            :href="`/items/${item.id}`"
                            label="View"
                        />
                        <IconButton
                            :icon="PencilIcon"
                            size="sm"
                            variant="primary"
                            :href="`/items/${item.id}/edit`"
                            label="Edit"
                        />
                        <IconButton
                            :icon="TrashIcon"
                            size="sm"
                            variant="danger"
                            label="Delete"
                            @click="deleteItem(item.id)"
                        />
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
```

## Accessibility

- Always provide a `label` prop for screen readers
- The `label` is used for both `aria-label` and `title` attributes
- Loading state displays an accessible spinner with animation
- Focus indicators are clearly visible with ring styles
- Disabled state prevents all interaction

## Playground

Try the icon button component with different props:

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; align-items: center;">
    <DemoButton variant="primary" size="sm" style="padding: 0.5rem; width: 2rem; height: 2rem;">+</DemoButton>
    <DemoButton variant="secondary" size="sm" style="padding: 0.5rem; width: 2rem; height: 2rem;">-</DemoButton>
    <DemoButton variant="success" size="sm" style="padding: 0.5rem; width: 2rem; height: 2rem;">✓</DemoButton>
    <DemoButton variant="danger" size="sm" style="padding: 0.5rem; width: 2rem; height: 2rem;">✕</DemoButton>
    <DemoButton variant="ghost" size="sm" style="padding: 0.5rem; width: 2rem; height: 2rem;">⚙</DemoButton>
  </div>

  <template #code>

```vue
<IconButton :icon="PlusIcon" variant="primary" label="Add" />
<IconButton :icon="MinusIcon" variant="secondary" label="Remove" />
<IconButton :icon="CheckIcon" variant="success" label="Approve" />
<IconButton :icon="XMarkIcon" variant="danger" label="Delete" />
<IconButton :icon="CogIcon" variant="ghost" label="Settings" />
```

  </template>
</LiveDemo>

### Icon Button Sizes

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; align-items: center;">
    <DemoButton variant="secondary" style="padding: 0.25rem; width: 1.5rem; height: 1.5rem; font-size: 0.75rem;">+</DemoButton>
    <DemoButton variant="secondary" style="padding: 0.375rem; width: 1.75rem; height: 1.75rem; font-size: 0.875rem;">+</DemoButton>
    <DemoButton variant="secondary" style="padding: 0.5rem; width: 2rem; height: 2rem;">+</DemoButton>
    <DemoButton variant="secondary" style="padding: 0.75rem; width: 2.5rem; height: 2.5rem; font-size: 1.25rem;">+</DemoButton>
  </div>

  <template #code>

```vue
<IconButton :icon="PlusIcon" size="xs" label="Add" />
<IconButton :icon="PlusIcon" size="sm" label="Add" />
<IconButton :icon="PlusIcon" size="md" label="Add" />
<IconButton :icon="PlusIcon" size="lg" label="Add" />
```

  </template>
</LiveDemo>
