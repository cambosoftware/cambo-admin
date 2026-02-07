# Icon

A wrapper component for rendering icon components with consistent sizing, colors, and optional stroke width customization. Works seamlessly with Heroicons and other Vue icon libraries.

## Import

```vue
<script setup>
import Icon from '@/Components/UI/Icon.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `icon` | `Object \| Function` | **required** | The icon component to render |
| `size` | `String` | `'md'` | Icon size. Options: `'xs'`, `'sm'`, `'md'`, `'lg'`, `'xl'`, `'2xl'` |
| `color` | `String` | `'current'` | Icon color. Options: `'current'`, `'primary'`, `'secondary'`, `'success'`, `'danger'`, `'warning'`, `'info'`, `'white'`, `'black'`, `'muted'`, or any Tailwind text color class |
| `stroke` | `Number` | `null` | Custom stroke width for outline icons |

## Basic Usage

```vue
<script setup>
import { UserIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <Icon :icon="UserIcon" />
</template>
```

## Sizes

```vue
<script setup>
import { StarIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex items-center gap-4">
        <Icon :icon="StarIcon" size="xs" />   <!-- 12px -->
        <Icon :icon="StarIcon" size="sm" />   <!-- 16px -->
        <Icon :icon="StarIcon" size="md" />   <!-- 20px -->
        <Icon :icon="StarIcon" size="lg" />   <!-- 24px -->
        <Icon :icon="StarIcon" size="xl" />   <!-- 32px -->
        <Icon :icon="StarIcon" size="2xl" />  <!-- 40px -->
    </div>
</template>
```

Size specifications:

| Size | Dimensions |
|------|------------|
| `xs` | 12px (w-3 h-3) |
| `sm` | 16px (w-4 h-4) |
| `md` | 20px (w-5 h-5) |
| `lg` | 24px (w-6 h-6) |
| `xl` | 32px (w-8 h-8) |
| `2xl` | 40px (w-10 h-10) |

## Colors

### Preset Colors

```vue
<script setup>
import { HeartIcon } from '@heroicons/vue/24/solid'
</script>

<template>
    <div class="flex items-center gap-4">
        <Icon :icon="HeartIcon" color="current" />   <!-- Inherits parent color -->
        <Icon :icon="HeartIcon" color="primary" />   <!-- Indigo -->
        <Icon :icon="HeartIcon" color="secondary" /> <!-- Gray -->
        <Icon :icon="HeartIcon" color="success" />   <!-- Green -->
        <Icon :icon="HeartIcon" color="danger" />    <!-- Red -->
        <Icon :icon="HeartIcon" color="warning" />   <!-- Amber -->
        <Icon :icon="HeartIcon" color="info" />      <!-- Sky blue -->
        <Icon :icon="HeartIcon" color="muted" />     <!-- Light gray -->
    </div>
</template>
```

Color specifications:

| Color | Class |
|-------|-------|
| `current` | `text-current` |
| `primary` | `text-indigo-600` |
| `secondary` | `text-gray-500` |
| `success` | `text-emerald-600` |
| `danger` | `text-red-600` |
| `warning` | `text-amber-600` |
| `info` | `text-sky-600` |
| `white` | `text-white` |
| `black` | `text-gray-900` |
| `muted` | `text-gray-400` |

### Custom Tailwind Colors

You can also pass any Tailwind text color class:

```vue
<template>
    <Icon :icon="StarIcon" color="text-pink-500" />
    <Icon :icon="StarIcon" color="text-teal-600" />
    <Icon :icon="StarIcon" color="text-purple-400" />
</template>
```

## Custom Stroke Width

For outline icons, you can customize the stroke width:

```vue
<script setup>
import { HeartIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex items-center gap-4">
        <Icon :icon="HeartIcon" :stroke="1" />
        <Icon :icon="HeartIcon" :stroke="1.5" />
        <Icon :icon="HeartIcon" :stroke="2" />
        <Icon :icon="HeartIcon" :stroke="2.5" />
    </div>
</template>
```

## With Heroicons

```vue
<script setup>
import {
    HomeIcon,
    UserIcon,
    CogIcon,
    BellIcon,
    EnvelopeIcon
} from '@heroicons/vue/24/outline'

import {
    HeartIcon as HeartSolidIcon,
    StarIcon as StarSolidIcon
} from '@heroicons/vue/24/solid'
</script>

<template>
    <!-- Outline icons -->
    <div class="flex gap-3">
        <Icon :icon="HomeIcon" />
        <Icon :icon="UserIcon" />
        <Icon :icon="CogIcon" />
        <Icon :icon="BellIcon" />
        <Icon :icon="EnvelopeIcon" />
    </div>

    <!-- Solid icons -->
    <div class="flex gap-3">
        <Icon :icon="HeartSolidIcon" color="danger" />
        <Icon :icon="StarSolidIcon" color="warning" />
    </div>
</template>
```

## Navigation Menu Example

```vue
<script setup>
import {
    HomeIcon,
    ChartBarIcon,
    UsersIcon,
    FolderIcon,
    CalendarIcon,
    CogIcon
} from '@heroicons/vue/24/outline'

const menuItems = [
    { icon: HomeIcon, label: 'Dashboard', href: '/' },
    { icon: ChartBarIcon, label: 'Analytics', href: '/analytics' },
    { icon: UsersIcon, label: 'Team', href: '/team' },
    { icon: FolderIcon, label: 'Projects', href: '/projects' },
    { icon: CalendarIcon, label: 'Calendar', href: '/calendar' },
    { icon: CogIcon, label: 'Settings', href: '/settings' },
]
</script>

<template>
    <nav class="space-y-1">
        <a
            v-for="item in menuItems"
            :key="item.href"
            :href="item.href"
            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100"
        >
            <Icon :icon="item.icon" color="muted" />
            <span>{{ item.label }}</span>
        </a>
    </nav>
</template>
```

## Status Icons Example

```vue
<script setup>
import {
    CheckCircleIcon,
    XCircleIcon,
    ExclamationCircleIcon,
    InformationCircleIcon
} from '@heroicons/vue/24/solid'
</script>

<template>
    <div class="space-y-3">
        <div class="flex items-center gap-2">
            <Icon :icon="CheckCircleIcon" color="success" />
            <span>Operation successful</span>
        </div>
        <div class="flex items-center gap-2">
            <Icon :icon="XCircleIcon" color="danger" />
            <span>Error occurred</span>
        </div>
        <div class="flex items-center gap-2">
            <Icon :icon="ExclamationCircleIcon" color="warning" />
            <span>Warning: Check settings</span>
        </div>
        <div class="flex items-center gap-2">
            <Icon :icon="InformationCircleIcon" color="info" />
            <span>Informational message</span>
        </div>
    </div>
</template>
```

## Feature List Example

```vue
<script setup>
import { CheckIcon } from '@heroicons/vue/24/outline'

const features = [
    'Unlimited projects',
    'Advanced analytics',
    'Priority support',
    'Custom integrations',
]
</script>

<template>
    <ul class="space-y-2">
        <li v-for="feature in features" :key="feature" class="flex items-center gap-2">
            <Icon :icon="CheckIcon" color="success" size="sm" />
            <span>{{ feature }}</span>
        </li>
    </ul>
</template>
```

## Action Buttons Example

```vue
<script setup>
import {
    PencilIcon,
    TrashIcon,
    DocumentDuplicateIcon,
    ArrowDownTrayIcon
} from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex gap-2">
        <button class="p-2 hover:bg-gray-100 rounded">
            <Icon :icon="PencilIcon" color="muted" size="sm" />
        </button>
        <button class="p-2 hover:bg-gray-100 rounded">
            <Icon :icon="DocumentDuplicateIcon" color="muted" size="sm" />
        </button>
        <button class="p-2 hover:bg-gray-100 rounded">
            <Icon :icon="ArrowDownTrayIcon" color="muted" size="sm" />
        </button>
        <button class="p-2 hover:bg-red-50 rounded">
            <Icon :icon="TrashIcon" color="danger" size="sm" />
        </button>
    </div>
</template>
```

## Empty State Example

```vue
<script setup>
import { FolderOpenIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="text-center py-12">
        <Icon :icon="FolderOpenIcon" size="2xl" color="muted" class="mx-auto" />
        <h3 class="mt-4 text-lg font-medium text-gray-900">No projects</h3>
        <p class="mt-1 text-gray-500">Get started by creating a new project.</p>
    </div>
</template>
```

## Card with Icon Example

```vue
<script setup>
import { ChartBarIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-primary-100 rounded-lg">
                <Icon :icon="ChartBarIcon" size="lg" color="primary" />
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Revenue</p>
                <p class="text-2xl font-bold">$45,231</p>
            </div>
        </div>
    </div>
</template>
```

## Accessibility

- Icons have `aria-hidden="true"` to hide them from screen readers
- When icons convey meaning, always pair them with text or use appropriate ARIA labels
- The `flex-shrink-0` class prevents icons from shrinking in flex containers

## Playground

Try the Icon component:

<LiveDemo>
  <div style="display: flex; align-items: center; gap: 16px;">
    <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #eef2ff; border-radius: 8px; color: #4f46e5;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
      </svg>
    </span>
    <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #fef2f2; border-radius: 8px; color: #dc2626;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
        <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
      </svg>
    </span>
    <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #ecfdf5; border-radius: 8px; color: #059669;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
      </svg>
    </span>
  </div>

  <template #code>

```vue
<script setup>
import { StarIcon } from '@heroicons/vue/24/outline'
import { HeartIcon } from '@heroicons/vue/24/solid'
import { CheckIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex items-center gap-4">
        <Icon :icon="StarIcon" color="primary" />
        <Icon :icon="HeartIcon" color="danger" />
        <Icon :icon="CheckIcon" color="success" />
    </div>
</template>
```

  </template>
</LiveDemo>
