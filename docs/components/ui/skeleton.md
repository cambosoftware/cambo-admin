# Skeleton

A placeholder loading component that mimics the shape of content before it loads. Provides a smooth loading experience and reduces perceived wait time.

## Import

```vue
<script setup>
import Skeleton from '@/Components/UI/Skeleton.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `String` | `'text'` | Shape variant. Options: `'text'`, `'circular'`, `'rectangular'`, `'rounded'` |
| `width` | `String` | `null` | Custom width (CSS value, e.g., '100px', '50%') |
| `height` | `String` | `null` | Custom height (CSS value) |
| `lines` | `Number` | `1` | Number of text lines to render (only for text variant) |

## Basic Usage

```vue
<template>
    <Skeleton />
</template>
```

## Variants

### Text Skeleton

Default variant for text content. Has a fixed height suitable for single lines:

```vue
<template>
    <Skeleton variant="text" />
    <Skeleton variant="text" width="75%" />
    <Skeleton variant="text" width="50%" />
</template>
```

### Circular Skeleton

Perfect for avatars and profile pictures:

```vue
<template>
    <Skeleton variant="circular" width="40px" />
    <Skeleton variant="circular" width="64px" />
    <Skeleton variant="circular" width="96px" />
</template>
```

Note: When `variant="circular"`, if no height is specified, it defaults to the width value (creating a perfect circle).

### Rectangular Skeleton

For images or cards with sharp corners:

```vue
<template>
    <Skeleton variant="rectangular" width="100%" height="200px" />
</template>
```

### Rounded Skeleton

For elements with rounded corners:

```vue
<template>
    <Skeleton variant="rounded" width="100%" height="120px" />
</template>
```

## Multiple Lines

For paragraph placeholders:

```vue
<template>
    <!-- 3 lines of text -->
    <Skeleton variant="text" :lines="3" />

    <!-- 5 lines of text -->
    <Skeleton variant="text" :lines="5" width="100%" />
</template>
```

When using multiple lines, the last line is automatically shortened to 75% width for a more natural appearance.

## Custom Dimensions

```vue
<template>
    <!-- Fixed width -->
    <Skeleton width="200px" />

    <!-- Percentage width -->
    <Skeleton width="80%" />

    <!-- Custom height -->
    <Skeleton width="100%" height="100px" variant="rounded" />

    <!-- Square -->
    <Skeleton width="150px" height="150px" variant="rectangular" />
</template>
```

## User Card Skeleton

```vue
<template>
    <div class="flex items-center gap-4 p-4 bg-white rounded-lg shadow">
        <!-- Avatar -->
        <Skeleton variant="circular" width="48px" />

        <div class="flex-1 space-y-2">
            <!-- Name -->
            <Skeleton variant="text" width="60%" />
            <!-- Email -->
            <Skeleton variant="text" width="80%" />
        </div>
    </div>
</template>
```

## Article Card Skeleton

```vue
<template>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Image placeholder -->
        <Skeleton variant="rectangular" width="100%" height="200px" />

        <div class="p-4 space-y-3">
            <!-- Title -->
            <Skeleton variant="text" width="90%" />

            <!-- Description -->
            <Skeleton variant="text" :lines="3" />

            <!-- Author and date -->
            <div class="flex items-center gap-3 pt-2">
                <Skeleton variant="circular" width="32px" />
                <Skeleton variant="text" width="120px" />
            </div>
        </div>
    </div>
</template>
```

## Table Row Skeleton

```vue
<template>
    <table class="w-full">
        <tbody>
            <tr v-for="i in 5" :key="i" class="border-b">
                <td class="py-4 px-2">
                    <Skeleton variant="text" width="80%" />
                </td>
                <td class="py-4 px-2">
                    <Skeleton variant="text" width="60%" />
                </td>
                <td class="py-4 px-2">
                    <Skeleton variant="text" width="40%" />
                </td>
                <td class="py-4 px-2">
                    <Skeleton variant="circular" width="32px" />
                </td>
            </tr>
        </tbody>
    </table>
</template>
```

## Product Card Skeleton

```vue
<template>
    <div class="bg-white rounded-lg shadow p-4">
        <!-- Product image -->
        <Skeleton variant="rounded" width="100%" height="180px" />

        <div class="mt-4 space-y-2">
            <!-- Product name -->
            <Skeleton variant="text" width="70%" />

            <!-- Price -->
            <Skeleton variant="text" width="40%" />

            <!-- Rating -->
            <div class="flex gap-1">
                <Skeleton v-for="i in 5" :key="i" variant="circular" width="16px" />
            </div>
        </div>
    </div>
</template>
```

## Comment List Skeleton

```vue
<template>
    <div class="space-y-4">
        <div v-for="i in 3" :key="i" class="flex gap-3">
            <!-- Avatar -->
            <Skeleton variant="circular" width="40px" />

            <div class="flex-1 space-y-2">
                <!-- Author name -->
                <Skeleton variant="text" width="120px" />
                <!-- Comment text -->
                <Skeleton variant="text" :lines="2" />
            </div>
        </div>
    </div>
