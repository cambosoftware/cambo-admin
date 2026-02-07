# CommandPalette

A searchable command palette (similar to Spotlight, VS Code's Command Palette, or Linear's command menu) for quick navigation and actions.

## Import

```vue
<script setup>
import { CommandPalette } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `groups` | `Array` | `[]` | Array of command groups |
| `placeholder` | `String` | `'Rechercher une commande...'` | Search input placeholder |
| `showRecent` | `Boolean` | `true` | Show recently used commands |
| `maxRecent` | `Number` | `5` | Maximum number of recent items to display |

## Group Structure

```typescript
interface CommandGroup {
    name: string;           // Group header label
    items: CommandItem[];   // Array of commands
}

interface CommandItem {
    id: string | number;    // Unique identifier
    label: string;          // Display text
    description?: string;   // Optional description
    icon?: Component;       // Optional icon component
    shortcut?: string;      // Keyboard shortcut hint (e.g., "Ctrl+N")
    action?: Function;      // Function to execute on selection
    href?: string;          // URL to navigate to (if no action)
    keywords?: string[];    // Additional search keywords
}
```

## Slots

This component does not have slots.

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `select` | `CommandItem` | Emitted when an item is selected |
| `close` | - | Emitted when the palette is closed |

## Exposed Methods

| Method | Description |
|--------|-------------|
| `open()` | Opens the command palette |
| `close()` | Closes the command palette |

## Keyboard Shortcuts

| Key | Action |
|-----|--------|
| `Cmd+K` / `Ctrl+K` | Open command palette (global) |
| `Escape` | Close command palette |
| `Arrow Up/Down` | Navigate items |
| `Enter` | Select current item |

## Examples

### Basic Setup

```vue
<template>
    <CommandPalette
        ref="commandPalette"
        :groups="commandGroups"
        @select="handleSelect"
    />
</template>

<script setup>
import { ref } from 'vue'
import { HomeIcon, UsersIcon, CogIcon } from '@heroicons/vue/24/outline'

const commandPalette = ref(null)

const commandGroups = [
    {
        name: 'Navigation',
        items: [
            { id: 'dashboard', label: 'Dashboard', icon: HomeIcon, href: '/dashboard' },
            { id: 'users', label: 'Users', icon: UsersIcon, href: '/users' },
            { id: 'settings', label: 'Settings', icon: CogIcon, href: '/settings' }
        ]
    }
]

const handleSelect = (item) => {
    console.log('Selected:', item)
}
</script>
```

### With Actions

```vue
<template>
    <CommandPalette :groups="commandGroups" />
</template>

<script setup>
import { PlusIcon, DocumentDuplicateIcon, TrashIcon } from '@heroicons/vue/24/outline'

const commandGroups = [
    {
        name: 'Actions',
        items: [
            {
                id: 'new-project',
                label: 'New Project',
                description: 'Create a new project',
                icon: PlusIcon,
                shortcut: 'Ctrl+N',
                action: () => openNewProjectModal()
            },
            {
                id: 'duplicate',
                label: 'Duplicate',
                description: 'Duplicate selected item',
                icon: DocumentDuplicateIcon,
                shortcut: 'Ctrl+D',
                action: () => duplicateSelected()
            },
            {
                id: 'delete',
                label: 'Delete',
                description: 'Delete selected item',
                icon: TrashIcon,
                shortcut: 'Del',
                action: () => deleteSelected()
            }
        ]
    }
]
</script>
```

### Multiple Groups

```vue
<template>
    <CommandPalette :groups="commandGroups" />
</template>

<script setup>
const commandGroups = [
    {
        name: 'Pages',
        items: [
            { id: 'home', label: 'Home', href: '/', keywords: ['main', 'start'] },
            { id: 'dashboard', label: 'Dashboard', href: '/dashboard' },
            { id: 'analytics', label: 'Analytics', href: '/analytics' }
        ]
    },
    {
        name: 'Users',
        items: [
            { id: 'users-list', label: 'All Users', href: '/users' },
            { id: 'users-new', label: 'Create User', href: '/users/create' },
            { id: 'users-roles', label: 'Manage Roles', href: '/users/roles' }
        ]
    },
    {
        name: 'Settings',
        items: [
            { id: 'settings-general', label: 'General Settings', href: '/settings' },
            { id: 'settings-billing', label: 'Billing', href: '/settings/billing' },
            { id: 'settings-team', label: 'Team Settings', href: '/settings/team' }
        ]
    }
]
</script>
```

### With Keywords for Better Search

```vue
<template>
    <CommandPalette :groups="commandGroups" />
</template>

<script setup>
const commandGroups = [
    {
        name: 'Navigation',
        items: [
            {
                id: 'dashboard',
                label: 'Dashboard',
                description: 'View your dashboard',
                href: '/dashboard',
                keywords: ['home', 'overview', 'main', 'start']
            },
            {
                id: 'invoices',
                label: 'Invoices',
                description: 'Manage invoices',
                href: '/invoices',
                keywords: ['billing', 'payments', 'money', 'receipts']
            },
            {
                id: 'customers',
                label: 'Customers',
                description: 'Customer management',
                href: '/customers',
                keywords: ['clients', 'users', 'contacts', 'people']
            }
        ]
    }
]
</script>
```

### Programmatic Opening

```vue
<template>
    <div>
        <Button @click="openPalette">
            <MagnifyingGlassIcon class="h-4 w-4 mr-2" />
            Search (Cmd+K)
        </Button>

        <CommandPalette
            ref="palette"
            :groups="groups"
        />
    </div>
</template>

<script setup>
import { ref } from 'vue'

