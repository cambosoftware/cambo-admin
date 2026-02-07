# Divider

A visual separator component for dividing content sections. Supports horizontal and vertical orientations, optional labels, and dashed styles.

## Import

```vue
<script setup>
import Divider from '@/Components/UI/Divider.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `String` | `null` | Optional text label to display in the center of the divider |
| `orientation` | `String` | `'horizontal'` | Divider orientation. Options: `'horizontal'`, `'vertical'` |
| `dashed` | `Boolean` | `false` | Renders divider with dashed line instead of solid |

## Basic Usage

```vue
<template>
    <div>
        <p>Content above</p>
        <Divider />
        <p>Content below</p>
    </div>
</template>
```

## Horizontal Divider (Default)

```vue
<template>
    <div class="space-y-4">
        <p>First section content goes here.</p>
        <Divider />
        <p>Second section content goes here.</p>
        <Divider />
        <p>Third section content goes here.</p>
    </div>
</template>
```

## Vertical Divider

```vue
<template>
    <div class="flex items-center h-8">
        <span>Item 1</span>
        <Divider orientation="vertical" />
        <span>Item 2</span>
        <Divider orientation="vertical" />
        <span>Item 3</span>
    </div>
</template>
```

## With Label

```vue
<template>
    <Divider label="OR" />
    <Divider label="Continue with" />
    <Divider label="More options" />
</template>
```

## Dashed Style

```vue
<template>
    <!-- Horizontal dashed -->
    <Divider dashed />

    <!-- With label and dashed -->
    <Divider label="Optional" dashed />

    <!-- Vertical dashed -->
    <div class="flex items-center h-8">
        <span>Left</span>
        <Divider orientation="vertical" dashed />
        <span>Right</span>
    </div>
</template>
```

## Login Form Example

```vue
<template>
    <div class="max-w-sm mx-auto">
        <form class="space-y-4">
            <input type="email" placeholder="Email" class="w-full px-4 py-2 border rounded-lg" />
            <input type="password" placeholder="Password" class="w-full px-4 py-2 border rounded-lg" />
            <Button block variant="primary">Sign In</Button>
        </form>

        <Divider label="OR" />

        <div class="space-y-2">
            <Button block variant="secondary">Continue with Google</Button>
            <Button block variant="secondary">Continue with GitHub</Button>
        </div>
    </div>
</template>
```

## Section Separator

```vue
<template>
    <article>
        <section>
            <h2>Introduction</h2>
            <p>Lorem ipsum dolor sit amet...</p>
        </section>

        <Divider />

        <section>
            <h2>Main Content</h2>
            <p>Consectetur adipiscing elit...</p>
        </section>

        <Divider />

        <section>
            <h2>Conclusion</h2>
            <p>Sed do eiusmod tempor...</p>
        </section>
    </article>
