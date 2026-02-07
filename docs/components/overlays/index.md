# Overlays

Overlay components for displaying content on top of the main interface.

## Components

| Component | Description |
|-----------|-------------|
| [Modal](./modal.md) | Dialog window for focused content |
| [ConfirmModal](./confirm-modal.md) | Confirmation dialog with actions |
| [Drawer](./drawer.md) | Slide-out panel from screen edge |
| [Dropdown](./dropdown.md) | Contextual menu triggered by click |
| [Popover](./popover.md) | Floating content on hover/click |
| [ContextMenu](./context-menu.md) | Right-click context menu |

## Usage

Overlays are used to display additional content without navigating away from the current page. They're ideal for:

- Confirmations and alerts
- Forms and data entry
- Additional details and previews
- Navigation menus
- Contextual actions

## Example

```vue
<template>
  <Button @click="showModal = true">Open Modal</Button>

  <Modal v-model="showModal" title="Example Modal">
    <p>Modal content goes here.</p>

    <template #footer>
      <Button variant="secondary" @click="showModal = false">Cancel</Button>
      <Button variant="primary" @click="confirm">Confirm</Button>
    </template>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'
import Modal from '@/Components/Overlays/Modal.vue'
import Button from '@/Components/UI/Button.vue'

const showModal = ref(false)
</script>
```
