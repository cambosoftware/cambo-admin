# BackButton

A navigation button for going back to the previous page or a specified URL.

## Import

```vue
<script setup>
import { BackButton } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `href` | `String` | `null` | URL to navigate to. If not provided, uses browser history |
| `label` | `String` | `'Retour'` | Button text label |
| `showLabel` | `Boolean` | `true` | Show the text label |
| `variant` | `String` | `'default'` | Button style. Values: `'default'`, `'minimal'`, `'button'` |
| `iconStyle` | `String` | `'arrow'` | Icon type. Values: `'arrow'`, `'chevron'` |

## Slots

This component does not have slots.

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `click` | `Event` | Emitted when clicked (when no href is provided) |

## Variants

### Default
Simple text link with icon, suitable for inline use.

### Minimal
Icon-only button in a rounded circle, great for compact spaces.

### Button
Full button appearance with border and background.

## Icon Styles

### Arrow
Uses `ArrowLeftIcon` - a full arrow pointing left.

### Chevron
Uses `ChevronLeftIcon` - a simple chevron/angle pointing left.

## Examples

### Basic Back Button

```vue
<template>
    <BackButton />
</template>
```

### With Custom Label

```vue
<template>
    <BackButton label="Go Back" />
</template>
```

### Navigate to Specific URL

```vue
<template>
    <BackButton href="/dashboard" label="Back to Dashboard" />
</template>
```

### Minimal Variant (Icon Only)

```vue
<template>
    <BackButton variant="minimal" />
</template>
```

### Button Variant

```vue
<template>
    <BackButton variant="button" label="Previous" />
</template>
```

### Chevron Icon Style

```vue
<template>
    <BackButton iconStyle="chevron" />
</template>
```

### Without Label

```vue
<template>
    <BackButton :showLabel="false" />
</template>
```

### Page Header with Back Button

```vue
<template>
    <div class="mb-6">
        <BackButton href="/users" label="Back to Users" />
        <h1 class="mt-2 text-2xl font-bold">Edit User</h1>
    </div>
</template>
```

### Minimal in Card Header

```vue
<template>
    <Card>
        <template #header>
            <div class="flex items-center gap-3">
                <BackButton variant="minimal" href="/projects" />
                <div>
                    <h2 class="font-semibold">Project Details</h2>
                    <p class="text-sm text-gray-500">View and edit project</p>
                </div>
            </div>
        </template>

        <div>Project content...</div>
    </Card>
</template>
```

### Wizard Navigation

```vue
<template>
    <div class="flex justify-between items-center">
        <BackButton
            variant="button"
            label="Previous Step"
            iconStyle="chevron"
            @click="previousStep"
        />
        <Button @click="nextStep">Next Step</Button>
    </div>
</template>

<script setup>
const emit = defineEmits(['previous', 'next'])

const previousStep = () => emit('previous')
const nextStep = () => emit('next')
</script>
```

### Breadcrumb-like Navigation

```vue
<template>
    <div class="flex items-center gap-4">
        <BackButton href="/orders" label="Orders" variant="default" />
        <span class="text-gray-400">/</span>
        <span class="text-gray-900">Order #12345</span>
    </div>
</template>
```

### Mobile Header

```vue
<template>
    <header class="flex items-center h-14 px-4 border-b border-gray-200 bg-white">
        <BackButton variant="minimal" />
        <h1 class="flex-1 text-center font-semibold">Settings</h1>
        <div class="w-8" /> <!-- Spacer for centering -->
    </header>
</template>
```

### Button Variant with Chevron

```vue
<template>
    <BackButton
        variant="button"
        iconStyle="chevron"
        href="/dashboard"
        label="Dashboard"
    />
</template>
```

### Custom Click Handler

```vue
<template>
    <BackButton
        label="Cancel"
        @click="handleCancel"
    />
</template>

<script setup>
const handleCancel = () => {
    if (hasUnsavedChanges.value) {
        showConfirmDialog.value = true
    } else {
        router.back()
    }
}
</script>
```

### In PageHeader Component

```vue
<template>
    <PageHeader title="Edit Product">
        <template #back>
            <BackButton href="/products" />
        </template>

        <template #actions>
            <Button variant="outline">Cancel</Button>
            <Button>Save</Button>
        </template>
    </PageHeader>
</template>
```

## Styling

### Default Variant
```
inline-flex items-center gap-1.5 text-sm text-gray-600 hover:text-gray-900 transition-colors
```

### Minimal Variant
```
inline-flex items-center justify-center h-8 w-8 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors
```

### Button Variant
```
inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors
```

## Behavior

1. **With `href` prop**: Uses Inertia.js `Link` component for SPA navigation
2. **Without `href` prop**: Uses `window.history.back()` if history exists
3. **`click` event**: Emitted when no `href` is provided, allowing custom handling

## Accessibility

- Renders as `<a>` (with href) or `<button>` (without href)
- Has `type="button"` when rendered as button
- Icon is visible but label provides context
- Minimal variant should be used with additional context for screen readers

## Playground

Try the BackButton component:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 16px;">
    <div style="display: flex; align-items: center; gap: 16px;">
      <span style="font-size: 12px; color: #6b7280; width: 80px;">Default:</span>
      <a href="#" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; text-decoration: none; font-size: 14px;">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back
      </a>
    </div>
    <div style="display: flex; align-items: center; gap: 16px;">
      <span style="font-size: 12px; color: #6b7280; width: 80px;">Minimal:</span>
      <button style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; background: #f3f4f6; border: none; cursor: pointer; color: #6b7280;">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
      </button>
    </div>
    <div style="display: flex; align-items: center; gap: 16px;">
      <span style="font-size: 12px; color: #6b7280; width: 80px;">Button:</span>
      <button style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; cursor: pointer; font-size: 14px; color: #374151;">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Previous
      </button>
    </div>
  </div>

  <template #code>

```vue
<template>
  <!-- Default variant -->
  <BackButton label="Back" />

  <!-- Minimal variant (icon only) -->
  <BackButton variant="minimal" />

  <!-- Button variant -->
  <BackButton variant="button" label="Previous" iconStyle="chevron" />
</template>
```

  </template>
</LiveDemo>