</template>
```

## Toolbar Divider

```vue
<script setup>
import IconButton from '@/Components/UI/IconButton.vue'
import { BoldIcon, ItalicIcon, UnderlineIcon, LinkIcon, PhotoIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="flex items-center gap-1 p-2 bg-gray-100 rounded-lg">
        <IconButton :icon="BoldIcon" label="Bold" />
        <IconButton :icon="ItalicIcon" label="Italic" />
        <IconButton :icon="UnderlineIcon" label="Underline" />

        <Divider orientation="vertical" />

        <IconButton :icon="LinkIcon" label="Insert link" />
        <IconButton :icon="PhotoIcon" label="Insert image" />
    </div>
</template>
```

## Navigation Breadcrumb

```vue
<template>
    <div class="flex items-center text-sm">
        <a href="/" class="text-gray-500 hover:text-gray-700">Home</a>
        <Divider orientation="vertical" />
        <a href="/products" class="text-gray-500 hover:text-gray-700">Products</a>
        <Divider orientation="vertical" />
        <span class="text-gray-900 font-medium">Electronics</span>
    </div>
</template>
```

## Stats Row

```vue
<template>
    <div class="flex items-center justify-center py-6 bg-white rounded-lg shadow">
        <div class="text-center px-6">
            <p class="text-2xl font-bold">1,234</p>
            <p class="text-sm text-gray-500">Users</p>
        </div>

        <Divider orientation="vertical" />

        <div class="text-center px-6">
            <p class="text-2xl font-bold">567</p>
            <p class="text-sm text-gray-500">Orders</p>
        </div>

        <Divider orientation="vertical" />

        <div class="text-center px-6">
            <p class="text-2xl font-bold">$89K</p>
            <p class="text-sm text-gray-500">Revenue</p>
        </div>
    </div>
</template>
```

## Card Footer Divider

```vue
<template>
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h3 class="font-bold text-lg">Card Title</h3>
            <p class="mt-2 text-gray-600">Card content goes here.</p>
        </div>

        <Divider />

        <div class="px-6 py-4 flex justify-end gap-2">
            <Button variant="secondary">Cancel</Button>
            <Button variant="primary">Save</Button>
        </div>
    </div>
</template>
```

## Menu Divider

```vue
<template>
    <div class="w-64 bg-white rounded-lg shadow-lg py-2">
        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a>

        <Divider />

        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Help Center</a>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Documentation</a>

        <Divider />

        <a href="#" class="block px-4 py-2 text-red-600 hover:bg-red-50">Sign Out</a>
    </div>
</template>
```

## Form Section Divider

```vue
<template>
    <form class="max-w-lg space-y-6">
        <div>
            <h3 class="font-medium">Personal Information</h3>
            <!-- Personal info fields -->
        </div>

        <Divider label="Address" dashed />

        <div>
            <!-- Address fields -->
        </div>

        <Divider label="Payment" dashed />

        <div>
            <!-- Payment fields -->
        </div>
    </form>
</template>
```

## Timeline Divider

```vue
<template>
    <div class="space-y-4">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-primary-500" />
            <div>
                <p class="font-medium">Order placed</p>
                <p class="text-sm text-gray-500">January 15, 2024</p>
            </div>
        </div>

        <Divider dashed />

        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-primary-500" />
            <div>
                <p class="font-medium">Order shipped</p>
                <p class="text-sm text-gray-500">January 17, 2024</p>
            </div>
        </div>

        <Divider dashed />

        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-gray-300" />
            <div>
                <p class="font-medium text-gray-400">Delivered</p>
                <p class="text-sm text-gray-400">Pending</p>
            </div>
        </div>
    </div>
</template>
```

## Styling Details

### Horizontal (simple)
- Uses `<hr>` element
- Margin: `my-4`
- Solid: `border-gray-200`
- Dashed: `border-dashed border-gray-300`

### Horizontal (with label)
- Uses flex container with centered text
- Background span with white background for clean overlay
- Label styling: `text-sm text-gray-500`

### Vertical
- Uses `<div>` with fixed width (`w-px`)
- Margin: `mx-2`
- Self-stretching to parent height
- Solid: `bg-gray-200`
- Dashed: `border-l border-dashed border-gray-300`

## Accessibility

- Horizontal dividers use semantic `<hr>` elements when no label is present
- Visual separation is maintained for all users
- Labels provide additional context where applicable
- Color contrast meets accessibility standards

## Playground

Try the divider component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 1rem; padding: 1rem;">
    <p style="margin: 0;">Content above</p>
    <DemoDivider />
    <p style="margin: 0;">Content below</p>
  </div>

  <template #code>

```vue
<p>Content above</p>
<Divider />
<p>Content below</p>
```

  </template>
</LiveDemo>

### Divider with Label

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 1rem; padding: 1rem;">
    <DemoDivider label="OR" />
    <DemoDivider label="Continue with" />
    <DemoDivider label="Section" dashed />
  </div>

  <template #code>

```vue
<Divider label="OR" />
<Divider label="Continue with" />
<Divider label="Section" dashed />
```

  </template>
</LiveDemo>

### Vertical Divider

<LiveDemo>
  <div style="display: flex; align-items: center; gap: 0.5rem; height: 2rem;">
    <span>Item 1</span>
    <DemoDivider orientation="vertical" />
    <span>Item 2</span>
    <DemoDivider orientation="vertical" />
    <span>Item 3</span>
  </div>

  <template #code>

```vue
<div class="flex items-center h-8">
  <span>Item 1</span>
  <Divider orientation="vertical" />
  <span>Item 2</span>
  <Divider orientation="vertical" />
  <span>Item 3</span>
</div>
```

  </template>
</LiveDemo>
