# NavGroup

A container component for grouping navigation links with optional label and configurable layout.

## Import

```vue
<script setup>
import { NavGroup } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `String` | `null` | Accessible label for the navigation group |
| `variant` | `String` | `'horizontal'` | Layout direction. Values: `'horizontal'`, `'vertical'` |
| `spacing` | `String` | `'normal'` | Gap between items. Values: `'tight'`, `'normal'`, `'loose'` |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Navigation items (typically NavLink components) |

## Events

This component does not emit any events.

## Spacing Classes

### Horizontal Layout

| spacing | CSS Class |
|---------|-----------|
| `'tight'` | `gap-1` |
| `'normal'` | `gap-2` |
| `'loose'` | `gap-4` |

### Vertical Layout

| spacing | CSS Class |
|---------|-----------|
| `'tight'` | `gap-0.5` |
| `'normal'` | `gap-1` |
| `'loose'` | `gap-2` |

## Examples

### Basic Horizontal Navigation

```vue
<template>
    <NavGroup>
        <NavLink href="/dashboard">Dashboard</NavLink>
        <NavLink href="/users">Users</NavLink>
        <NavLink href="/settings">Settings</NavLink>
    </NavGroup>
</template>
```

### Vertical Navigation

```vue
<template>
    <NavGroup variant="vertical">
        <NavLink href="/dashboard">Dashboard</NavLink>
        <NavLink href="/users">Users</NavLink>
        <NavLink href="/reports">Reports</NavLink>
        <NavLink href="/settings">Settings</NavLink>
    </NavGroup>
</template>
```

### With Accessible Label

```vue
<template>
    <NavGroup label="Main navigation">
        <NavLink href="/">Home</NavLink>
        <NavLink href="/about">About</NavLink>
        <NavLink href="/contact">Contact</NavLink>
    </NavGroup>
</template>
```

### Tight Spacing

```vue
<template>
    <NavGroup spacing="tight">
        <NavLink href="/all" variant="pills">All</NavLink>
        <NavLink href="/active" variant="pills">Active</NavLink>
        <NavLink href="/archived" variant="pills">Archived</NavLink>
    </NavGroup>
</template>
```

### Loose Spacing

```vue
<template>
    <NavGroup spacing="loose">
        <NavLink href="/home" variant="minimal">Home</NavLink>
        <NavLink href="/features" variant="minimal">Features</NavLink>
        <NavLink href="/pricing" variant="minimal">Pricing</NavLink>
        <NavLink href="/contact" variant="minimal">Contact</NavLink>
    </NavGroup>
</template>
```

### Sidebar with Multiple Groups

```vue
<template>
    <aside class="w-64 p-4 space-y-6">
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                Main
            </h3>
            <NavGroup variant="vertical" label="Main navigation">
                <NavLink href="/dashboard" :icon="HomeIcon">Dashboard</NavLink>
                <NavLink href="/projects" :icon="FolderIcon">Projects</NavLink>
                <NavLink href="/tasks" :icon="ClipboardIcon">Tasks</NavLink>
            </NavGroup>
        </div>

        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                Team
            </h3>
            <NavGroup variant="vertical" label="Team navigation">
                <NavLink href="/team" :icon="UsersIcon">Members</NavLink>
                <NavLink href="/calendar" :icon="CalendarIcon">Calendar</NavLink>
                <NavLink href="/messages" :icon="ChatIcon">Messages</NavLink>
            </NavGroup>
        </div>

        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                Settings
            </h3>
            <NavGroup variant="vertical" label="Settings navigation">
                <NavLink href="/settings/profile" :icon="UserIcon">Profile</NavLink>
                <NavLink href="/settings/account" :icon="CogIcon">Account</NavLink>
            </NavGroup>
        </div>
    </aside>
</template>
```

### Header Navigation

```vue
<template>
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <Logo />
                <NavGroup label="Main navigation" spacing="loose">
                    <NavLink href="/features" variant="minimal">Features</NavLink>
                    <NavLink href="/pricing" variant="minimal">Pricing</NavLink>
                    <NavLink href="/docs" variant="minimal">Documentation</NavLink>
                    <NavLink href="/blog" variant="minimal">Blog</NavLink>
                </NavGroup>
            </div>
            <div class="flex items-center gap-4">
                <NavLink href="/login" variant="minimal">Sign in</NavLink>
                <Button href="/register">Get Started</Button>
            </div>
        </div>
    </header>
