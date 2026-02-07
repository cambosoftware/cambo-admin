# Toggle

A segmented control component for selecting between a small set of mutually exclusive options. Provides a compact, inline alternative to radio buttons.

## Import

```vue
<script setup>
import { Toggle } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String \| Number \| Boolean \| null` | `null` | The selected value (v-model) |
| `options` | `Array` | `[]` | Array of options (strings, numbers, or objects) |
| `size` | `String` | `'md'` | Toggle size. Allowed: `'sm'`, `'md'`, `'lg'` |
| `disabled` | `Boolean` | `false` | Disables the toggle |
| `error` | `String \| Boolean` | `null` | Error state. When truthy, shows error styling |
| `optionLabel` | `String` | `'label'` | Property name to use for option label |
| `optionValue` | `String` | `'value'` | Property name to use for option value |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| Number \| Boolean` | Emitted when selection changes |
| `change` | `String \| Number \| Boolean` | Emitted when selection changes |

## Option Formats

```vue
<!-- Simple strings -->
<Toggle :options="['Daily', 'Weekly', 'Monthly']" />

<!-- Simple numbers -->
<Toggle :options="[1, 2, 3, 5, 10]" />

<!-- Objects with label/value -->
<Toggle :options="[
    { label: 'Grid', value: 'grid' },
    { label: 'List', value: 'list' }
]" />

<!-- With icons -->
<Toggle :options="[
    { label: 'Grid', value: 'grid', icon: Squares2X2Icon },
    { label: 'List', value: 'list', icon: ListBulletIcon }
]" />

<!-- With disabled options -->
<Toggle :options="[
    { label: 'Free', value: 'free' },
    { label: 'Pro', value: 'pro', disabled: true }
]" />
```

## Basic Example

```vue
<script setup>
import { Toggle } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const view = ref('grid')
const options = [
    { value: 'grid', label: 'Grid' },
    { value: 'list', label: 'List' },
    { value: 'table', label: 'Table' }
]
</script>

<template>
    <Toggle v-model="view" :options="options" />
</template>
```

## Sizes

```vue
<!-- Small -->
<Toggle v-model="value" :options="options" size="sm" />

<!-- Medium (default) -->
<Toggle v-model="value" :options="options" size="md" />

<!-- Large -->
<Toggle v-model="value" :options="options" size="lg" />
```

### Size Specifications

| Size | Padding | Font Size |
|------|---------|-----------|
| `sm` | `px-2.5 py-1` | `text-xs` |
| `md` | `px-3 py-1.5` | `text-sm` |
| `lg` | `px-4 py-2` | `text-base` |

## With Icons

```vue
<script setup>
import { Toggle } from '@cambosoftware/cambo-admin'
import { Squares2X2Icon, ListBulletIcon, TableCellsIcon } from '@heroicons/vue/24/outline'
import { ref } from 'vue'

const viewMode = ref('grid')
const viewOptions = [
    { value: 'grid', label: 'Grid', icon: Squares2X2Icon },
    { value: 'list', label: 'List', icon: ListBulletIcon },
    { value: 'table', label: 'Table', icon: TableCellsIcon }
]
</script>

<template>
    <Toggle v-model="viewMode" :options="viewOptions" />
</template>
```

## Icon Only (No Labels)

```vue
<script setup>
const alignOptions = [
    { value: 'left', label: '', icon: AlignLeftIcon },
    { value: 'center', label: '', icon: AlignCenterIcon },
    { value: 'right', label: '', icon: AlignRightIcon }
]
</script>

<template>
    <Toggle v-model="alignment" :options="alignOptions" />
</template>
```

## Error State

```vue
<FormGroup label="Billing Cycle" :error="form.errors.billing_cycle">
    <Toggle
        v-model="form.billing_cycle"
        :options="billingOptions"
        :error="form.errors.billing_cycle"
    />
</FormGroup>
```

## Disabled State

```vue
<!-- All disabled -->
<Toggle v-model="value" :options="options" disabled />

<!-- Individual options disabled -->
<Toggle
    v-model="value"
    :options="[
        { value: 'a', label: 'Option A' },
        { value: 'b', label: 'Option B', disabled: true },
        { value: 'c', label: 'Option C' }
    ]"
/>
```

## Boolean Toggle

```vue
<script setup>
const isPublic = ref(false)
const publicOptions = [
    { value: false, label: 'Private' },
    { value: true, label: 'Public' }
]
</script>

<template>
    <Toggle v-model="isPublic" :options="publicOptions" />
