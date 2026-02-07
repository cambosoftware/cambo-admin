# DescriptionList

A component for displaying key-value pairs in a structured format, commonly used for showing details or metadata.

## Import

```js
import { DescriptionList } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `items` | `Array` | `[]` | Array of items: `[{ label: string, value: any, key?: string }]` |
| `columns` | `Number` | `1` | Number of columns: `1`, `2`, `3`, or `4` |
| `striped` | `Boolean` | `false` | Apply alternating background colors |
| `bordered` | `Boolean` | `false` | Add border and rounded corners |
| `horizontal` | `Boolean` | `false` | Display label and value side by side |
| `compact` | `Boolean` | `false` | Use compact padding |

## Slots

| Slot | Props | Description |
|------|-------|-------------|
| `default` | - | Custom content (when items prop not used) |
| `item-{key}` | `{ item }` | Custom value content for specific item |

## Basic Example

```vue
<template>
  <DescriptionList :items="details" />
</template>

<script setup>
import { DescriptionList } from '@cambosoftware/cambo-admin'

const details = [
  { label: 'Full Name', value: 'John Doe' },
  { label: 'Email', value: 'john@example.com' },
  { label: 'Phone', value: '+1 (555) 123-4567' },
  { label: 'Location', value: 'New York, USA' },
]
</script>
```

## Horizontal Layout

```vue
<template>
  <DescriptionList :items="details" horizontal />
</template>

<script setup>
const details = [
  { label: 'Name', value: 'John Doe' },
  { label: 'Email', value: 'john@example.com' },
  { label: 'Status', value: 'Active' },
]
</script>
```

## Multi-Column Layout

```vue
<template>
  <DescriptionList :items="userInfo" :columns="2" bordered />
</template>

<script setup>
const userInfo = [
  { label: 'First Name', value: 'John' },
  { label: 'Last Name', value: 'Doe' },
  { label: 'Email', value: 'john@example.com' },
  { label: 'Phone', value: '+1 555-123-4567' },
  { label: 'Department', value: 'Engineering' },
  { label: 'Role', value: 'Senior Developer' },
]
</script>
```

## Striped Style

```vue
<template>
  <DescriptionList :items="details" striped bordered />
</template>
```

## With Custom Value Rendering

```vue
<template>
  <DescriptionList :items="orderDetails">
    <template #item-status="{ item }">
      <Badge :variant="item.value === 'Delivered' ? 'success' : 'warning'">
        {{ item.value }}
      </Badge>
    </template>

    <template #item-total="{ item }">
      <span class="font-semibold text-gray-900">{{ item.value }}</span>
    </template>
  </DescriptionList>
</template>

<script setup>
const orderDetails = [
  { key: 'order_id', label: 'Order ID', value: '#ORD-12345' },
  { key: 'date', label: 'Date', value: 'Feb 5, 2024' },
  { key: 'status', label: 'Status', value: 'Delivered' },
  { key: 'total', label: 'Total', value: '$299.00' },
]
</script>
```

## Compact Version

```vue
<template>
  <DescriptionList :items="details" compact bordered />
</template>
```

## Three Column Layout

```vue
<template>
  <DescriptionList :items="stats" :columns="3" />
</template>

<script setup>
const stats = [
  { label: 'Total Users', value: '1,234' },
  { label: 'Active Today', value: '456' },
  { label: 'Revenue', value: '$12,345' },
  { label: 'Orders', value: '789' },
  { label: 'Products', value: '234' },
  { label: 'Reviews', value: '567' },
]
</script>
```

## With Custom Content via Slot

```vue
<template>
  <DescriptionList bordered>
    <div class="px-4 py-4">
      <dt class="text-sm font-medium text-gray-500">Profile Picture</dt>
      <dd class="mt-1">
        <img
          src="/avatar.jpg"
          class="h-16 w-16 rounded-full"
          alt="Profile"
        />
      </dd>
    </div>
    <div class="px-4 py-4 bg-gray-50">
      <dt class="text-sm font-medium text-gray-500">Bio</dt>
      <dd class="mt-1 text-sm text-gray-900">
        Software developer with 10+ years of experience in web development.
      </dd>
    </div>
    <div class="px-4 py-4">
      <dt class="text-sm font-medium text-gray-500">Social Links</dt>
      <dd class="mt-1 flex gap-2">
        <a href="#" class="text-gray-400 hover:text-gray-600">
          <TwitterIcon class="h-5 w-5" />
        </a>
        <a href="#" class="text-gray-400 hover:text-gray-600">
          <LinkedInIcon class="h-5 w-5" />
        </a>
        <a href="#" class="text-gray-400 hover:text-gray-600">
          <GitHubIcon class="h-5 w-5" />
        </a>
      </dd>
    </div>
  </DescriptionList>
</template>
```

## Product Details Example

```vue
<template>
  <Card title="Product Details">
    <DescriptionList :items="product" :columns="2" horizontal />
  </Card>
</template>

<script setup>
const product = [
  { label: 'SKU', value: 'PRD-001234' },
  { label: 'Category', value: 'Electronics' },
  { label: 'Price', value: '$299.99' },
  { label: 'Stock', value: '45 units' },
  { label: 'Weight', value: '1.5 kg' },
  { label: 'Dimensions', value: '30 x 20 x 10 cm' },
]
</script>
```

## Responsive Columns

```vue
<template>
  <!-- 1 column on mobile, 2 on tablet, 4 on desktop -->
  <DescriptionList :items="details" :columns="4" bordered />
</template>

<!-- The component automatically handles responsive breakpoints:
  - 1 column: always available
  - 2 columns: sm breakpoint and up
  - 3 columns: lg breakpoint and up
  - 4 columns: lg breakpoint and up
-->
```

## Playground

Try the DescriptionList component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
    <div style="display: grid; grid-template-columns: 1fr 1fr;">
      <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
        <dt style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Full Name</dt>
        <dd style="font-size: 14px; font-weight: 500;">John Doe</dd>
      </div>
      <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0;">
        <dt style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Email</dt>
        <dd style="font-size: 14px; font-weight: 500;">john@example.com</dd>
      </div>
      <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
        <dt style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Phone</dt>
        <dd style="font-size: 14px; font-weight: 500;">+1 (555) 123-4567</dd>
      </div>
      <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0;">
        <dt style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Location</dt>
        <dd style="font-size: 14px; font-weight: 500;">New York, USA</dd>
      </div>
      <div style="padding: 12px 16px; border-right: 1px solid #e2e8f0;">
        <dt style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Department</dt>
        <dd style="font-size: 14px; font-weight: 500;">Engineering</dd>
      </div>
      <div style="padding: 12px 16px;">
        <dt style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Status</dt>
        <dd><span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 9999px; font-size: 12px;">Active</span></dd>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <DescriptionList :items="details" :columns="2" bordered />
</template>

<script setup>
import { DescriptionList } from '@cambosoftware/cambo-admin'

const details = [
  { label: 'Full Name', value: 'John Doe' },
  { label: 'Email', value: 'john@example.com' },
  { label: 'Phone', value: '+1 (555) 123-4567' },
  { label: 'Location', value: 'New York, USA' },
  { label: 'Department', value: 'Engineering' },
  { label: 'Status', value: 'Active' }
]
</script>
```

  </template>
</LiveDemo>
```
