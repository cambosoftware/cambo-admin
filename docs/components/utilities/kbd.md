# Kbd

A keyboard key indicator component for displaying keyboard shortcuts.

## Import

```vue
<script setup>
import Kbd from '@/Components/Utilities/Kbd.vue'
</script>
```

## Basic Usage

```vue
<template>
  <Kbd>Enter</Kbd>
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `size` | `string` | `'md'` | Size: `sm`, `md`, `lg` |

## Examples

### Single Key

```vue
<Kbd>Esc</Kbd>
<Kbd>Enter</Kbd>
<Kbd>Tab</Kbd>
<Kbd>Space</Kbd>
```

### Key Combinations

```vue
<div class="flex items-center gap-1">
  <Kbd>Ctrl</Kbd>
  <span>+</span>
  <Kbd>S</Kbd>
</div>
```

### Mac Style

```vue
<div class="flex items-center gap-1">
  <Kbd>⌘</Kbd>
  <span>+</span>
  <Kbd>Shift</Kbd>
  <span>+</span>
  <Kbd>P</Kbd>
</div>
```

### Different Sizes

```vue
<Kbd size="sm">Ctrl</Kbd>
<Kbd size="md">Ctrl</Kbd>
<Kbd size="lg">Ctrl</Kbd>
```

### In Context

```vue
<p>
  Press <Kbd>Ctrl</Kbd> + <Kbd>K</Kbd> to open the command palette.
</p>
```

### Shortcut List

```vue
<template>
  <div class="space-y-2">
    <div class="flex justify-between items-center">
      <span>Save</span>
      <div class="flex items-center gap-1">
        <Kbd size="sm">Ctrl</Kbd>
        <span class="text-gray-400">+</span>
        <Kbd size="sm">S</Kbd>
      </div>
    </div>
    <div class="flex justify-between items-center">
      <span>Undo</span>
      <div class="flex items-center gap-1">
        <Kbd size="sm">Ctrl</Kbd>
        <span class="text-gray-400">+</span>
        <Kbd size="sm">Z</Kbd>
      </div>
    </div>
    <div class="flex justify-between items-center">
      <span>Search</span>
      <div class="flex items-center gap-1">
        <Kbd size="sm">Ctrl</Kbd>
        <span class="text-gray-400">+</span>
        <Kbd size="sm">F</Kbd>
      </div>
    </div>
  </div>
</template>
```

## Real-World Example

```vue
<template>
  <Modal v-model="showShortcuts" title="Keyboard Shortcuts">
    <div class="space-y-4">
      <div>
        <h4 class="font-medium mb-2">General</h4>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between">
            <span>Command Palette</span>
            <span><Kbd size="sm">Ctrl</Kbd> + <Kbd size="sm">K</Kbd></span>
          </div>
          <div class="flex justify-between">
            <span>Quick Search</span>
            <span><Kbd size="sm">/</Kbd></span>
          </div>
          <div class="flex justify-between">
            <span>Close Modal</span>
            <span><Kbd size="sm">Esc</Kbd></span>
          </div>
        </div>
      </div>

      <div>
        <h4 class="font-medium mb-2">Navigation</h4>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between">
            <span>Go to Dashboard</span>
            <span><Kbd size="sm">G</Kbd> then <Kbd size="sm">D</Kbd></span>
          </div>
          <div class="flex justify-between">
            <span>Go to Settings</span>
            <span><Kbd size="sm">G</Kbd> then <Kbd size="sm">S</Kbd></span>
          </div>
        </div>
      </div>
    </div>
  </Modal>
</template>
```

## Styling

The component renders with:

- Rounded corners
- Subtle border and shadow
- Slightly raised appearance
- Monospace font for consistency

## Playground

Try the Kbd component:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 12px;">
    <p style="display: flex; align-items: center; gap: 6px;">
      Press
      <kbd style="display: inline-block; padding: 2px 6px; font-family: monospace; font-size: 12px; background: linear-gradient(180deg, #fff 0%, #f3f4f6 100%); border: 1px solid #d1d5db; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1), inset 0 1px 0 #fff;">Ctrl</kbd>
      +
      <kbd style="display: inline-block; padding: 2px 6px; font-family: monospace; font-size: 12px; background: linear-gradient(180deg, #fff 0%, #f3f4f6 100%); border: 1px solid #d1d5db; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1), inset 0 1px 0 #fff;">K</kbd>
      to open the command palette.
    </p>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
      <span>Save</span>
      <span style="display: flex; align-items: center; gap: 4px;">
        <kbd style="display: inline-block; padding: 2px 6px; font-family: monospace; font-size: 11px; background: linear-gradient(180deg, #fff 0%, #f3f4f6 100%); border: 1px solid #d1d5db; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1), inset 0 1px 0 #fff;">Ctrl</kbd>
        <span style="color: #9ca3af;">+</span>
        <kbd style="display: inline-block; padding: 2px 6px; font-family: monospace; font-size: 11px; background: linear-gradient(180deg, #fff 0%, #f3f4f6 100%); border: 1px solid #d1d5db; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1), inset 0 1px 0 #fff;">S</kbd>
      </span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
      <span>Undo</span>
      <span style="display: flex; align-items: center; gap: 4px;">
        <kbd style="display: inline-block; padding: 2px 6px; font-family: monospace; font-size: 11px; background: linear-gradient(180deg, #fff 0%, #f3f4f6 100%); border: 1px solid #d1d5db; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1), inset 0 1px 0 #fff;">Ctrl</kbd>
        <span style="color: #9ca3af;">+</span>
        <kbd style="display: inline-block; padding: 2px 6px; font-family: monospace; font-size: 11px; background: linear-gradient(180deg, #fff 0%, #f3f4f6 100%); border: 1px solid #d1d5db; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1), inset 0 1px 0 #fff;">Z</kbd>
      </span>
    </div>
  </div>

  <template #code>

```vue
<template>
  <div class="space-y-3">
    <p>
      Press <Kbd>Ctrl</Kbd> + <Kbd>K</Kbd> to open the command palette.
    </p>

    <div class="flex justify-between items-center">
      <span>Save</span>
      <span>
        <Kbd size="sm">Ctrl</Kbd>
        <span class="text-gray-400">+</span>
        <Kbd size="sm">S</Kbd>
      </span>
    </div>

    <div class="flex justify-between items-center">
      <span>Undo</span>
      <span>
        <Kbd size="sm">Ctrl</Kbd>
        <span class="text-gray-400">+</span>
        <Kbd size="sm">Z</Kbd>
      </span>
    </div>
  </div>
</template>

<script setup>
import Kbd from '@/Components/Utilities/Kbd.vue'
</script>
```

  </template>
</LiveDemo>
