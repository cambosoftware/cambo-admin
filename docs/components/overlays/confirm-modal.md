# ConfirmModal

A specialized modal for confirmation dialogs with pre-built confirm/cancel actions.

## Import

```vue
<script setup>
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
</script>
```

## Basic Usage

```vue
<template>
  <Button variant="danger" @click="showConfirm = true">
    Delete Item
  </Button>

  <ConfirmModal
    v-model="showConfirm"
    title="Delete Item"
    message="Are you sure you want to delete this item? This action cannot be undone."
    confirm-text="Delete"
    confirm-variant="danger"
    @confirm="deleteItem"
  />
</template>

<script setup>
import { ref } from 'vue'

const showConfirm = ref(false)

const deleteItem = () => {
  // Perform delete action
  console.log('Item deleted')
}
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `boolean` | `false` | Controls visibility (v-model) |
| `title` | `string` | `'Confirm'` | Modal title |
| `message` | `string` | `''` | Confirmation message |
| `confirmText` | `string` | `'Confirm'` | Confirm button text |
| `cancelText` | `string` | `'Cancel'` | Cancel button text |
| `confirmVariant` | `string` | `'primary'` | Confirm button variant |
| `icon` | `Component` | `null` | Optional icon component |
| `loading` | `boolean` | `false` | Show loading state on confirm |
| `destructive` | `boolean` | `false` | Shorthand for danger styling |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `boolean` | Visibility changed |
| `confirm` | - | Confirm button clicked |
| `cancel` | - | Cancel button clicked |

## Examples

### Destructive Action

```vue
<ConfirmModal
  v-model="showDelete"
  title="Delete User"
  message="This will permanently delete the user and all their data."
  destructive
  @confirm="deleteUser"
/>
```

### With Icon

```vue
<script setup>
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline'
</script>

<template>
  <ConfirmModal
    v-model="show"
    title="Warning"
    message="This action may affect other users."
    :icon="ExclamationTriangleIcon"
    @confirm="proceed"
  />
</template>
```

### With Loading State

```vue
<template>
  <ConfirmModal
    v-model="showConfirm"
    title="Processing"
    message="Please wait while we process your request."
    :loading="isProcessing"
    @confirm="handleConfirm"
  />
</template>

<script setup>
const isProcessing = ref(false)

const handleConfirm = async () => {
  isProcessing.value = true
  await performAction()
  isProcessing.value = false
  showConfirm.value = false
}
</script>
```

### Custom Content

```vue
<ConfirmModal
  v-model="show"
  title="Delete Multiple Items"
  @confirm="deleteSelected"
>
  <template #default>
    <p class="mb-4">You are about to delete {{ selected.length }} items:</p>
    <ul class="list-disc list-inside text-sm text-gray-600">
      <li v-for="item in selected" :key="item.id">{{ item.name }}</li>
    </ul>
  </template>
</ConfirmModal>
```

## Variants

### Info Confirmation

```vue
<ConfirmModal
  title="Publish Article"
  message="This article will be visible to all users."
  confirm-text="Publish"
  confirm-variant="primary"
/>
```

### Warning Confirmation

```vue
<ConfirmModal
  title="Archive Project"
  message="This project will be moved to archives."
  confirm-text="Archive"
  confirm-variant="warning"
/>
```

### Danger Confirmation

```vue
<ConfirmModal
  title="Delete Account"
  message="This action is irreversible."
  confirm-text="Delete Forever"
  confirm-variant="danger"
  destructive
/>
```

## Playground

Try the ConfirmModal component:

<LiveDemo>
  <div style="position: relative; background: rgba(0,0,0,0.5); padding: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; max-width: 400px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
      <div style="display: flex; align-items: flex-start; gap: 16px;">
        <div style="width: 40px; height: 40px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
          <svg width="20" height="20" fill="#ef4444" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        </div>
        <div style="flex: 1;">
          <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 600; color: #111827;">Delete Item</h3>
          <p style="margin: 0; font-size: 14px; color: #6b7280;">Are you sure you want to delete this item? This action cannot be undone.</p>
        </div>
      </div>
      <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
        <button style="padding: 8px 16px; border: 1px solid #d1d5db; border-radius: 6px; background: white; cursor: pointer; font-size: 14px;">Cancel</button>
        <button style="padding: 8px 16px; border: none; border-radius: 6px; background: #ef4444; color: white; cursor: pointer; font-size: 14px;">Delete</button>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <ConfirmModal
    v-model="showConfirm"
    title="Delete Item"
    message="Are you sure you want to delete this item? This action cannot be undone."
    confirm-text="Delete"
    confirm-variant="danger"
    @confirm="deleteItem"
  />
</template>

<script setup>
import { ref } from 'vue'
const showConfirm = ref(true)

const deleteItem = () => {
  console.log('Item deleted')
}
</script>
```

  </template>
</LiveDemo>
