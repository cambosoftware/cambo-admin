# SliderInput

A slider input component for selecting numeric values within a range with customizable appearance.

## Import

```vue
<script setup>
import { SliderInput } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Number` | `0` | The selected value (v-model) |
| `min` | `Number` | `0` | Minimum value |
| `max` | `Number` | `100` | Maximum value |
| `step` | `Number` | `1` | Step increment |
| `size` | `String` | `'md'` | Slider size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the slider |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `showValue` | `Boolean` | `false` | Show current value above slider |
| `showMinMax` | `Boolean` | `false` | Show min/max labels below slider |
| `formatValue` | `Function` | `null` | Custom value formatting function |
| `color` | `String` | `'primary'` | Color theme: `'primary'`, `'success'`, `'danger'`, `'warning'`, `'info'` |
| `marks` | `Array` | `[]` | Array of mark values or objects `{value, label}` |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Number` | Emitted when value changes (for v-model) |
| `change` | `Number` | Emitted when value changes |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <SliderInput v-model="value" />
</template>

<script setup>
import { ref } from 'vue'
const value = ref(50)
</script>
```

### With Value Display

```vue
<template>
  <SliderInput v-model="value" show-value />
</template>
```

### With Min/Max Labels

```vue
<template>
  <SliderInput v-model="value" show-min-max />
</template>
```

### Custom Range

```vue
<template>
  <SliderInput
    v-model="temperature"
    :min="-20"
    :max="40"
    :step="1"
    show-value
    show-min-max
  />
</template>

<script setup>
import { ref } from 'vue'
const temperature = ref(20)
</script>
```

### With Custom Step

```vue
<template>
  <div class="space-y-4">
    <!-- Step of 5 -->
    <SliderInput v-model="value1" :step="5" show-value />

    <!-- Step of 10 -->
    <SliderInput v-model="value2" :step="10" show-value />

    <!-- Decimal step -->
    <SliderInput v-model="value3" :min="0" :max="1" :step="0.1" show-value />
  </div>
</template>
```

### With Custom Formatting

```vue
<template>
  <div class="space-y-4">
    <!-- Percentage -->
    <SliderInput
      v-model="percent"
      :format-value="v => v + '%'"
      show-value
    />

    <!-- Currency -->
    <SliderInput
      v-model="price"
      :min="0"
      :max="1000"
      :format-value="v => '$' + v"
      show-value
    />

    <!-- Temperature -->
    <SliderInput
      v-model="temp"
      :min="0"
      :max="100"
      :format-value="v => v + ' C'"
      show-value
    />
  </div>
</template>
```

### Different Colors

```vue
<template>
  <div class="space-y-4">
    <SliderInput v-model="value" color="primary" />
    <SliderInput v-model="value" color="success" />
    <SliderInput v-model="value" color="danger" />
    <SliderInput v-model="value" color="warning" />
    <SliderInput v-model="value" color="info" />
  </div>
</template>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <SliderInput v-model="value" size="sm" />
    <SliderInput v-model="value" size="md" />
    <SliderInput v-model="value" size="lg" />
  </div>
</template>
```

### With Marks

```vue
<template>
  <SliderInput
    v-model="value"
    :marks="[0, 25, 50, 75, 100]"
    show-min-max
  />
</template>
```

### With Labeled Marks

```vue
<template>
  <SliderInput
    v-model="priority"
    :min="1"
    :max="5"
    :marks="[
      { value: 1, label: 'Low' },
      { value: 3, label: 'Medium' },
      { value: 5, label: 'High' }
    ]"
  />
</template>
```

### With Error State

```vue
<template>
  <SliderInput
    v-model="value"
    :error="value < 25 ? 'Value must be at least 25' : false"
    show-value
  />
</template>
```

### Disabled State

```vue
<template>
  <SliderInput
    v-model="value"
    disabled
    show-value
  />
</template>
```

### In a Form (Volume Control)

```vue
<template>
  <FormGroup label="Volume">
    <SliderInput
      v-model="settings.volume"
      :format-value="v => v + '%'"
      show-value
    />
  </FormGroup>
</template>
```

### Image Quality Selector

```vue
<template>
  <FormGroup label="Image Quality">
    <SliderInput
      v-model="quality"
      :min="1"
      :max="100"
      :marks="[
        { value: 25, label: 'Low' },
        { value: 50, label: 'Medium' },
        { value: 75, label: 'High' },
        { value: 100, label: 'Best' }
      ]"
      :format-value="v => v + '%'"
      show-value
    />
    <p class="mt-2 text-xs text-gray-500">
      File size: ~{{ Math.round(quality * 0.5) }}KB
    </p>
  </FormGroup>
</template>

<script setup>
import { ref } from 'vue'
const quality = ref(75)
</script>
```

### Font Size Slider

```vue
<template>
  <div>
    <SliderInput
      v-model="fontSize"
      :min="12"
      :max="32"
      :format-value="v => v + 'px'"
      show-value
    />
    <p :style="{ fontSize: fontSize + 'px' }" class="mt-4">
      Preview text at {{ fontSize }}px
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const fontSize = ref(16)
</script>
```

## Features

- Native range input under custom styling
- Customizable min/max/step values
- Multiple color themes
- Three size variants
- Value display option
- Min/max labels option
- Custom value formatting
- Mark indicators with labels
- Filled track visualization
- Custom thumb styling
- Keyboard accessible
- Disabled state support

## Playground

Try the SliderInput component:

<LiveDemo>
  <DemoSliderInput />

  <template #code>

```vue
<script setup>
import { SliderInput } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const value = ref(50)
</script>

<template>
    <SliderInput
        v-model="value"
        :min="0"
        :max="100"
        show-value
        show-min-max
    />
</template>
```

  </template>
</LiveDemo>
