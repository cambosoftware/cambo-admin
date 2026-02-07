# ClickToCopy

A wrapper component that copies its content when clicked.

## Import

```vue
<script setup>
import ClickToCopy from '@/Components/Utilities/ClickToCopy.vue'
</script>
```

## Basic Usage

```vue
<template>
  <ClickToCopy :text="email">
    {{ email }}
  </ClickToCopy>
</template>

<script setup>
const email = 'user@example.com'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `text` | `string` | `''` | Text to copy (defaults to slot content) |
| `tooltip` | `string` | `'Click to copy'` | Tooltip text |
| `copiedTooltip` | `string` | `'Copied!'` | Tooltip after copying |
| `showTooltip` | `boolean` | `true` | Show tooltip |
| `timeout` | `number` | `2000` | Reset timeout |

## Examples

### Email Address

```vue
<p>
  Contact us at
  <ClickToCopy text="support@company.com" class="text-indigo-600 hover:underline cursor-pointer">
    support@company.com
  </ClickToCopy>
</p>
```

### Code Snippet

```vue
<ClickToCopy :text="command" class="font-mono bg-gray-100 px-2 py-1 rounded">
  npm install cambo-admin
</ClickToCopy>
```

### ID or Reference Number

```vue
<template>
  <div class="flex items-center gap-2">
    <span class="text-gray-500">Order:</span>
    <ClickToCopy :text="orderId" class="font-mono font-medium">
      #{{ orderId }}
    </ClickToCopy>
  </div>
</template>
```

### Without Tooltip

```vue
<ClickToCopy :text="text" :show-tooltip="false">
  {{ text }}
</ClickToCopy>
```

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `copied` | `string` | Text was copied |

## Styling

The component applies these classes on different states:

- Default: `cursor-pointer`
- Hover: User-defined hover styles apply
- Copied: Briefly shows success state

```vue
<ClickToCopy
  :text="code"
  class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded transition-colors"
>
  {{ code }}
</ClickToCopy>
```

## Playground

Try the ClickToCopy component:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 12px;">
    <span style="display: inline-block; padding: 4px 8px; background: #f3f4f6; border-radius: 4px; font-family: monospace; cursor: pointer; transition: background 0.15s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'" title="Click to copy">npm install cambo-admin</span>
    <span style="color: #4f46e5; cursor: pointer; text-decoration: none; transition: text-decoration 0.15s;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'" title="Click to copy">support@company.com</span>
  </div>

  <template #code>

```vue
<template>
  <div class="space-y-3">
    <ClickToCopy
      text="npm install cambo-admin"
      class="font-mono bg-gray-100 px-2 py-1 rounded"
    >
      npm install cambo-admin
    </ClickToCopy>

    <ClickToCopy
      text="support@company.com"
      class="text-indigo-600 hover:underline"
    >
      support@company.com
    </ClickToCopy>
  </div>
</template>

<script setup>
import ClickToCopy from '@/Components/Utilities/ClickToCopy.vue'
</script>
```

  </template>
</LiveDemo>
