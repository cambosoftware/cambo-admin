# Dropdown

A toggleable menu for displaying a list of actions or options.

## Import

```vue
<script setup>
import Dropdown from '@/Components/Overlays/Dropdown.vue'
import DropdownItem from '@/Components/Overlays/DropdownItem.vue'
import DropdownDivider from '@/Components/Overlays/DropdownDivider.vue'
</script>
```

## Basic Usage

```vue
<template>
  <Dropdown>
    <template #trigger>
      <Button>Options</Button>
    </template>

    <DropdownItem @click="edit">Edit</DropdownItem>
    <DropdownItem @click="duplicate">Duplicate</DropdownItem>
    <DropdownDivider />
    <DropdownItem @click="remove" variant="danger">Delete</DropdownItem>
  </Dropdown>
</template>
```

## Props

### Dropdown

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `align` | `string` | `'left'` | Alignment: `left`, `right` |
| `width` | `string` | `'48'` | Width class: `48`, `56`, `64`, `72`, `full` |
| `closeOnClick` | `boolean` | `true` | Close when item clicked |

### DropdownItem

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `href` | `string` | `null` | Link URL (renders as `<a>`) |
| `as` | `string` | `'button'` | Element type |
| `variant` | `string` | `'default'` | Style: `default`, `danger` |
| `disabled` | `boolean` | `false` | Disable the item |
| `icon` | `Component` | `null` | Icon component |

## Slots

### Dropdown Slots

| Slot | Description |
|------|-------------|
| `trigger` | Trigger element (button) |
| `default` | Dropdown content (items) |
| `header` | Header content above items |
| `footer` | Footer content below items |

## Examples

### With Icons

```vue
<script setup>
import {
  PencilIcon,
  DocumentDuplicateIcon,
  ArchiveBoxIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'
</script>

<template>
  <Dropdown>
    <template #trigger>
      <IconButton :icon="EllipsisVerticalIcon" variant="ghost" />
    </template>

    <DropdownItem :icon="PencilIcon" @click="edit">Edit</DropdownItem>
    <DropdownItem :icon="DocumentDuplicateIcon" @click="duplicate">
      Duplicate
    </DropdownItem>
    <DropdownItem :icon="ArchiveBoxIcon" @click="archive">Archive</DropdownItem>
    <DropdownDivider />
    <DropdownItem :icon="TrashIcon" variant="danger" @click="remove">
      Delete
    </DropdownItem>
  </Dropdown>
</template>
```

### User Menu

```vue
<Dropdown align="right" width="56">
  <template #trigger>
    <button class="flex items-center gap-2">
      <Avatar :src="user.avatar" size="sm" />
      <span>{{ user.name }}</span>
      <ChevronDownIcon class="w-4 h-4" />
    </button>
  </template>

  <template #header>
    <div class="px-4 py-3">
      <p class="text-sm font-medium">{{ user.name }}</p>
      <p class="text-xs text-gray-500">{{ user.email }}</p>
    </div>
  </template>

  <DropdownItem href="/profile">Profile</DropdownItem>
  <DropdownItem href="/settings">Settings</DropdownItem>
  <DropdownDivider />
  <DropdownItem @click="logout">Sign Out</DropdownItem>
</Dropdown>
```

### With Links

```vue
<Dropdown>
  <template #trigger>
    <Button>Navigation</Button>
  </template>

  <DropdownItem href="/dashboard">Dashboard</DropdownItem>
  <DropdownItem href="/products">Products</DropdownItem>
  <DropdownItem href="/orders">Orders</DropdownItem>
  <DropdownItem href="/customers">Customers</DropdownItem>
</Dropdown>
```

### Disabled Items

```vue
<Dropdown>
  <template #trigger>
    <Button>Actions</Button>
  </template>

  <DropdownItem @click="edit">Edit</DropdownItem>
  <DropdownItem disabled>Duplicate (Premium)</DropdownItem>
  <DropdownItem @click="share">Share</DropdownItem>
</Dropdown>
```

### With Checkbox/Selection

```vue
<Dropdown :close-on-click="false">
  <template #trigger>
    <Button>Select Columns</Button>
  </template>

  <div class="p-2">
    <Checkbox v-model="columns.name" label="Name" />
    <Checkbox v-model="columns.email" label="Email" />
    <Checkbox v-model="columns.role" label="Role" />
    <Checkbox v-model="columns.status" label="Status" />
    <Checkbox v-model="columns.createdAt" label="Created At" />
  </div>
</Dropdown>
```

## Keyboard Navigation

- `Enter` / `Space` - Open dropdown or select item
- `Escape` - Close dropdown
- `Arrow Down` - Move to next item
- `Arrow Up` - Move to previous item
- `Home` - Move to first item
- `End` - Move to last item

## Accessibility

- Proper ARIA attributes for menu behavior
- Keyboard navigation support
- Focus management
- Screen reader announcements

## Playground

Try the dropdown component:

<LiveDemo>
  <DemoDropdown />

  <template #code>

```vue
<Dropdown>
  <template #trigger>
    <Button>Options</Button>
  </template>

  <DropdownItem @click="edit">Edit</DropdownItem>
  <DropdownItem @click="duplicate">Duplicate</DropdownItem>
  <DropdownDivider />
  <DropdownItem @click="remove" variant="danger">Delete</DropdownItem>
</Dropdown>
```

  </template>
</LiveDemo>
