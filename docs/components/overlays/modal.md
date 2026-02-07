# Modal

A dialog overlay that appears above the page content, used for important interactions that require user attention.

## Import

```vue
<script setup>
import Modal from '@/Components/Overlays/Modal.vue'
</script>
```

## Basic Usage

```vue
<template>
  <Button @click="showModal = true">Open Modal</Button>

  <Modal v-model="showModal" title="Confirmation">
    <p>Are you sure you want to continue?</p>

    <template #footer>
      <Button variant="secondary" @click="showModal = false">Cancel</Button>
      <Button variant="primary" @click="confirm">Confirm</Button>
    </template>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'

const showModal = ref(false)

const confirm = () => {
  // Do something
  showModal.value = false
}
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `boolean` | `false` | Controls modal visibility (v-model) |
| `title` | `string` | `''` | Modal title |
| `size` | `string` | `'md'` | Modal size: `sm`, `md`, `lg`, `xl`, `full` |
| `closable` | `boolean` | `true` | Show close button |
| `closeOnBackdrop` | `boolean` | `true` | Close when clicking backdrop |
| `closeOnEscape` | `boolean` | `true` | Close on Escape key |
| `persistent` | `boolean` | `false` | Prevent closing (for forms) |

## Sizes

```vue
<Modal size="sm" />  <!-- 400px max-width -->
<Modal size="md" />  <!-- 500px max-width (default) -->
<Modal size="lg" />  <!-- 700px max-width -->
<Modal size="xl" />  <!-- 900px max-width -->
<Modal size="full" /> <!-- Full screen -->
```

## Slots

| Slot | Description |
|------|-------------|
| `default` | Modal body content |
| `header` | Custom header (replaces title) |
| `footer` | Footer with action buttons |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `boolean` | Emitted when visibility changes |
| `close` | - | Emitted when modal is closed |
| `open` | - | Emitted when modal is opened |

## Examples

### Form Modal

```vue
<Modal v-model="showForm" title="Edit User" :closable="false" persistent>
  <Form @submit="saveUser">
    <FormGroup label="Name" :error="form.errors.name">
      <Input v-model="form.name" />
    </FormGroup>

    <FormGroup label="Email" :error="form.errors.email">
      <Input v-model="form.email" type="email" />
    </FormGroup>
  </Form>

  <template #footer>
    <Button variant="secondary" @click="showForm = false">Cancel</Button>
    <Button variant="primary" :loading="form.processing" @click="saveUser">
      Save Changes
    </Button>
  </template>
</Modal>
```

### Custom Header

```vue
<Modal v-model="show">
  <template #header>
    <div class="flex items-center gap-3">
      <Avatar :src="user.avatar" />
      <div>
        <h3 class="font-semibold">{{ user.name }}</h3>
        <p class="text-sm text-gray-500">{{ user.email }}</p>
      </div>
    </div>
  </template>

  <!-- Content -->
</Modal>
```

### Scrollable Content

```vue
<Modal v-model="show" title="Terms of Service">
  <div class="max-h-96 overflow-y-auto">
    <!-- Long content -->
  </div>
</Modal>
```

## Accessibility

- Focus is trapped within the modal when open
- Pressing `Escape` closes the modal (unless disabled)
- `aria-modal="true"` and proper roles are applied
- Focus returns to trigger element on close

## Playground

Try the modal component with different props:

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <DemoButton @click="$refs.modal1.modelValue = true">Open Small Modal</DemoButton>
    <DemoButton @click="$refs.modal2.modelValue = true">Open Medium Modal</DemoButton>
    <DemoButton @click="$refs.modal3.modelValue = true">Open Large Modal</DemoButton>
  </div>

  <template #code>

```vue
<script setup>
import { ref } from 'vue'
const showModal = ref(false)
</script>

<template>
  <Button @click="showModal = true">Open Modal</Button>

  <Modal v-model="showModal" title="Modal Title" size="md">
    <p>This is the modal content.</p>

    <template #footer>
      <Button variant="secondary" @click="showModal = false">Cancel</Button>
      <Button variant="primary" @click="confirm">Confirm</Button>
    </template>
  </Modal>
</template>
```

  </template>
</LiveDemo>

### Modal Sizes

| Size | Max Width | Use Case |
|------|-----------|----------|
| `sm` | 400px | Confirmations, simple forms |
| `md` | 500px | Standard forms, details |
| `lg` | 700px | Complex forms, tables |
| `xl` | 900px | Large content |
| `full` | 100% | Full screen dialogs |
