# AdminLayout

The main wrapper component for CamboAdmin pages. It provides the complete admin interface structure including sidebar, navbar, flash messages, and content area.

## Import

```vue
<script setup>
import { AdminLayout } from 'cambo-admin'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `String` | `''` | Page title displayed in the navbar |
| `breadcrumb` | `Array` | `[]` | Breadcrumb items array for navigation |
| `fluid` | `Boolean` | `false` | When `true`, content uses full width. When `false`, content is constrained to `max-w-7xl` |
| `sidebarCollapsible` | `Boolean` | `true` | Whether the sidebar can be collapsed |
| `sidebarCollapsedByDefault` | `Boolean` | `true` | Whether the sidebar starts in collapsed state |
| `sidebarExpandOnHover` | `Boolean` | `true` | When `true`, sidebar expands on hover. When `false`, uses toggle button |
| `sidebarMode` | `String` | `'overlay'` | Sidebar behavior: `'overlay'` (sidebar over content) or `'push'` (content adapts to sidebar width) |
| `sidebarPosition` | `String` | `'left'` | Sidebar position: `'left'`, `'right'`, or `'top'` |
| `showNavbar` | `Boolean` | `null` | Control navbar visibility. `null` = auto (hidden when sidebar is `'top'`) |

## Slots

| Name | Description |
|------|-------------|
| `default` | Main page content |
| `sidebar-header` | Custom content for sidebar header (replaces title) |
| `sidebar-menu` | Navigation menu items (use SidebarItem components) |
| `sidebar-footer` | Footer content in the sidebar |
| `header-actions` | Action buttons in the navbar (right side) |
| `user-menu` | Custom user dropdown menu content |

## Usage

### Basic Usage

```vue
<template>
  <AdminLayout title="Dashboard">
    <template #sidebar-menu>
      <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
      <SidebarItem href="/users" icon="users" label="Users" />
    </template>

    <div>
      <!-- Page content here -->
    </div>
  </AdminLayout>
</template>
```

### With Breadcrumb

```vue
<template>
  <AdminLayout
    title="User Details"
    :breadcrumb="[
      { label: 'Users', href: '/users' },
      { label: 'John Doe' }
    ]"
  >
    <template #sidebar-menu>
      <SidebarItem href="/users" icon="users" label="Users" />
    </template>

    <div>
      <!-- User details content -->
    </div>
  </AdminLayout>
</template>
```

### Fluid Layout

```vue
<template>
  <AdminLayout title="Reports" :fluid="true">
    <template #sidebar-menu>
      <!-- Menu items -->
    </template>

    <div>
      <!-- Full-width content for tables, charts, etc. -->
    </div>
  </AdminLayout>
</template>
```

### Push Mode Sidebar

```vue
<template>
  <AdminLayout
    title="Dashboard"
    sidebar-mode="push"
    :sidebar-collapsed-by-default="false"
    :sidebar-expand-on-hover="false"
  >
    <template #sidebar-menu>
      <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
    </template>

    <div>
      <!-- Content adapts to sidebar width changes -->
    </div>
  </AdminLayout>
</template>
```

### Right-Side Sidebar

```vue
<template>
  <AdminLayout
    title="Dashboard"
    sidebar-position="right"
  >
    <template #sidebar-menu>
      <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
    </template>

    <div>
      <!-- Sidebar appears on the right -->
    </div>
  </AdminLayout>
</template>
```

### Top Navigation Bar

```vue
<template>
  <AdminLayout
    title="Dashboard"
    sidebar-position="top"
  >
    <template #sidebar-menu>
      <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
      <SidebarItem href="/users" icon="users" label="Users" />
    </template>

    <div>
      <!-- Horizontal navigation at top, navbar is hidden -->
    </div>
  </AdminLayout>
</template>
```

### With Header Actions

```vue
<template>
  <AdminLayout title="Products">
    <template #sidebar-menu>
      <SidebarItem href="/products" icon="box" label="Products" />
    </template>

    <template #header-actions>
      <button class="btn btn-primary">
        Add Product
      </button>
    </template>

    <div>
      <!-- Products list -->
    </div>
  </AdminLayout>
</template>
```

### Custom User Menu

```vue
<template>
  <AdminLayout title="Dashboard">
    <template #sidebar-menu>
      <!-- Menu items -->
    </template>

    <template #user-menu>
      <div class="py-1">
        <a href="/profile" class="block px-4 py-2 hover:bg-gray-100">
          My Profile
        </a>
        <a href="/settings" class="block px-4 py-2 hover:bg-gray-100">
          Settings
        </a>
        <button @click="logout" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
          Logout
        </button>
      </div>
    </template>

    <div>
      <!-- Content -->
    </div>
  </AdminLayout>
</template>
```

## Configuration Options

### Sidebar Modes

| Mode | Description |
|------|-------------|
| `overlay` | Sidebar overlays content when expanded. Content width remains constant. |
| `push` | Content area adjusts its margin based on sidebar width. |

### Sidebar Positions

| Position | Description |
|----------|-------------|
| `left` | Traditional left sidebar (default) |
| `right` | Sidebar on the right side of the screen |
| `top` | Horizontal navigation bar at the top (replaces navbar) |

### Flash Messages

AdminLayout automatically displays flash messages from Inertia.js page props:

```php
// In your Laravel controller
return redirect()->route('dashboard')
    ->with('success', 'Operation completed successfully');

// Or for errors
return back()->with('error', 'Something went wrong');
```

The component will automatically render success messages in green and error messages in red.

## Provided Injections

AdminLayout provides the following values via Vue's `provide/inject`:

| Key | Type | Description |
|-----|------|-------------|
| `sidebarCollapsed` | `Ref<Boolean>` | Current collapsed state of the sidebar |
| `sidebarMobileOpen` | `Ref<Boolean>` | Whether mobile sidebar is open |
| `currentUser` | `Ref<Object>` | Current authenticated user from `page.props.auth.user` |

## Playground

Try the AdminLayout component:

<LiveDemo>
  <div style="border: 2px solid #e5e7eb; border-radius: 8px; overflow: hidden; height: 300px;">
    <div style="display: flex; height: 100%;">
      <div style="width: 64px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 12px 8px; display: flex; flex-direction: column; align-items: center; gap: 16px;">
        <div style="font-weight: bold; font-size: 14px;">CA</div>
        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
        </div>
        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
        </div>
      </div>
      <div style="flex: 1; display: flex; flex-direction: column;">
        <div style="height: 48px; background: white; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; padding: 0 16px; justify-content: space-between;">
          <span style="font-weight: 600; color: #111827;">Dashboard</span>
          <div style="width: 32px; height: 32px; background: #e5e7eb; border-radius: 50%;"></div>
        </div>
        <div style="flex: 1; background: #f9fafb; padding: 16px;">
          <div style="background: white; border-radius: 8px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="color: #6b7280; margin: 0;">Page content goes here</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <AdminLayout title="Dashboard">
    <template #sidebar-menu>
      <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
      <SidebarItem href="/users" icon="users" label="Users" />
    </template>

    <div class="bg-white rounded-lg p-4 shadow">
      <p>Page content goes here</p>
    </div>
  </AdminLayout>
</template>
```

  </template>
</LiveDemo>
