# Tabs

A tabbed interface component for organizing content into switchable panels. Use with `Tab` components.

## Import

```vue
<script setup>
import { Tabs, Tab } from '@cambosoftware/cambo-admin'
</script>
```

## Tabs Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String \| Number` | `null` | Active tab ID (for v-model) |
| `variant` | `String` | `'underline'` | Tab style. Values: `'underline'`, `'pills'`, `'bordered'` |

## Tab Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `String` | **required** | Tab button label text |
| `id` | `String \| Number` | `null` | Unique tab identifier (auto-generated if not provided) |
| `icon` | `Object \| Function` | `null` | Icon component to display |
| `badge` | `String \| Number` | `null` | Badge content (e.g., count) |
| `disabled` | `Boolean` | `false` | Disable the tab |

## Slots

### Tabs Slots

| Slot | Description |
|------|-------------|
| `default` | Tab components |

### Tab Slots

| Slot | Description |
|------|-------------|
| `default` | Tab panel content |

## Events

### Tabs Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| Number` | Emitted when active tab changes |

## Variants

### Underline (Default)

Traditional underlined tabs with a bottom border indicator.

### Pills

Rounded pill-style tabs with background color.

### Bordered

Bordered tabs that appear connected to the content area.

## Examples

### Basic Tabs

```vue
<template>
    <Tabs>
        <Tab label="Overview">
            <p>Overview content goes here.</p>
        </Tab>
        <Tab label="Details">
            <p>Details content goes here.</p>
        </Tab>
        <Tab label="Settings">
            <p>Settings content goes here.</p>
        </Tab>
    </Tabs>
</template>
```

### Controlled Tabs (v-model)

```vue
<template>
    <Tabs v-model="activeTab">
        <Tab id="overview" label="Overview">
            <p>Overview panel</p>
        </Tab>
        <Tab id="analytics" label="Analytics">
            <p>Analytics panel</p>
        </Tab>
        <Tab id="reports" label="Reports">
            <p>Reports panel</p>
        </Tab>
    </Tabs>
</template>

<script setup>
import { ref } from 'vue'
const activeTab = ref('overview')
</script>
```

### Pills Variant

```vue
<template>
    <Tabs variant="pills">
        <Tab label="All">
            <p>All items</p>
        </Tab>
        <Tab label="Active">
            <p>Active items</p>
        </Tab>
        <Tab label="Archived">
            <p>Archived items</p>
        </Tab>
    </Tabs>
</template>
```

### Bordered Variant

```vue
<template>
    <Tabs variant="bordered">
        <Tab label="Profile">
            <p>Profile settings</p>
        </Tab>
        <Tab label="Security">
            <p>Security settings</p>
        </Tab>
    </Tabs>
</template>
```

### Tabs with Icons

```vue
<template>
    <Tabs>
        <Tab label="Dashboard" :icon="HomeIcon">
            <p>Dashboard content</p>
        </Tab>
        <Tab label="Users" :icon="UsersIcon">
            <p>Users content</p>
        </Tab>
        <Tab label="Settings" :icon="CogIcon">
            <p>Settings content</p>
        </Tab>
    </Tabs>
</template>

<script setup>
import { HomeIcon, UsersIcon, CogIcon } from '@heroicons/vue/24/outline'
</script>
```

### Tabs with Badges

```vue
<template>
    <Tabs>
        <Tab label="Inbox" :badge="12">
            <p>You have 12 unread messages.</p>
        </Tab>
        <Tab label="Sent">
            <p>Sent messages</p>
        </Tab>
        <Tab label="Drafts" :badge="3">
            <p>3 draft messages</p>
        </Tab>
    </Tabs>
</template>
```

### Tabs with Icons and Badges

```vue
<template>
    <Tabs variant="pills">
        <Tab label="Tasks" :icon="ClipboardIcon" :badge="5">
            <p>5 pending tasks</p>
        </Tab>
        <Tab label="Messages" :icon="ChatIcon" :badge="new">
            <p>New messages</p>
        </Tab>
        <Tab label="Notifications" :icon="BellIcon">
            <p>All caught up!</p>
        </Tab>
    </Tabs>
</template>
```

### Disabled Tab

```vue
<template>
    <Tabs>
        <Tab label="Step 1" id="step1">
            <p>Complete this step first.</p>
        </Tab>
        <Tab label="Step 2" id="step2" disabled>
            <p>This step is locked.</p>
        </Tab>
        <Tab label="Step 3" id="step3" disabled>
            <p>Complete previous steps first.</p>
        </Tab>
    </Tabs>
</template>
```

### Product Page Tabs

```vue
<template>
    <Card :padding="false">
        <Tabs>
            <Tab label="Description">
                <div class="p-6">
                    <h3 class="font-semibold mb-2">Product Description</h3>
                    <p class="text-gray-600">{{ product.description }}</p>
                </div>
            </Tab>
            <Tab label="Specifications">
                <div class="p-6">
                    <DescriptionList :items="product.specs" />
                </div>
            </Tab>
            <Tab label="Reviews" :badge="product.reviewCount">
                <div class="p-6">
                    <ReviewList :reviews="product.reviews" />
                </div>
            </Tab>
        </Tabs>
    </Card>
</template>
```

### Settings Page

```vue
<template>
    <div class="max-w-4xl mx-auto">
        <PageHeader title="Settings" />

        <Tabs v-model="activeSection" variant="pills">
            <Tab id="general" label="General" :icon="CogIcon">
                <GeneralSettings />
            </Tab>
            <Tab id="notifications" label="Notifications" :icon="BellIcon">
                <NotificationSettings />
            </Tab>
            <Tab id="security" label="Security" :icon="ShieldIcon">
                <SecuritySettings />
            </Tab>
            <Tab id="billing" label="Billing" :icon="CreditCardIcon">
                <BillingSettings />
            </Tab>
        </Tabs>
    </div>
</template>
```

## Styling

### Underline Variant
- Wrapper: `border-b border-gray-200`
- Inactive: `border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300`
- Active: `border-b-2 border-primary-500 text-primary-600`

### Pills Variant
- Wrapper: `flex gap-1`
- Inactive: `rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100`
- Active: `rounded-lg bg-primary-100 text-primary-700`

### Bordered Variant
- Wrapper: `border-b border-gray-200`
- Inactive: `border border-transparent rounded-t-lg text-gray-500 hover:text-gray-700`
- Active: `border border-gray-200 border-b-white rounded-t-lg text-primary-600 bg-white`

## Accessibility

- Tab list has `role="tablist"`
- Tab buttons have `role="tab"`
- Tab panels have `role="tabpanel"`
- Active tab has `aria-selected="true"`
- Keyboard navigation is supported

## Playground

Try the tabs component:

<LiveDemo>
  <DemoTabs />

  <template #code>

```vue
<Tabs>
  <Tab label="Overview">
    <p>Overview content goes here.</p>
  </Tab>
  <Tab label="Details">
    <p>Details content goes here.</p>
  </Tab>
  <Tab label="Settings">
    <p>Settings content goes here.</p>
  </Tab>
</Tabs>
```

  </template>
</LiveDemo>
