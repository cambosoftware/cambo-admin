# Spinner

An animated loading indicator component for displaying progress states. Provides visual feedback during asynchronous operations.

## Import

```vue
<script setup>
import Spinner from '@/Components/UI/Spinner.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `size` | `String` | `'md'` | Spinner size. Options: `'xs'`, `'sm'`, `'md'`, `'lg'`, `'xl'` |
| `color` | `String` | `'primary'` | Spinner color. Options: `'primary'`, `'white'`, `'gray'`, `'success'`, `'danger'` |
| `label` | `String` | `'Loading...'` | Accessible label for screen readers |

## Basic Usage

```vue
<template>
    <Spinner />
</template>
```

## Sizes

```vue
<template>
    <div class="flex items-center gap-4">
        <Spinner size="xs" />   <!-- 12px -->
        <Spinner size="sm" />   <!-- 16px -->
        <Spinner size="md" />   <!-- 24px -->
        <Spinner size="lg" />   <!-- 32px -->
        <Spinner size="xl" />   <!-- 48px -->
    </div>
</template>
```

Size specifications:

| Size | Dimensions |
|------|------------|
| `xs` | 12px (h-3 w-3) |
| `sm` | 16px (h-4 w-4) |
| `md` | 24px (h-6 w-6) |
| `lg` | 32px (h-8 w-8) |
| `xl` | 48px (h-12 w-12) |

## Colors

```vue
<template>
    <div class="flex items-center gap-4">
        <Spinner color="primary" />
        <Spinner color="gray" />
        <Spinner color="success" />
        <Spinner color="danger" />
    </div>

    <!-- White spinner (for dark backgrounds) -->
    <div class="bg-gray-800 p-4 rounded">
        <Spinner color="white" />
    </div>
</template>
```

Color specifications:

| Color | Class |
|-------|-------|
| `primary` | `text-primary-600` |
| `white` | `text-white` |
| `gray` | `text-gray-400` |
| `success` | `text-emerald-600` |
| `danger` | `text-red-600` |

## Custom Label

```vue
<template>
    <Spinner label="Saving your changes..." />
    <Spinner label="Uploading file..." />
    <Spinner label="Please wait..." />
</template>
```

## Loading State Example

```vue
<script setup>
const isLoading = ref(false)

const fetchData = async () => {
    isLoading.value = true
    try {
        await api.getData()
    } finally {
        isLoading.value = false
    }
}
</script>

<template>
    <div v-if="isLoading" class="flex items-center justify-center py-8">
        <Spinner size="lg" />
    </div>
    <div v-else>
        <!-- Content -->
    </div>
</template>
```

## Button with Spinner

```vue
<script setup>
import Button from '@/Components/UI/Button.vue'
</script>

<template>
    <Button :loading="isSubmitting">
        <Spinner v-if="isSubmitting" size="sm" color="white" class="mr-2" />
        {{ isSubmitting ? 'Saving...' : 'Save' }}
    </Button>
</template>
```

Note: The Button component has built-in loading state support, so you typically don't need to manually add a Spinner.

## Inline Loading

```vue
<template>
    <span class="inline-flex items-center gap-2">
        <Spinner size="xs" />
        <span class="text-sm text-gray-500">Loading more items...</span>
    </span>
</template>
```

## Full Page Loading

```vue
<template>
    <div class="fixed inset-0 bg-white/80 flex items-center justify-center z-50">
        <div class="text-center">
            <Spinner size="xl" />
            <p class="mt-4 text-gray-600">Loading application...</p>
        </div>
    </div>
</template>
```

## Card Loading State

```vue
<template>
    <div class="bg-white rounded-lg shadow p-6">
        <div v-if="isLoading" class="flex flex-col items-center py-8">
            <Spinner size="lg" />
            <p class="mt-4 text-gray-500">Loading data...</p>
        </div>
        <div v-else>
            <!-- Card content -->
        </div>
    </div>
</template>
```

## Table Loading Overlay

```vue
<template>
    <div class="relative">
        <table class="w-full">
            <!-- Table content -->
        </table>

        <!-- Loading overlay -->
        <div
            v-if="isLoading"
            class="absolute inset-0 bg-white/75 flex items-center justify-center"
        >
            <Spinner size="lg" />
        </div>
    </div>
