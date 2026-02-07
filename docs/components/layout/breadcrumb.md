# Breadcrumb

A navigation component that displays the current page location within a hierarchical structure.

## Import

```vue
<script setup>
import { Breadcrumb } from 'cambo-admin'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `items` | `Array` | **required** | Array of breadcrumb items |
| `showHome` | `Boolean` | `true` | Whether to show the home icon as the first item |
| `homeHref` | `String` | `'/'` | URL for the home link |
| `homeLabel` | `String` | `'Accueil'` | Accessible label for the home icon |

## Item Structure

Each item in the `items` array should have the following structure:

```typescript
interface BreadcrumbItem {
  label: string      // Display text (required)
  href?: string      // Direct URL link
  route?: string     // Laravel route name (uses Ziggy)
  icon?: string      // Optional icon (for future use)
}
```

## Usage

### Basic Usage

```vue
<template>
  <Breadcrumb
    :items="[
      { label: 'Users', href: '/users' },
      { label: 'John Doe' }
    ]"
  />
</template>
```

### Without Home Icon

```vue
<template>
  <Breadcrumb
    :items="[
      { label: 'Dashboard', href: '/dashboard' },
      { label: 'Reports', href: '/reports' },
      { label: 'Sales Report' }
    ]"
    :show-home="false"
  />
</template>
```

### With Custom Home URL

```vue
<template>
  <Breadcrumb
    :items="[
      { label: 'Products', href: '/products' },
      { label: 'Electronics' }
    ]"
    home-href="/dashboard"
    home-label="Dashboard"
  />
</template>
```

### Using Laravel Route Names

```vue
<template>
  <Breadcrumb
    :items="[
      { label: 'Users', route: 'admin.users.index' },
      { label: 'Create', route: 'admin.users.create' }
    ]"
  />
</template>
```

### Mixed href and route

```vue
<template>
  <Breadcrumb
    :items="[
      { label: 'Settings', href: '/settings' },
      { label: 'Security', route: 'settings.security' },
      { label: 'Two-Factor Auth' }
    ]"
  />
</template>
```

### Dynamic Breadcrumbs

```vue
<template>
  <Breadcrumb :items="breadcrumbItems" />
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.user)

const breadcrumbItems = computed(() => [
  { label: 'Users', href: '/users' },
  { label: user.value?.name || 'Loading...' }
])
</script>
```

## Configuration Options

### Link Resolution

The component resolves links in the following order:

1. If `href` is provided, use it directly
2. If `route` is provided, use Laravel's Ziggy route helper
3. If neither is provided, render as plain text (non-clickable)

### Styling

| Element | Style |
|---------|-------|
| Home icon | Gray with hover effect (`text-gray-400 hover:text-gray-500`) |
| Separator | Chevron right icon in light gray (`text-gray-300`) |
| Link items | Medium gray with hover effect (`text-gray-500 hover:text-gray-700`) |
| Current item | Dark text, non-clickable (`text-gray-900`) |

### Last Item Behavior

The last item in the breadcrumb:
- Is styled differently (`text-gray-900`)
- Has `aria-current="page"` for accessibility
- Is typically not a link (no href/route)

## Accessibility

- Uses semantic `<nav>` element with `aria-label="Breadcrumb"`
- Uses `<ol>` ordered list for proper structure
- Last item has `aria-current="page"` attribute
- Home icon has screen-reader text via `sr-only` class

## Integration with AdminLayout

When using AdminLayout, pass breadcrumb items via the `breadcrumb` prop:

```vue
<template>
  <AdminLayout
    title="User Profile"
    :breadcrumb="[
      { label: 'Users', href: '/users' },
      { label: 'John Doe' }
    ]"
  >
    <!-- Page content -->
  </AdminLayout>
</template>
```

## Full Example

```vue
<template>
  <AdminLayout :breadcrumb="breadcrumbs">
    <PageHeader
      title="Edit User"
      :breadcrumb="breadcrumbs"
    >
      <template #actions>
        <button class="btn btn-primary">Save</button>
      </template>
    </PageHeader>

    <!-- Form content -->
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  user: Object
})

const breadcrumbs = computed(() => [
  { label: 'Users', href: '/users' },
  { label: props.user.name, href: `/users/${props.user.id}` },
  { label: 'Edit' }
])
</script>
```

## Playground

Try the Breadcrumb component:

<LiveDemo>
  <nav style="display: flex; align-items: center; gap: 8px; font-size: 14px;">
    <a href="#" style="color: #9ca3af; text-decoration: none; display: flex; align-items: center;">
      <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
    </a>
    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="color: #d1d5db;"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
    <a href="#" style="color: #6b7280; text-decoration: none;">Users</a>
    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="color: #d1d5db;"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
    <a href="#" style="color: #6b7280; text-decoration: none;">John Doe</a>
    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="color: #d1d5db;"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
    <span style="color: #111827; font-weight: 500;">Edit</span>
  </nav>

  <template #code>

```vue
<template>
  <Breadcrumb
    :items="[
      { label: 'Users', href: '/users' },
      { label: 'John Doe', href: '/users/1' },
      { label: 'Edit' }
    ]"
  />
</template>
```

  </template>
</LiveDemo>