</template>
```

## Dashboard Stats Skeleton

```vue
<template>
    <div class="grid grid-cols-4 gap-4">
        <div v-for="i in 4" :key="i" class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center gap-4">
                <Skeleton variant="rounded" width="48px" height="48px" />
                <div class="space-y-2">
                    <Skeleton variant="text" width="80px" />
                    <Skeleton variant="text" width="60px" />
                </div>
            </div>
        </div>
    </div>
</template>
```

## Profile Page Skeleton

```vue
<template>
    <div class="max-w-2xl mx-auto">
        <!-- Cover image -->
        <Skeleton variant="rectangular" width="100%" height="200px" />

        <div class="px-6 pb-6">
            <!-- Avatar (overlapping cover) -->
            <div class="-mt-16 mb-4">
                <Skeleton variant="circular" width="128px" class="border-4 border-white" />
            </div>

            <!-- Name -->
            <Skeleton variant="text" width="200px" />

            <!-- Bio -->
            <div class="mt-4">
                <Skeleton variant="text" :lines="3" />
            </div>

            <!-- Stats -->
            <div class="flex gap-6 mt-6">
                <Skeleton variant="text" width="80px" />
                <Skeleton variant="text" width="80px" />
                <Skeleton variant="text" width="80px" />
            </div>
        </div>
    </div>
</template>
```

## Form Skeleton

```vue
<template>
    <div class="space-y-6">
        <!-- Input field -->
        <div class="space-y-2">
            <Skeleton variant="text" width="100px" />
            <Skeleton variant="rounded" width="100%" height="40px" />
        </div>

        <!-- Input field -->
        <div class="space-y-2">
            <Skeleton variant="text" width="80px" />
            <Skeleton variant="rounded" width="100%" height="40px" />
        </div>

        <!-- Textarea -->
        <div class="space-y-2">
            <Skeleton variant="text" width="120px" />
            <Skeleton variant="rounded" width="100%" height="100px" />
        </div>

        <!-- Button -->
        <Skeleton variant="rounded" width="120px" height="40px" />
    </div>
</template>
```

## Conditional Rendering

```vue
<script setup>
const isLoading = ref(true)
const user = ref(null)

onMounted(async () => {
    user.value = await fetchUser()
    isLoading.value = false
})
</script>

<template>
    <div class="flex items-center gap-4">
        <template v-if="isLoading">
            <Skeleton variant="circular" width="48px" />
            <div class="space-y-2">
                <Skeleton variant="text" width="150px" />
                <Skeleton variant="text" width="200px" />
            </div>
        </template>

        <template v-else>
            <Avatar :src="user.avatar" :name="user.name" size="lg" />
            <div>
                <p class="font-medium">{{ user.name }}</p>
                <p class="text-gray-500">{{ user.email }}</p>
            </div>
        </template>
    </div>
</template>
```

## Grid of Cards Skeleton

```vue
<template>
    <div class="grid grid-cols-3 gap-6">
        <div v-for="i in 6" :key="i" class="bg-white rounded-lg shadow overflow-hidden">
            <Skeleton variant="rectangular" height="160px" />
            <div class="p-4 space-y-3">
                <Skeleton variant="text" width="80%" />
                <Skeleton variant="text" :lines="2" />
                <Skeleton variant="text" width="40%" />
            </div>
        </div>
    </div>
</template>
```

## Styling Details

- Uses `bg-gray-200` background color
- Animated with `animate-pulse` for subtle pulsing effect
- Automatically applies appropriate border radius based on variant
- Multiple lines have `space-y-2` gap between them

## Accessibility

- Skeleton elements are purely decorative
- Use appropriate loading states and announcements for screen readers
- Consider providing text alternatives like "Loading content..."
- Replace skeletons with actual content once loaded

## Playground

Try the skeleton component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 20rem;">
    <DemoSkeleton variant="text" />
    <DemoSkeleton variant="text" width="75%" />
    <DemoSkeleton variant="text" width="50%" />
  </div>

  <template #code>

```vue
<Skeleton variant="text" />
<Skeleton variant="text" width="75%" />
<Skeleton variant="text" width="50%" />
```

  </template>
</LiveDemo>

### Skeleton Variants

<LiveDemo>
  <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
    <DemoSkeleton variant="text" width="100px" />
    <DemoSkeleton variant="circular" width="48px" />
    <DemoSkeleton variant="rectangular" width="100px" height="60px" />
    <DemoSkeleton variant="rounded" width="100px" height="60px" />
  </div>

  <template #code>

```vue
<Skeleton variant="text" width="100px" />
<Skeleton variant="circular" width="48px" />
<Skeleton variant="rectangular" width="100px" height="60px" />
<Skeleton variant="rounded" width="100px" height="60px" />
```

  </template>
</LiveDemo>

### User Card Skeleton

<LiveDemo>
  <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <DemoSkeleton variant="circular" width="48px" />
    <div style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
      <DemoSkeleton variant="text" width="60%" />
      <DemoSkeleton variant="text" width="80%" />
    </div>
  </div>

  <template #code>

```vue
<div class="flex items-center gap-3 p-4 bg-white rounded-lg shadow">
  <Skeleton variant="circular" width="48px" />
  <div class="flex-1 space-y-2">
    <Skeleton variant="text" width="60%" />
    <Skeleton variant="text" width="80%" />
  </div>
</div>
```

  </template>
</LiveDemo>
