# PageHeader

A standardized page header component that displays the page title, optional subtitle, breadcrumb navigation, and action buttons.

## Import

```vue
<script setup>
import { PageHeader } from 'cambo-admin'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `String` | **required** | Main page title |
| `subtitle` | `String` | `null` | Optional subtitle text below the title |
| `breadcrumb` | `Array` | `[]` | Array of breadcrumb items |
| `showBreadcrumb` | `Boolean` | `true` | Whether to display the breadcrumb navigation |
| `border` | `Boolean` | `false` | Whether to show a bottom border |

## Slots

| Name | Description |
|------|-------------|
| `description` | Additional description content below subtitle |
| `actions` | Action buttons aligned to the right |
| `tabs` | Tab navigation or additional content below the header |

## Usage

### Basic Usage

```vue
<template>
  <PageHeader title="Dashboard" />
</template>
```

### With Subtitle

```vue
<template>
  <PageHeader
    title="User Management"
    subtitle="Manage user accounts and permissions"
  />
</template>
```

### With Breadcrumb

```vue
<template>
  <PageHeader
    title="Edit User"
    :breadcrumb="[
      { label: 'Users', href: '/users' },
      { label: 'John Doe', href: '/users/1' },
      { label: 'Edit' }
    ]"
  />
</template>
```

### With Actions

```vue
<template>
  <PageHeader title="Products">
    <template #actions>
      <button class="btn btn-secondary">
        Export
      </button>
      <button class="btn btn-primary">
        Add Product
      </button>
    </template>
  </PageHeader>
</template>
```

### With Bottom Border

```vue
<template>
  <PageHeader
    title="Settings"
    subtitle="Configure your application settings"
    :border="true"
  />
</template>
```

### With Description Slot

```vue
<template>
  <PageHeader title="API Keys">
    <template #description>
      <div class="mt-1 flex items-center gap-2 text-sm text-gray-500">
        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
          3 Active
        </span>
        <span>Last generated 2 days ago</span>
      </div>
    </template>

    <template #actions>
      <button class="btn btn-primary">Generate New Key</button>
    </template>
  </PageHeader>
</template>
```

### With Tabs

```vue
<template>
  <PageHeader
    title="User Profile"
    :border="true"
  >
    <template #actions>
      <button class="btn btn-primary">Save Changes</button>
    </template>

    <template #tabs>
      <nav class="flex gap-4">
        <a
          href="#general"
          class="py-2 px-1 border-b-2 border-primary-500 text-primary-600 font-medium"
        >
          General
        </a>
        <a
          href="#security"
          class="py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700"
        >
          Security
        </a>
        <a
          href="#notifications"
          class="py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700"
        >
          Notifications
        </a>
      </nav>
    </template>
  </PageHeader>
</template>
```

### Complete Example

```vue
<template>
  <AdminLayout>
    <template #sidebar-menu>
      <!-- Menu items -->
    </template>

    <PageHeader
      title="Order #12345"
      subtitle="Created on January 15, 2025"
      :breadcrumb="[
        { label: 'Orders', href: '/orders' },
        { label: '#12345' }
      ]"
      :border="true"
    >
      <template #description>
        <div class="flex items-center gap-3 mt-2">
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
            Pending
          </span>
          <span class="text-sm text-gray-500">
            Customer: John Doe
          </span>
        </div>
      </template>

      <template #actions>
        <button class="btn btn-secondary">
          <PrinterIcon class="h-4 w-4 mr-2" />
          Print Invoice
        </button>
        <button class="btn btn-primary">
          Mark as Shipped
        </button>
      </template>
    </PageHeader>

    <!-- Order details content -->
  </AdminLayout>
</template>
```

## Configuration Options

### Layout Structure

```
+----------------------------------------------------------+
| [Breadcrumb]                                              |
+----------------------------------------------------------+
| Title                                    [Action Buttons] |
| Subtitle                                                  |
| [Description slot]                                        |
+----------------------------------------------------------+
| [Tabs slot]                                               |
+----------------------------------------------------------+
```

### Responsive Behavior

| Screen Size | Behavior |
|-------------|----------|
| Mobile (< 640px) | Title and actions stack vertically |
| Desktop (>= 640px) | Title on left, actions on right |

### Styling Details

| Element | Classes |
|---------|---------|
| Container | `mb-6` (margin-bottom) |
| Title | `text-2xl font-bold text-gray-900 truncate` |
| Subtitle | `mt-1 text-sm text-gray-500` |
| Border | `pb-6 border-b border-gray-200` |

## Best Practices

1. **Consistent Breadcrumbs**: Always include breadcrumb navigation for pages deeper than the root level
2. **Clear Titles**: Use descriptive, concise titles that identify the page content
3. **Action Grouping**: Group related actions together, with primary action on the right
4. **Border Usage**: Use border when content below needs visual separation (e.g., with tabs)

## Without Breadcrumb

You can hide the breadcrumb for top-level pages:

```vue
<template>
  <PageHeader
    title="Dashboard"
    subtitle="Welcome back, John!"
    :show-breadcrumb="false"
  />
</template>
```

## Playground

Try the PageHeader component:

<LiveDemo>
  <div style="background: white; padding: 16px 0;">
    <nav style="display: flex; align-items: center; gap: 8px; font-size: 14px; margin-bottom: 12px;">
      <a href="#" style="color: #9ca3af; text-decoration: none; display: flex; align-items: center;">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
      </a>
      <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="color: #d1d5db;"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
      <a href="#" style="color: #6b7280; text-decoration: none;">Products</a>
      <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="color: #d1d5db;"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
      <span style="color: #111827;">New</span>
    </nav>
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <div>
        <h1 style="font-size: 24px; font-weight: 700; color: #111827; margin: 0;">Create Product</h1>
        <p style="color: #6b7280; font-size: 14px; margin: 4px 0 0 0;">Add a new product to your catalog</p>
      </div>
      <div style="display: flex; gap: 8px;">
        <button style="padding: 8px 16px; border: 1px solid #d1d5db; border-radius: 6px; background: white; cursor: pointer;">Cancel</button>
        <button style="padding: 8px 16px; border: none; border-radius: 6px; background: #4f46e5; color: white; cursor: pointer;">Save Product</button>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <PageHeader
    title="Create Product"
    subtitle="Add a new product to your catalog"
    :breadcrumb="[
      { label: 'Products', href: '/products' },
      { label: 'New' }
    ]"
  >
    <template #actions>
      <button class="btn btn-secondary">Cancel</button>
      <button class="btn btn-primary">Save Product</button>
    </template>
  </PageHeader>
</template>
```

  </template>
</LiveDemo>