const palette = ref(null)

const openPalette = () => {
    palette.value.open()
}
</script>
```

### Without Recent Items

```vue
<template>
    <CommandPalette
        :groups="commandGroups"
        :showRecent="false"
    />
</template>
```

### Custom Placeholder

```vue
<template>
    <CommandPalette
        :groups="commandGroups"
        placeholder="Type to search commands..."
    />
</template>
```

### Application Command Palette

```vue
<template>
    <CommandPalette :groups="appCommands" />
</template>

<script setup>
import { useRouter } from 'vue-router'
import {
    HomeIcon,
    UsersIcon,
    FolderIcon,
    CalendarIcon,
    CogIcon,
    QuestionMarkCircleIcon,
    ArrowRightOnRectangleIcon,
    MoonIcon,
    SunIcon
} from '@heroicons/vue/24/outline'

const router = useRouter()

const appCommands = [
    {
        name: 'Navigation',
        items: [
            { id: 'nav-home', label: 'Go to Dashboard', icon: HomeIcon, href: '/dashboard' },
            { id: 'nav-users', label: 'Go to Users', icon: UsersIcon, href: '/users' },
            { id: 'nav-projects', label: 'Go to Projects', icon: FolderIcon, href: '/projects' },
            { id: 'nav-calendar', label: 'Go to Calendar', icon: CalendarIcon, href: '/calendar' },
            { id: 'nav-settings', label: 'Go to Settings', icon: CogIcon, href: '/settings' }
        ]
    },
    {
        name: 'Actions',
        items: [
            {
                id: 'action-new-project',
                label: 'Create New Project',
                description: 'Start a new project',
                shortcut: 'Ctrl+Shift+P',
                action: () => router.push('/projects/create')
            },
            {
                id: 'action-new-user',
                label: 'Invite Team Member',
                description: 'Send an invitation',
                action: () => openInviteModal()
            }
        ]
    },
    {
        name: 'Preferences',
        items: [
            {
                id: 'pref-dark',
                label: 'Toggle Dark Mode',
                icon: MoonIcon,
                shortcut: 'Ctrl+Shift+D',
                action: () => toggleDarkMode()
            }
        ]
    },
    {
        name: 'Help',
        items: [
            { id: 'help-docs', label: 'Documentation', icon: QuestionMarkCircleIcon, href: '/docs' },
            {
                id: 'help-logout',
                label: 'Sign Out',
                icon: ArrowRightOnRectangleIcon,
                action: () => logout()
            }
        ]
    }
]
</script>
```

## Styling

The CommandPalette uses:

- Overlay: `bg-black/50 dark:bg-black/70`
- Container: `max-w-xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl`
- Search input: Full width with search icon
- Group headers: `text-xs font-semibold text-gray-500 uppercase`
- Items: Hover and selected states with primary color
- Footer: Keyboard navigation hints

## Storage

Recent items are stored in `localStorage` under the key `cambo-command-palette-recent`.

## Accessibility

- Modal is rendered via `<Teleport>` to body
- Proper focus management (input focused on open)
- Keyboard navigation with arrow keys
- ESC key closes the palette
- Body scroll is locked when open
- Screen reader friendly with semantic structure

## Playground

Try the CommandPalette component:

<LiveDemo>
  <div style="max-width: 500px; background: white; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; border: 1px solid #e5e7eb;">
    <div style="padding: 12px 16px; border-bottom: 1px solid #e5e7eb;">
      <div style="display: flex; align-items: center; gap: 12px; background: #f9fafb; border-radius: 8px; padding: 10px 12px;">
        <svg width="20" height="20" fill="none" stroke="#9ca3af" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <span style="color: #9ca3af; font-size: 14px;">Search commands...</span>
      </div>
    </div>
    <div style="padding: 8px;">
      <p style="font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; padding: 8px 12px;">Navigation</p>
      <div style="padding: 8px 12px; background: #eef2ff; border-radius: 6px; display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
        <svg width="16" height="16" fill="#4f46e5" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
        <span style="color: #4f46e5; font-size: 14px;">Dashboard</span>
      </div>
      <div style="padding: 8px 12px; display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
        <svg width="16" height="16" fill="#6b7280" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
        <span style="color: #374151; font-size: 14px;">Users</span>
      </div>
      <div style="padding: 8px 12px; display: flex; align-items: center; gap: 12px;">
        <svg width="16" height="16" fill="#6b7280" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
        <span style="color: #374151; font-size: 14px;">Settings</span>
      </div>
    </div>
    <div style="padding: 8px 16px; background: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; gap: 16px;">
      <span style="font-size: 12px; color: #6b7280;"><kbd style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #d1d5db; font-size: 11px;">↑↓</kbd> Navigate</span>
      <span style="font-size: 12px; color: #6b7280;"><kbd style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #d1d5db; font-size: 11px;">↵</kbd> Select</span>
      <span style="font-size: 12px; color: #6b7280;"><kbd style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #d1d5db; font-size: 11px;">esc</kbd> Close</span>
    </div>
  </div>

  <template #code>

```vue
<template>
  <CommandPalette
    ref="commandPalette"
    :groups="[
      {
        name: 'Navigation',
        items: [
          { id: 'dashboard', label: 'Dashboard', icon: HomeIcon, href: '/dashboard' },
          { id: 'users', label: 'Users', icon: UsersIcon, href: '/users' },
          { id: 'settings', label: 'Settings', icon: CogIcon, href: '/settings' }
        ]
      }
    ]"
    placeholder="Search commands..."
  />
</template>
```

  </template>
</LiveDemo>