</template>
```

## View Mode Switcher

```vue
<script setup>
import { Toggle } from '@cambosoftware/cambo-admin'
import { computed, ref } from 'vue'

const view = ref('grid')
const viewOptions = [
    { value: 'grid', label: 'Grid' },
    { value: 'list', label: 'List' }
]

// Use view to conditionally render
const isGridView = computed(() => view.value === 'grid')
</script>

<template>
    <div class="flex justify-between items-center mb-4">
        <h2>Products</h2>
        <Toggle v-model="view" :options="viewOptions" size="sm" />
    </div>

    <div v-if="isGridView" class="grid grid-cols-3 gap-4">
        <!-- Grid view content -->
    </div>
    <div v-else class="space-y-2">
        <!-- List view content -->
    </div>
</template>
```

## Billing Cycle Selector

```vue
<script setup>
const billingCycle = ref('monthly')
const billingOptions = [
    { value: 'monthly', label: 'Monthly' },
    { value: 'yearly', label: 'Yearly (Save 20%)' }
]
</script>

<template>
    <div class="text-center">
        <Toggle v-model="billingCycle" :options="billingOptions" />

        <div class="mt-4">
            <p v-if="billingCycle === 'monthly'" class="text-2xl font-bold">
                $29/month
            </p>
            <p v-else class="text-2xl font-bold">
                $279/year <span class="text-green-600 text-sm">($23.25/mo)</span>
            </p>
        </div>
    </div>
</template>
```

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, Toggle, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    title: '',
    visibility: 'private',
    status: 'draft'
})

const visibilityOptions = [
    { value: 'private', label: 'Private' },
    { value: 'unlisted', label: 'Unlisted' },
    { value: 'public', label: 'Public' }
]

const statusOptions = [
    { value: 'draft', label: 'Draft' },
    { value: 'published', label: 'Published' }
]

const submit = (form) => {
    form.post('/posts')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Title" required :error="form.errors.title">
            <Input v-model="form.title" :error="form.errors.title" />
        </FormGroup>

        <FormGroup label="Visibility" :error="form.errors.visibility">
            <Toggle
                v-model="form.visibility"
                :options="visibilityOptions"
                :error="form.errors.visibility"
            />
        </FormGroup>

        <FormGroup label="Status" :error="form.errors.status">
            <Toggle
                v-model="form.status"
                :options="statusOptions"
                :error="form.errors.status"
            />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Save Post
        </Button>
    </Form>
</template>
```

## Theme Switcher Example

```vue
<script setup>
import { SunIcon, MoonIcon, ComputerDesktopIcon } from '@heroicons/vue/24/outline'

const theme = ref('system')
const themeOptions = [
    { value: 'light', label: 'Light', icon: SunIcon },
    { value: 'dark', label: 'Dark', icon: MoonIcon },
    { value: 'system', label: 'System', icon: ComputerDesktopIcon }
]
</script>

<template>
    <div class="flex items-center justify-between">
        <span class="text-sm font-medium text-gray-700">Theme</span>
        <Toggle v-model="theme" :options="themeOptions" size="sm" />
    </div>
</template>
```

## When to Use Toggle vs RadioGroup

| Use Toggle | Use RadioGroup |
|------------|----------------|
| 2-4 options | More than 4 options |
| Quick switching | Form selections |
| Toolbar controls | Settings pages |
| Compact space | Need descriptions |
| View mode switching | Complex choices |

## Styling

The Toggle component includes:
- Pill-shaped container with gray background
- Rounded button segments
- White background + shadow on selected
- Smooth transition animations
- Icon support (4x4 icons)

### Visual States

| State | Appearance |
|-------|------------|
| Container | `bg-gray-100` rounded pill |
| Selected | `bg-white` with shadow |
| Unselected | Transparent, gray text |
| Hover | Darker text color |
| Error | Red ring around container |
| Disabled | 50% opacity |

## Accessibility

- Container has `role="radiogroup"`
- Each button has `role="radio"` and `aria-checked`
- Full keyboard navigation
- Click to select
- Visible focus states
- Icons are decorative (labels provide meaning)

## Playground

Try the Toggle component:

<LiveDemo>
  <DemoToggle />

  <template #code>

```vue
<script setup>
import { Toggle } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const view = ref('grid')
const options = [
    { value: 'grid', label: 'Grid' },
    { value: 'list', label: 'List' },
    { value: 'table', label: 'Table' }
]
</script>

<template>
    <Toggle v-model="view" :options="options" />
</template>
```

  </template>
</LiveDemo>
