# Navbar

A responsive top navigation bar component that displays page title, breadcrumbs, search, and user menu.

## Import

```vue
<script setup>
import { Navbar } from 'cambo-admin'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `String` | `''` | Page title displayed in the navbar |
| `breadcrumb` | `Array` | `[]` | Array of breadcrumb items for navigation |
| `showSearch` | `Boolean` | `false` | Whether to show the search input field |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `toggle-sidebar` | - | Emitted when the mobile menu button is clicked |

## Slots

| Name | Description |
|------|-------------|
| `actions` | Custom action buttons displayed before notifications |
| `user-menu` | Custom user dropdown menu content |

## Usage

### Basic Usage

```vue
<template>
  <Navbar
    title="Dashboard"
    @toggle-sidebar="toggleMobileSidebar"
  />
</template>

<script setup>
const toggleMobileSidebar = () => {
  // Handle sidebar toggle
}
</script>
```

### With Breadcrumb

```vue
<template>
  <Navbar
    title="User Profile"
    :breadcrumb="[
      { label: 'Users', href: '/users' },
      { label: 'John Doe', href: '/users/1' },
      { label: 'Profile' }
    ]"
    @toggle-sidebar="toggleMobileSidebar"
  />
</template>
```

### With Search

```vue
<template>
  <Navbar
    title="Products"
    :show-search="true"
    @toggle-sidebar="toggleMobileSidebar"
  />
</template>
```

### With Custom Actions

```vue
<template>
  <Navbar title="Orders" @toggle-sidebar="toggleMobileSidebar">
    <template #actions>
      <button class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
      </button>

      <button class="btn btn-primary btn-sm">
        New Order
      </button>
    </template>
  </Navbar>
</template>
```

### With Custom User Menu

```vue
<template>
  <Navbar title="Dashboard" @toggle-sidebar="toggleMobileSidebar">
    <template #user-menu>
      <div class="py-1">
        <div class="px-4 py-2 border-b">
          <p class="text-sm font-medium">John Doe</p>
          <p class="text-xs text-gray-500">john@example.com</p>
        </div>

        <a href="/profile" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
          <UserIcon class="h-4 w-4" />
          My Profile
        </a>

        <a href="/settings" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
          <CogIcon class="h-4 w-4" />
          Settings
        </a>

        <a href="/billing" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
          <CreditCardIcon class="h-4 w-4" />
          Billing
        </a>

        <div class="border-t my-1" />

        <button
          @click="logout"
          class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50"
        >
          <LogoutIcon class="h-4 w-4" />
          Logout
        </button>
      </div>
    </template>
  </Navbar>
</template>
```

## Configuration Options

### Breadcrumb Item Structure

Each breadcrumb item can have the following properties:

```typescript
interface BreadcrumbItem {
  label: string      // Display text (required)
  href?: string      // URL link (optional)
  route?: string     // Laravel route name (optional)
  icon?: string      // Icon name (optional)
}
```

### Responsive Behavior

| Screen Size | Behavior |
|-------------|----------|
| Mobile (< 640px) | Shows hamburger menu, title only, user avatar only |
| Tablet (640px - 1024px) | Shows breadcrumb, search expands on click |
| Desktop (> 1024px) | Full navbar with all elements visible |

## Dependencies

The Navbar component injects the following from parent components:

| Injection | Type | Description |
|-----------|------|-------------|
| `currentUser` | `Ref<Object>` | Current authenticated user (from AdminLayout) |

### Expected User Object

```typescript
interface User {
  name: string
  email: string
  avatar?: string  // Optional avatar URL
}
```

## Features

### Built-in Notification Button

The navbar includes a notification bell button with a badge indicator. This is a placeholder for notification functionality.

### User Avatar

- If the user has an `avatar` property, it displays the image
- Otherwise, it generates initials from the user's name
- Displays "?" if no user name is available

### Mobile Search

When `showSearch` is true:
- On mobile: Search icon button that reveals a full-width search input
- On desktop: Always-visible search input in the navbar

### Dark Mode Support

The navbar automatically adapts to dark mode using Tailwind's `dark:` variants:
- Light mode: White background, gray text
- Dark mode: Dark gray background, light text

## Playground

Try the Navbar component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: 16px;">
      <button style="display: none; padding: 8px; border: none; background: none; cursor: pointer;">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
      </button>
      <div>
        <nav style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #6b7280;">
          <a href="#" style="color: #6b7280; text-decoration: none;">Home</a>
          <span>/</span>
          <a href="#" style="color: #6b7280; text-decoration: none;">Users</a>
          <span>/</span>
          <span style="color: #111827;">Profile</span>
        </nav>
        <h1 style="font-size: 18px; font-weight: 600; color: #111827; margin: 4px 0 0 0;">User Profile</h1>
      </div>
    </div>
    <div style="display: flex; align-items: center; gap: 12px;">
      <button style="padding: 8px; border: none; background: none; cursor: pointer; color: #6b7280;">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
      </button>
      <div style="width: 32px; height: 32px; background: #4f46e5; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 600;">JD</div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <Navbar
    title="User Profile"
    :breadcrumb="[
      { label: 'Home', href: '/' },
      { label: 'Users', href: '/users' },
      { label: 'Profile' }
    ]"
    @toggle-sidebar="toggleMobileSidebar"
  />
</template>

<script setup>
const toggleMobileSidebar = () => {
  // Handle sidebar toggle
}
</script>
```

  </template>
</LiveDemo>
