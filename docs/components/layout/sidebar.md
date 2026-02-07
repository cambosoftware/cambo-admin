# Sidebar

A versatile sidebar navigation component with support for multiple themes, positions, and collapse behaviors.

## Import

```vue
<script setup>
import { Sidebar } from 'cambo-admin'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `collapsed` | `Boolean` | `false` | Whether the sidebar is in collapsed state |
| `mobileOpen` | `Boolean` | `false` | Whether the mobile sidebar is visible |
| `theme` | `String` | `'colorful'` | Visual theme: `'dark'`, `'light'`, or `'colorful'` |
| `logo` | `String` | `null` | URL for the full logo image |
| `logoCollapsed` | `String` | `null` | URL for the collapsed state logo (typically a smaller/icon version) |
| `title` | `String` | `'CamboAdmin'` | Application title shown in the sidebar header |
| `collapsible` | `Boolean` | `true` | Whether the sidebar can be collapsed |
| `expandOnHover` | `Boolean` | `false` | When `true`, sidebar expands on mouse hover |
| `mode` | `String` | `'overlay'` | Behavior mode: `'overlay'` or `'push'` |
| `accordion` | `Boolean` | `true` | When `true`, only one submenu can be open at a time |
| `position` | `String` | `'left'` | Position: `'left'`, `'right'`, or `'top'` |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `toggle` | - | Emitted when the collapse toggle button is clicked |
| `close-mobile` | - | Emitted when the mobile sidebar close button is clicked |
| `visual-state-change` | `Boolean` | Emitted when the visual collapsed state changes (includes hover states) |

## Slots

| Name | Description |
|------|-------------|
| `header` | Custom header content (replaces the title text) |
| `menu` | Main navigation menu content |
| `footer` | Footer content at the bottom of the sidebar |

## Usage

### Basic Usage

```vue
<template>
  <Sidebar
    :collapsed="isCollapsed"
    title="My Admin"
    @toggle="isCollapsed = !isCollapsed"
  >
    <template #menu>
      <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
      <SidebarItem href="/users" icon="users" label="Users" />
    </template>
  </Sidebar>
</template>

<script setup>
import { ref } from 'vue'

const isCollapsed = ref(false)
</script>
```

### With Different Themes

```vue
<!-- Dark theme -->
<Sidebar theme="dark" title="Dark Theme">
  <template #menu>
    <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
  </template>
</Sidebar>

<!-- Light theme -->
<Sidebar theme="light" title="Light Theme">
  <template #menu>
    <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
  </template>
</Sidebar>

<!-- Colorful theme (default) -->
<Sidebar theme="colorful" title="Colorful Theme">
  <template #menu>
    <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
  </template>
</Sidebar>
```

### With Logo

```vue
<Sidebar
  :collapsed="isCollapsed"
  title="My App"
  logo="/images/logo-full.png"
  logo-collapsed="/images/logo-icon.png"
>
  <template #menu>
    <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
  </template>
</Sidebar>
```

### Expand on Hover

```vue
<Sidebar
  :collapsed="true"
  :expand-on-hover="true"
  title="Hover Me"
>
  <template #menu>
    <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
  </template>
</Sidebar>
```

### Right Position

```vue
<Sidebar
  :collapsed="isCollapsed"
  position="right"
  title="Right Sidebar"
>
  <template #menu>
    <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
  </template>
</Sidebar>
```

### Top Position (Horizontal)

```vue
<Sidebar
  position="top"
  title="My App"
>
  <template #menu>
    <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
    <SidebarItem href="/users" icon="users" label="Users" />
  </template>

  <template #footer>
    <UserDropdown />
  </template>
</Sidebar>
```

### With Footer

```vue
<Sidebar :collapsed="isCollapsed" title="Admin">
  <template #menu>
    <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
  </template>

  <template #footer>
    <div class="flex items-center gap-3 text-white">
      <img src="/avatar.jpg" class="h-8 w-8 rounded-full" />
      <span v-if="!isCollapsed">John Doe</span>
    </div>
  </template>
