# ColorPicker

A color picker component with preset swatches, native color picker integration, and hex input support.

## Import

```vue
<script setup>
import { ColorPicker } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String` | `null` | The selected color in hex format (v-model) |
| `placeholder` | `String` | `'Choose a color'` | Placeholder text when no color is selected |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the color picker |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `clearable` | `Boolean` | `true` | Show clear button when a color is selected |
| `swatches` | `Array` | `[default colors]` | Array of preset color swatches (hex values) |
| `showInput` | `Boolean` | `true` | Show hex input and native color picker |

## Default Swatches

The default swatch palette includes:
- Red tones: `#ef4444`, `#f43f5e`
- Orange tones: `#f97316`, `#f59e0b`
- Yellow tones: `#eab308`
- Green tones: `#84cc16`, `#22c55e`, `#14b8a6`
- Blue tones: `#06b6d4`, `#0ea5e9`, `#3b82f6`
- Purple tones: `#6366f1`, `#8b5cf6`, `#a855f7`
- Pink tones: `#d946ef`, `#ec4899`
- Grayscale: `#000000`, `#374151`, `#6b7280`, `#9ca3af`, `#d1d5db`, `#f3f4f6`, `#ffffff`

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| null` | Emitted when color changes (for v-model) |
| `change` | `String \| null` | Emitted when color changes |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <ColorPicker v-model="color" />
</template>

<script setup>
import { ref } from 'vue'
const color = ref(null)
</script>
```

### With Default Color

```vue
<template>
  <ColorPicker v-model="color" />
</template>

<script setup>
import { ref } from 'vue'
const color = ref('#3b82f6')
</script>
```

### Custom Swatches

```vue
<template>
  <ColorPicker
    v-model="color"
    :swatches="brandColors"
    placeholder="Select brand color"
  />
</template>

<script setup>
import { ref } from 'vue'

const color = ref(null)
const brandColors = [
  '#1a365d', // Navy
  '#2c5282', // Blue
  '#2a4365', // Dark Blue
  '#1e40af', // Royal Blue
  '#1e3a8a', // Deep Blue
  '#ffffff', // White
  '#f7fafc', // Light Gray
  '#000000', // Black
]
</script>
```

### Swatches Only (No Input)

```vue
<template>
  <ColorPicker
    v-model="color"
    :show-input="false"
    placeholder="Pick from swatches"
  />
</template>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <ColorPicker v-model="color" size="sm" placeholder="Small" />
    <ColorPicker v-model="color" size="md" placeholder="Medium" />
    <ColorPicker v-model="color" size="lg" placeholder="Large" />
  </div>
</template>
```

### With Error State

```vue
<template>
  <ColorPicker
    v-model="color"
    :error="!color ? 'Please select a color' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <ColorPicker
    v-model="color"
    disabled
  />
</template>
```

### Non-clearable

```vue
<template>
  <ColorPicker
    v-model="color"
    :clearable="false"
  />
</template>
```

### In a Form (Theme Customization)

```vue
<template>
  <form @submit.prevent="saveTheme">
    <FormGroup label="Primary Color" required>
      <ColorPicker
        v-model="theme.primaryColor"
        :swatches="primarySwatches"
        :error="errors.primaryColor"
      />
    </FormGroup>

    <FormGroup label="Accent Color">
      <ColorPicker
        v-model="theme.accentColor"
        :swatches="accentSwatches"
      />
    </FormGroup>

    <FormGroup label="Background Color">
      <ColorPicker
        v-model="theme.backgroundColor"
        :swatches="['#ffffff', '#f9fafb', '#f3f4f6', '#e5e7eb', '#1f2937', '#111827', '#000000']"
      />
    </FormGroup>
  </form>
</template>
```

### With Color Preview

```vue
<template>
  <div class="space-y-4">
    <ColorPicker v-model="color" />

    <div
      v-if="color"
      class="h-20 w-full rounded-lg border"
      :style="{ backgroundColor: color }"
    >
      <span class="p-2 text-white mix-blend-difference">
        {{ color }}
      </span>
    </div>
  </div>
</template>
```

## Features

- Preset color swatches for quick selection
- Native browser color picker integration
- Hex color input with validation
- Visual selection indicator on swatches
- Color preview in the trigger button
- Supports any valid hex color (#RGB or #RRGGBB)
- Auto-prefix hex input with #
- Keyboard navigation support
- Click outside to close

## Playground

Try the ColorPicker component:

<LiveDemo>
  <DemoColorPicker />

  <template #code>

```vue
<script setup>
import { ColorPicker } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const color = ref('#3b82f6')
</script>

<template>
    <ColorPicker v-model="color" placeholder="Choose a color" />
</template>
```

  </template>
</LiveDemo>
