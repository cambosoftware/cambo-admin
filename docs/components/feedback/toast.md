# Toast

A notification component for displaying temporary messages with auto-dismiss functionality, progress indicator, and different severity levels.

## Import

```js
import { Toast, ToastContainer } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `String` | `'info'` | Toast type: `'info'`, `'success'`, `'warning'`, `'danger'` |
| `title` | `String` | `null` | Optional title text |
| `message` | `String` | `''` | Toast message content |
| `duration` | `Number` | `5000` | Auto-dismiss duration in ms (0 for no auto-dismiss) |
| `dismissible` | `Boolean` | `true` | Show close button |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `close` | - | Emitted when toast is closed (manually or auto) |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Custom message content (overrides `message` prop) |

## Basic Example

```vue
<template>
  <Toast
    variant="success"
    title="Success!"
    message="Your changes have been saved."
  />
</template>

<script setup>
import { Toast } from '@cambosoftware/cambo-admin'
</script>
```

## Using Toast Container (Recommended)

For a complete toast notification system, use the ToastContainer with a composable:

```vue
<!-- App.vue -->
<template>
  <div id="app">
    <router-view />
    <ToastContainer />
  </div>
</template>

<script setup>
import { ToastContainer } from '@cambosoftware/cambo-admin'
</script>
```

```js
// composables/useToast.js
import { ref } from 'vue'

const toasts = ref([])

export function useToast() {
  const add = (toast) => {
    const id = Date.now()
    toasts.value.push({ id, ...toast })
    return id
  }

  const remove = (id) => {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }

  const success = (message, title = null) =>
    add({ variant: 'success', message, title })

  const error = (message, title = null) =>
    add({ variant: 'danger', message, title })

  const warning = (message, title = null) =>
    add({ variant: 'warning', message, title })

  const info = (message, title = null) =>
    add({ variant: 'info', message, title })

  return { toasts, add, remove, success, error, warning, info }
}
```

```vue
<!-- Usage in any component -->
<script setup>
import { useToast } from '@/composables/useToast'

const toast = useToast()

const handleSave = async () => {
  try {
    await saveData()
    toast.success('Data saved successfully!')
  } catch (e) {
    toast.error('Failed to save data', 'Error')
  }
}
</script>
```

## Variants

```vue
<template>
  <div class="space-y-4">
    <Toast variant="info" message="This is an info notification." />
    <Toast variant="success" message="Operation completed successfully!" />
    <Toast variant="warning" message="Please review before continuing." />
    <Toast variant="danger" message="An error occurred." />
  </div>
</template>
```

## With Title

```vue
<template>
  <Toast
    variant="success"
    title="Payment Received"
    message="Your payment of $49.99 has been processed."
  />
</template>
```

## Custom Duration

```vue
<template>
  <!-- 10 second duration -->
  <Toast
    variant="info"
    message="This will stay for 10 seconds."
    :duration="10000"
  />

  <!-- No auto-dismiss -->
  <Toast
    variant="warning"
    message="This stays until manually dismissed."
    :duration="0"
  />

  <!-- Quick 2 second toast -->
  <Toast
    variant="success"
    message="Quick notification!"
    :duration="2000"
  />
</template>
```

## Non-Dismissible

```vue
<template>
  <Toast
    variant="info"
    message="Processing... Please wait."
    :dismissible="false"
    :duration="0"
  />
</template>
```

## With Custom Content

```vue
<template>
  <Toast variant="info" title="New Message">
    <div class="flex items-center gap-2">
      <Avatar src="/john.jpg" size="xs" />
      <span>John Doe sent you a message</span>
    </div>
  </Toast>
</template>
```

## Handle Close Event

```vue
<template>
  <Toast
    v-if="showToast"
    variant="success"
    message="Saved!"
    @close="handleClose"
  />
</template>

<script setup>
import { ref } from 'vue'

const showToast = ref(true)

const handleClose = () => {
  showToast.value = false
  // Perform any cleanup
}
</script>
```

## Toast Container Implementation

```vue
<!-- ToastContainer.vue -->
<template>
  <Teleport to="body">
    <div class="fixed bottom-4 right-4 z-50 space-y-2">
      <Toast
        v-for="toast in toasts"
        :key="toast.id"
        :variant="toast.variant"
        :title="toast.title"
        :message="toast.message"
        :duration="toast.duration"
        @close="remove(toast.id)"
      />
    </div>
  </Teleport>
</template>

<script setup>
import { useToast } from '@/composables/useToast'
import { Toast } from '@cambosoftware/cambo-admin'

const { toasts, remove } = useToast()
</script>
```

## Action Toast

```vue
<template>
  <Toast variant="info" title="Item Deleted" :duration="8000">
    <p>The item has been moved to trash.</p>
    <Button
      size="xs"
      variant="ghost"
      class="mt-2 text-primary-600 hover:text-primary-700"
      @click="handleUndo"
    >
      Undo
    </Button>
  </Toast>
</template>

<script setup>
const handleUndo = () => {
  // Restore the item
  console.log('Undoing delete...')
}
</script>
```

## Progress Toast

```vue
<template>
  <Toast
    variant="info"
    title="Uploading File"
    :duration="0"
    :dismissible="false"
  >
    <div class="mt-2">
      <ProgressBar :value="uploadProgress" size="sm" />
      <p class="text-xs text-gray-500 mt-1">{{ uploadProgress }}% complete</p>
    </div>
  </Toast>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const uploadProgress = ref(0)

onMounted(() => {
  const interval = setInterval(() => {
    uploadProgress.value += 10
    if (uploadProgress.value >= 100) {
      clearInterval(interval)
    }
  }, 500)
})
</script>
```

## Toast Positioning

```vue
<!-- Top Right -->
<div class="fixed top-4 right-4 z-50 space-y-2">
  <Toast ... />
</div>

<!-- Top Left -->
<div class="fixed top-4 left-4 z-50 space-y-2">
  <Toast ... />
</div>

<!-- Bottom Center -->
<div class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 space-y-2">
  <Toast ... />
</div>

<!-- Top Center -->
<div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 space-y-2">
  <Toast ... />
</div>
```

## Transition Animation

The Toast component includes built-in transition animations:
- Enter: Slides in from right with fade
- Leave: Slides out to right with fade
- Progress bar animates to show remaining time

## Playground

Try the toast component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoToast variant="info" message="This is an info notification." />
    <DemoToast variant="success" message="Operation completed successfully!" />
    <DemoToast variant="warning" message="Please review before continuing." />
    <DemoToast variant="danger" message="An error occurred." />
  </div>

  <template #code>

```vue
<Toast variant="info" message="This is an info notification." />
<Toast variant="success" message="Operation completed successfully!" />
<Toast variant="warning" message="Please review before continuing." />
<Toast variant="danger" message="An error occurred." />
```

  </template>
</LiveDemo>

### Toast with Title

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoToast variant="success" title="Success!" message="Your changes have been saved." />
    <DemoToast variant="danger" title="Error" message="Failed to save changes." />
  </div>

  <template #code>

```vue
<Toast variant="success" title="Success!" message="Your changes have been saved." />
<Toast variant="danger" title="Error" message="Failed to save changes." />
```

  </template>
</LiveDemo>
