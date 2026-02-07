# UI Components

Core UI components for building interfaces.

## Components

| Component | Description |
|-----------|-------------|
| [Button](./button.md) | Primary action button with variants |
| [ButtonGroup](./button-group.md) | Group of related buttons |
| [IconButton](./icon-button.md) | Button with icon only |
| [Badge](./badge.md) | Status indicators and labels |
| [Avatar](./avatar.md) | User profile images |
| [AvatarGroup](./avatar-group.md) | Stacked avatar display |
| [Icon](./icon.md) | Icon wrapper component |
| [Spinner](./spinner.md) | Loading indicator |
| [Skeleton](./skeleton.md) | Content placeholder |
| [Tooltip](./tooltip.md) | Hover information |
| [Divider](./divider.md) | Visual separator |
| [AppLink](./app-link.md) | Smart link component |

## Usage

UI components are the building blocks of your interface. Import them from `@/Components/UI/`:

```vue
<script setup>
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import Avatar from '@/Components/UI/Avatar.vue'
</script>

<template>
  <div class="flex items-center gap-4">
    <Avatar src="/avatar.jpg" alt="John Doe" />
    <div>
      <span>John Doe</span>
      <Badge variant="success">Active</Badge>
    </div>
    <Button variant="primary">Edit Profile</Button>
  </div>
</template>
```
