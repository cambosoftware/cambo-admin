# CopyButton

A button that copies text to the clipboard with visual feedback.

## Import

```vue
<script setup>
import CopyButton from '@/Components/Utilities/CopyButton.vue'
</script>
```

## Basic Usage

```vue
<template>
  <CopyButton :text="apiKey" />
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `text` | `string` | `''` | Text to copy |
| `label` | `string` | `'Copy'` | Button label |
| `copiedLabel` | `string` | `'Copied!'` | Label after copying |
| `variant` | `string` | `'secondary'` | Button variant |
| `size` | `string` | `'sm'` | Button size |
| `iconOnly` | `boolean` | `false` | Show icon only |
| `timeout` | `number` | `2000` | Reset timeout in ms |

## Examples

### Icon Only

```vue
<CopyButton :text="code" icon-only />
```

### With Code Block

```vue
<template>
  <div class="relative bg-gray-900 rounded-lg p-4">
    <pre class="text-gray-100"><code>{{ code }}</code></pre>
    <CopyButton
      :text="code"
      icon-only
      class="absolute top-2 right-2"
    />
  </div>
</template>
```

### Custom Labels

```vue
<CopyButton
  :text="text"
  label="Copy Link"
  copied-label="Link Copied!"
/>
```

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `copied` | `string` | Text was copied |
| `error` | `Error` | Copy failed |

## Real-World Example

```vue
<template>
  <Card>
    <template #header>
      <h3>API Key</h3>
    </template>

    <div class="flex items-center gap-3">
      <code class="flex-1 bg-gray-100 px-3 py-2 rounded font-mono text-sm">
        {{ maskedKey }}
      </code>
      <Button variant="ghost" size="sm" @click="toggleVisibility">
        <EyeIcon v-if="!visible" class="w-4 h-4" />
        <EyeSlashIcon v-else class="w-4 h-4" />
      </Button>
      <CopyButton :text="apiKey" icon-only />
    </div>
  </Card>
</template>

<script setup>
import { ref, computed } from 'vue'

const apiKey = 'sk_live_abc123xyz789'
const visible = ref(false)

const maskedKey = computed(() =>
  visible.value ? apiKey : apiKey.slice(0, 7) + '...' + apiKey.slice(-4)
)

const toggleVisibility = () => {
  visible.value = !visible.value
}
</script>
```

## Playground

Try the CopyButton component:

<LiveDemo>
  <div style="display: flex; align-items: center; gap: 12px;">
    <code style="flex: 1; background: #f3f4f6; padding: 8px 12px; border-radius: 6px; font-family: monospace; font-size: 14px;">sk_live_abc123xyz789</code>
    <button style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 14px; font-weight: 500; color: #374151; background: #fff; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
      </svg>
      Copy
    </button>
  </div>

  <template #code>

```vue
<template>
  <div class="flex items-center gap-3">
    <code class="flex-1 bg-gray-100 px-3 py-2 rounded font-mono text-sm">
      sk_live_abc123xyz789
    </code>
    <CopyButton :text="apiKey" />
  </div>
</template>

<script setup>
import CopyButton from '@/Components/Utilities/CopyButton.vue'

const apiKey = 'sk_live_abc123xyz789'
</script>
```

  </template>
</LiveDemo>
