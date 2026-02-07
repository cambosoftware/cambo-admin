# NavLink

A navigation link component that integrates with Inertia.js and provides automatic active state detection.

## Import

```vue
<script setup>
import { NavLink } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `href` | `String` | **required** | The URL to navigate to |
| `active` | `Boolean` | `null` | Override automatic active detection |
| `exact` | `Boolean` | `false` | Match URL exactly (not prefix matching) |
| `icon` | `Object` | `null` | Icon component to display |
| `disabled` | `Boolean` | `false` | Disable the link |
| `variant` | `String` | `'default'` | Link style. Values: `'default'`, `'pills'`, `'underline'`, `'minimal'` |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Link text content |

## Events

This component does not emit custom events. Standard link events are available.

## Active State Detection

By default, NavLink automatically determines if it's active based on the current URL:
- **Prefix matching** (default): Active if current URL starts with `href`
- **Exact matching** (`exact` prop): Active only if current URL equals `href`

You can override this with the `active` prop for manual control.

## Variants

### Default
Standard navigation link with subtle background on active/hover.

### Pills
Rounded pill style with filled background when active.

### Underline
Bottom border indicator, common for horizontal navigation.

### Minimal
No background, only text color changes.

## Examples

### Basic Navigation

```vue
<template>
    <nav class="flex gap-2">
        <NavLink href="/dashboard">Dashboard</NavLink>
        <NavLink href="/users">Users</NavLink>
        <NavLink href="/settings">Settings</NavLink>
    </nav>
</template>
```

### With Icons

```vue
<template>
    <nav class="flex gap-2">
        <NavLink href="/dashboard" :icon="HomeIcon">Dashboard</NavLink>
        <NavLink href="/users" :icon="UsersIcon">Users</NavLink>
        <NavLink href="/settings" :icon="CogIcon">Settings</NavLink>
    </nav>
</template>

<script setup>
import { HomeIcon, UsersIcon, CogIcon } from '@heroicons/vue/24/outline'
</script>
```

### Pills Variant

```vue
<template>
    <nav class="flex gap-1">
        <NavLink href="/all" variant="pills">All</NavLink>
        <NavLink href="/active" variant="pills">Active</NavLink>
        <NavLink href="/archived" variant="pills">Archived</NavLink>
    </nav>
</template>
```

### Underline Variant

```vue
<template>
    <nav class="flex border-b border-gray-200">
        <NavLink href="/overview" variant="underline">Overview</NavLink>
        <NavLink href="/analytics" variant="underline">Analytics</NavLink>
        <NavLink href="/reports" variant="underline">Reports</NavLink>
    </nav>
</template>
```

### Minimal Variant

```vue
<template>
    <nav class="flex gap-4">
        <NavLink href="/home" variant="minimal">Home</NavLink>
        <NavLink href="/about" variant="minimal">About</NavLink>
        <NavLink href="/contact" variant="minimal">Contact</NavLink>
    </nav>
</template>
```

### Exact Matching

```vue
<template>
    <nav>
        <!-- Only active when URL is exactly "/users" -->
        <NavLink href="/users" exact>Users</NavLink>

        <!-- Active for "/users", "/users/1", "/users/create", etc. -->
        <NavLink href="/users">All Users</NavLink>
    </nav>
</template>
```

### Manual Active State

```vue
<template>
    <nav>
        <NavLink href="/dashboard" :active="currentSection === 'dashboard'">
            Dashboard
        </NavLink>
        <NavLink href="/reports" :active="currentSection === 'reports'">
            Reports
        </NavLink>
    </nav>
</template>

<script setup>
import { ref } from 'vue'
const currentSection = ref('dashboard')
</script>
```

### Disabled Link

```vue
<template>
    <nav class="flex gap-2">
        <NavLink href="/dashboard">Dashboard</NavLink>
        <NavLink href="/premium" disabled>Premium (Upgrade Required)</NavLink>
    </nav>
</template>
```

### Sidebar Navigation

```vue
<template>
    <aside class="w-64 bg-white border-r border-gray-200 p-4">
        <nav class="space-y-1">
            <NavLink href="/dashboard" :icon="HomeIcon" class="w-full">
                Dashboard
            </NavLink>
            <NavLink href="/projects" :icon="FolderIcon" class="w-full">
                Projects
            </NavLink>
            <NavLink href="/team" :icon="UsersIcon" class="w-full">
                Team
            </NavLink>
            <NavLink href="/calendar" :icon="CalendarIcon" class="w-full">
                Calendar
            </NavLink>
            <NavLink href="/settings" :icon="CogIcon" class="w-full">
                Settings
            </NavLink>
        </nav>
    </aside>
</template>
```

### Tab-like Navigation

```vue
<template>
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px">
            <NavLink href="/account/profile" variant="underline">
                Profile
            </NavLink>
            <NavLink href="/account/security" variant="underline">
                Security
            </NavLink>
            <NavLink href="/account/notifications" variant="underline">
                Notifications
            </NavLink>
        </nav>
    </div>
</template>
```

## Styling

### Default Variant
- Base: `inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition-colors`
- Active: `bg-primary-100 text-primary-700`
- Inactive: `text-gray-600 hover:bg-gray-100 hover:text-gray-900`

### Pills Variant
- Base: `inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full transition-colors`
- Active: `bg-primary-500 text-white`
- Inactive: `text-gray-600 hover:bg-gray-100 hover:text-gray-900`

### Underline Variant
- Base: `inline-flex items-center gap-2 px-1 py-2 text-sm font-medium border-b-2 transition-colors`
- Active: `border-primary-500 text-primary-600`
- Inactive: `border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700`

### Minimal Variant
- Base: `inline-flex items-center gap-2 text-sm font-medium transition-colors`
- Active: `text-primary-600`
- Inactive: `text-gray-500 hover:text-gray-900`

### Disabled State
- `opacity-50 cursor-not-allowed pointer-events-none`

## Accessibility

- Uses Inertia.js `Link` component for SPA navigation
- Active link has `aria-current="page"` attribute
- Disabled links prevent navigation
- Icon has appropriate sizing and color states

## Playground

Try the nav link component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.5rem;">
    <DemoNavLink>Default Link</DemoNavLink>
    <DemoNavLink active>Active Link</DemoNavLink>
    <DemoNavLink disabled>Disabled Link</DemoNavLink>
    <DemoNavLink badge="5">With Badge</DemoNavLink>
  </div>

  <template #code>

```vue
<NavLink href="/dashboard">Default Link</NavLink>
<NavLink href="/dashboard" active>Active Link</NavLink>
<NavLink href="/dashboard" disabled>Disabled Link</NavLink>
<NavLink href="/notifications" badge="5">With Badge</NavLink>
```

  </template>
</LiveDemo>

### Navigation Examples

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <DemoNavLink>Dashboard</DemoNavLink>
    <DemoNavLink active>Users</DemoNavLink>
    <DemoNavLink>Settings</DemoNavLink>
    <DemoNavLink>Reports</DemoNavLink>
  </div>

  <template #code>

```vue
<nav class="flex gap-2">
  <NavLink href="/dashboard">Dashboard</NavLink>
  <NavLink href="/users">Users</NavLink>
  <NavLink href="/settings">Settings</NavLink>
  <NavLink href="/reports">Reports</NavLink>
</nav>
```

  </template>
</LiveDemo>