</template>
```

## Infinite Scroll Loading

```vue
<template>
    <div>
        <ul>
            <li v-for="item in items" :key="item.id">
                {{ item.name }}
            </li>
        </ul>

        <div v-if="hasMore" class="py-4 text-center">
            <Spinner size="sm" />
            <span class="ml-2 text-sm text-gray-500">Loading more...</span>
        </div>
    </div>
</template>
```

## Form Submission

```vue
<script setup>
const isSubmitting = ref(false)

const handleSubmit = async () => {
    isSubmitting.value = true
    try {
        await submitForm()
    } finally {
        isSubmitting.value = false
    }
}
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <!-- Form fields -->

        <div class="flex items-center gap-4">
            <Button type="submit" :disabled="isSubmitting">
                Submit
            </Button>
            <Spinner v-if="isSubmitting" size="sm" color="primary" />
        </div>
    </form>
</template>
```

## Search Loading

```vue
<script setup>
const isSearching = ref(false)
const searchQuery = ref('')

watch(searchQuery, async (query) => {
    if (query.length > 2) {
        isSearching.value = true
        await search(query)
        isSearching.value = false
    }
})
</script>

<template>
    <div class="relative">
        <input
            v-model="searchQuery"
            type="text"
            placeholder="Search..."
            class="w-full px-4 py-2 pr-10 border rounded-lg"
        />
        <div class="absolute right-3 top-1/2 -translate-y-1/2">
            <Spinner v-if="isSearching" size="xs" />
        </div>
    </div>
</template>
```

## Dashboard Widget Loading

```vue
<template>
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="font-medium mb-4">Recent Activity</h3>

        <div v-if="isLoading" class="space-y-3">
            <div class="flex items-center justify-center py-6">
                <Spinner />
            </div>
        </div>

        <ul v-else class="space-y-3">
            <li v-for="activity in activities" :key="activity.id">
                {{ activity.description }}
            </li>
        </ul>
    </div>
</template>
```

## With Different Backgrounds

```vue
<template>
    <!-- Light background -->
    <div class="bg-gray-100 p-4 rounded">
        <Spinner color="primary" />
    </div>

    <!-- Dark background -->
    <div class="bg-gray-900 p-4 rounded">
        <Spinner color="white" />
    </div>

    <!-- Primary background -->
    <div class="bg-primary-600 p-4 rounded">
        <Spinner color="white" />
    </div>

    <!-- Success background -->
    <div class="bg-emerald-600 p-4 rounded">
        <Spinner color="white" />
    </div>
</template>
```

## Accessibility

- The spinner has `role="status"` for screen reader announcement
- The `aria-label` attribute (from the `label` prop) provides context
- The animation uses CSS for smooth performance
- Default label is "Loading..." but can be customized for context

## Playground

Try the spinner component with different props:

<LiveDemo>
  <div style="display: flex; gap: 2rem; align-items: center; flex-wrap: wrap;">
    <DemoSpinner size="xs" />
    <DemoSpinner size="sm" />
    <DemoSpinner size="md" />
    <DemoSpinner size="lg" />
    <DemoSpinner size="xl" />
  </div>

  <template #code>

```vue
<Spinner size="xs" />
<Spinner size="sm" />
<Spinner size="md" />
<Spinner size="lg" />
<Spinner size="xl" />
```

  </template>
</LiveDemo>

### Spinner Colors

<LiveDemo>
  <div style="display: flex; gap: 2rem; align-items: center; flex-wrap: wrap;">
    <DemoSpinner color="primary" />
    <DemoSpinner color="secondary" />
    <DemoSpinner color="success" />
    <DemoSpinner color="danger" />
    <DemoSpinner color="warning" />
  </div>

  <template #code>

```vue
<Spinner color="primary" />
<Spinner color="secondary" />
<Spinner color="success" />
<Spinner color="danger" />
<Spinner color="warning" />
```

  </template>
</LiveDemo>

### With Label

<LiveDemo>
  <div style="display: flex; gap: 2rem; align-items: center; flex-wrap: wrap;">
    <DemoSpinner label="Loading..." />
    <DemoSpinner label="Please wait..." color="success" />
  </div>

  <template #code>

```vue
<Spinner label="Loading..." />
<Spinner label="Please wait..." color="success" />
```

  </template>
</LiveDemo>