</Sidebar>
```

### Accordion Submenu

```vue
<Sidebar :accordion="true" title="Admin">
  <template #menu>
    <SidebarItem href="/dashboard" icon="home" label="Dashboard" />

    <SidebarItem icon="settings" label="Settings">
      <SidebarItem href="/settings/general" label="General" />
      <SidebarItem href="/settings/security" label="Security" />
    </SidebarItem>

    <SidebarItem icon="users" label="Users">
      <SidebarItem href="/users/list" label="All Users" />
      <SidebarItem href="/users/roles" label="Roles" />
    </SidebarItem>
  </template>
</Sidebar>
```

## Configuration Options

### Theme Classes

| Theme | Background | Text | Hover |
|-------|------------|------|-------|
| `colorful` | `bg-indigo-600` | `text-white` | `hover:bg-indigo-500` |
| `dark` | `bg-gray-900` | `text-white` | `hover:bg-gray-800` |
| `light` | `bg-white` | `text-gray-900` | `hover:bg-gray-100` |

### Width Values

| State | Width |
|-------|-------|
| Expanded | `w-64` (256px) |
| Collapsed | `w-16` (64px) |

### Mode Behaviors

| Mode | Behavior |
|------|----------|
| `overlay` | Sidebar overlays content, adds shadow when expanded via hover |
| `push` | Content margin adjusts based on sidebar width |

## Provided Injections

The Sidebar component provides the following values to child components:

| Key | Type | Description |
|-----|------|-------------|
| `sidebarCollapsed` | `Ref<Boolean>` | Visual collapsed state (respects hover expansion) |
| `sidebarTheme` | `Ref<String>` | Current theme value |
| `sidebarPosition` | `Ref<String>` | Current position value |
| `sidebarAccordion` | `Ref<Boolean>` | Whether accordion mode is enabled |
| `sidebarActiveSubmenu` | `Ref<String>` | ID of currently open submenu |
| `setSidebarActiveSubmenu` | `Function` | Function to set active submenu |

## Mobile Behavior

- On mobile devices (< 1024px), the sidebar is hidden by default
- Use the `mobileOpen` prop to control visibility
- The sidebar appears as a full-height overlay with a close button
- Mobile menu items always appear expanded (never collapsed)

## Playground

Try the Sidebar component:

<LiveDemo>
  <div style="display: flex; gap: 16px;">
    <div style="width: 200px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border-radius: 8px; padding: 16px; color: white;">
      <div style="font-weight: bold; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.2);">CamboAdmin</div>
      <div style="display: flex; flex-direction: column; gap: 4px;">
        <div style="padding: 8px 12px; background: rgba(255,255,255,0.2); border-radius: 6px; display: flex; align-items: center; gap: 8px;">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
          Dashboard
        </div>
        <div style="padding: 8px 12px; border-radius: 6px; display: flex; align-items: center; gap: 8px; opacity: 0.8;">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
          Users
        </div>
        <div style="padding: 8px 12px; border-radius: 6px; display: flex; align-items: center; gap: 8px; opacity: 0.8;">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
          Settings
        </div>
      </div>
    </div>
    <div style="width: 64px; background: #1f2937; border-radius: 8px; padding: 12px 8px; color: white; display: flex; flex-direction: column; align-items: center; gap: 16px;">
      <div style="font-weight: bold; font-size: 12px;">CA</div>
      <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
      </div>
      <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <Sidebar
    :collapsed="isCollapsed"
    title="CamboAdmin"
    theme="colorful"
    @toggle="isCollapsed = !isCollapsed"
  >
    <template #menu>
      <SidebarItem href="/dashboard" icon="home" label="Dashboard" />
      <SidebarItem href="/users" icon="users" label="Users" />
      <SidebarItem href="/settings" icon="cog" label="Settings" />
    </template>
  </Sidebar>
</template>

<script setup>
import { ref } from 'vue'
const isCollapsed = ref(false)
</script>
```

  </template>
</LiveDemo>