</template>
```

### Tab Navigation

```vue
<template>
    <div class="border-b border-gray-200">
        <NavGroup spacing="tight">
            <NavLink href="/account/profile" variant="underline">
                Profile
            </NavLink>
            <NavLink href="/account/security" variant="underline">
                Security
            </NavLink>
            <NavLink href="/account/notifications" variant="underline">
                Notifications
            </NavLink>
            <NavLink href="/account/billing" variant="underline">
                Billing
            </NavLink>
        </NavGroup>
    </div>
</template>
```

### Filter Pills

```vue
<template>
    <NavGroup spacing="tight">
        <NavLink href="?status=all" variant="pills" exact>All</NavLink>
        <NavLink href="?status=pending" variant="pills" exact>Pending</NavLink>
        <NavLink href="?status=active" variant="pills" exact>Active</NavLink>
        <NavLink href="?status=completed" variant="pills" exact>Completed</NavLink>
    </NavGroup>
</template>
```

### Mobile Menu

```vue
<template>
    <div class="lg:hidden">
        <NavGroup variant="vertical" spacing="tight" label="Mobile navigation">
            <NavLink href="/" class="w-full">Home</NavLink>
            <NavLink href="/features" class="w-full">Features</NavLink>
            <NavLink href="/pricing" class="w-full">Pricing</NavLink>
            <NavLink href="/about" class="w-full">About</NavLink>
            <NavLink href="/contact" class="w-full">Contact</NavLink>
        </NavGroup>
    </div>
</template>
```

## Styling

- Horizontal: `flex flex-row items-center`
- Vertical: `flex flex-col`
- Uses semantic `<nav>` element with `aria-label` when `label` prop is provided

## Accessibility

- Renders as `<nav>` element for semantic navigation
- Supports `aria-label` via the `label` prop
- Properly groups related navigation items
- Works with keyboard navigation

## Tips

- Use `label` prop for screen reader accessibility
- Combine with `NavLink` components for consistent styling
- Use `variant="vertical"` for sidebar navigation
- Use `spacing="tight"` for compact pill-style filters

## Playground

Try the NavGroup component:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 24px;">
    <div>
      <p style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">Horizontal (default)</p>
      <nav style="display: flex; gap: 8px;">
        <a href="#" style="padding: 8px 16px; background: #4f46e5; color: white; border-radius: 6px; text-decoration: none; font-size: 14px;">Dashboard</a>
        <a href="#" style="padding: 8px 16px; color: #6b7280; text-decoration: none; font-size: 14px;">Users</a>
        <a href="#" style="padding: 8px 16px; color: #6b7280; text-decoration: none; font-size: 14px;">Settings</a>
      </nav>
    </div>
    <div>
      <p style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">Vertical</p>
      <nav style="display: flex; flex-direction: column; gap: 4px; width: 200px;">
        <a href="#" style="padding: 8px 12px; background: #eef2ff; color: #4f46e5; border-radius: 6px; text-decoration: none; font-size: 14px;">Dashboard</a>
        <a href="#" style="padding: 8px 12px; color: #6b7280; text-decoration: none; font-size: 14px;">Users</a>
        <a href="#" style="padding: 8px 12px; color: #6b7280; text-decoration: none; font-size: 14px;">Settings</a>
      </nav>
    </div>
  </div>

  <template #code>

```vue
<template>
  <!-- Horizontal Navigation -->
  <NavGroup>
    <NavLink href="/dashboard">Dashboard</NavLink>
    <NavLink href="/users">Users</NavLink>
    <NavLink href="/settings">Settings</NavLink>
  </NavGroup>

  <!-- Vertical Navigation -->
  <NavGroup variant="vertical">
    <NavLink href="/dashboard">Dashboard</NavLink>
    <NavLink href="/users">Users</NavLink>
    <NavLink href="/settings">Settings</NavLink>
  </NavGroup>
</template>
```

  </template>
</LiveDemo>
