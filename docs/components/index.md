# UI Components

CamboAdmin provides a comprehensive library of **Vue 3 components** ready to use, built with Tailwind CSS.

## Categories

### Basic UI

- [Button](./ui/button.md) - Buttons with multiple variants
- [ButtonGroup](./ui/button-group.md) - Grouped buttons
- [IconButton](./ui/icon-button.md) - Icon-only buttons
- [Badge](./ui/badge.md) - Badges and labels
- [Avatar](./ui/avatar.md) - User avatars
- [AvatarGroup](./ui/avatar-group.md) - Grouped avatars display
- [Icon](./ui/icon.md) - Icon wrapper component
- [Spinner](./ui/spinner.md) - Loading indicators
- [Skeleton](./ui/skeleton.md) - Loading placeholders
- [Tooltip](./ui/tooltip.md) - Hover tooltips
- [Divider](./ui/divider.md) - Visual separators
- [AppLink](./ui/app-link.md) - Styled navigation links

### Forms

- [Form](/components/forms/) - Form container
- [Input](/components/forms/) - Text fields
- [Textarea](/components/forms/) - Text areas
- [Select](/components/forms/) - Dropdown lists
- [Checkbox](/components/forms/) - Checkboxes
- [Radio](/components/forms/) - Radio buttons
- [Switch](/components/forms/) - Toggle switches
- [FileInput](/components/forms/) - File uploads

### Navigation

- [Tabs](/components/layout/) - Tab navigation
- [Breadcrumb](/components/layout/) - Breadcrumb navigation
- [Pagination](/components/data/) - Pagination controls
- [Dropdown](/components/overlays/) - Dropdown menus

### Feedback

- [Alert](/components/feedback/) - Alert messages
- [Toast](/components/feedback/) - Toast notifications
- [Modal](/components/overlays/) - Modal dialogs
- [Drawer](/components/overlays/) - Side drawers
- [Progress](/components/feedback/) - Progress bars

### Data Display

- [Table](/components/data/) - Data tables
- [Card](/components/layout/) - Card containers
- [Accordion](/components/layout/) - Collapsible sections
- [Stats](/components/data/) - Statistics display

## Installation

Components are automatically available after installing CamboAdmin. Import them directly:

```vue
<script setup>
import Button from '@/Components/UI/Button.vue'
import Input from '@/Components/Form/Input.vue'
import DataTable from '@/Components/DataTable/DataTable.vue'
</script>
```

## Conventions

### Naming

- Components follow PascalCase convention
- Props use kebab-case in templates
- Events use kebab-case

```vue
<template>
    <Button
        variant="primary"
        :icon="PlusIcon"
        @click="handleClick"
    >
        Add Item
    </Button>
</template>
```

### Slots

Most components support slots for customization:

```vue
<Card>
    <template #header>
        <h3>Title</h3>
    </template>

    <template #default>
        Main content
    </template>

    <template #footer>
        <Button>Action</Button>
    </template>
</Card>
```

### Icons

Icons use [Heroicons](https://heroicons.com/):

```vue
<script setup>
import { UserIcon, PlusIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <Icon :icon="UserIcon" class="w-5 h-5" />
    <Button :icon="PlusIcon">Add</Button>
</template>
```

## Theming

Components use Tailwind CSS with custom color configuration:

```javascript
// tailwind.config.js
module.exports = {
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#eff6ff',
                    // ... color scale
                    600: '#2563eb',
                    700: '#1d4ed8',
                }
            }
        }
    }
}
```

Modify these colors to customize the appearance of all components.

## Dark Mode

All components include dark mode support. Dark mode is automatically applied based on user system preferences or can be toggled manually using the theme system.

## TypeScript

All components are TypeScript compatible with exported types:

```typescript
import type { ButtonProps } from '@/Components/UI/Button.vue'
import type { InputProps } from '@/Components/Form/Input.vue'
```

## Quick Links

| Category | Components |
|----------|------------|
| [Basic UI](./ui/) | Button, Badge, Avatar, Icon, Spinner, Skeleton, Tooltip, Divider |
| [Forms](./forms/) | Input, Select, Checkbox, Radio, Switch, FileInput |
| [Feedback](./feedback/) | Alert, Toast, Progress |
| [Data](./data/) | Table, Stats, Pagination |
| [Layout](./layout/) | Card, Tabs, Accordion, Breadcrumb |
| [Overlays](./overlays/) | Modal, Drawer, Dropdown |
