# NumberInput

A numeric input component with increment/decrement controls, step support, and precision control.

## Import

```vue
<script setup>
import { NumberInput } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Number \| String \| null` | `null` | The numeric value (v-model) |
| `min` | `Number` | `-Infinity` | Minimum value |
| `max` | `Number` | `Infinity` | Maximum value |
| `step` | `Number` | `1` | Step increment/decrement value |
| `placeholder` | `String` | `null` | Placeholder text |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the input |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `controls` | `Boolean` | `true` | Show increment/decrement buttons |
| `controlsPosition` | `String` | `'sides'` | Position of controls: `'sides'` or `'right'` |
| `precision` | `Number` | `null` | Decimal precision (null = no rounding) |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Number \| null` | Emitted when value changes (for v-model) |
| `change` | `Number \| null` | Emitted when value changes (on blur) |
| `focus` | `FocusEvent` | Emitted when input gains focus |
| `blur` | `FocusEvent` | Emitted when input loses focus |

## Exposed Methods

| Method | Description |
|--------|-------------|
| `focus()` | Focus the input |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <NumberInput v-model="quantity" />
</template>

<script setup>
import { ref } from 'vue'
const quantity = ref(1)
</script>
```

### With Min and Max

```vue
<template>
  <NumberInput
    v-model="quantity"
    :min="0"
    :max="100"
  />
</template>
```

### Custom Step

```vue
<template>
  <div class="space-y-4">
    <!-- Step of 1 (default) -->
    <NumberInput v-model="value1" :step="1" />

    <!-- Step of 5 -->
    <NumberInput v-model="value2" :step="5" />

    <!-- Step of 0.1 -->
    <NumberInput v-model="value3" :step="0.1" :precision="1" />

    <!-- Step of 0.01 -->
    <NumberInput v-model="value4" :step="0.01" :precision="2" />
  </div>
</template>
```

### Controls Position

```vue
<template>
  <div class="space-y-4">
    <!-- Controls on sides (default) -->
    <NumberInput v-model="value" controls-position="sides" />

    <!-- Controls stacked on right -->
    <NumberInput v-model="value" controls-position="right" />
  </div>
</template>
```

### Without Controls

```vue
<template>
  <NumberInput v-model="value" :controls="false" />
</template>
```

### With Precision

```vue
<template>
  <div class="space-y-4">
    <!-- Integer only -->
    <NumberInput v-model="whole" :precision="0" />

    <!-- 2 decimal places -->
    <NumberInput v-model="decimal" :precision="2" />
  </div>
</template>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <NumberInput v-model="value" size="sm" />
    <NumberInput v-model="value" size="md" />
    <NumberInput v-model="value" size="lg" />
  </div>
</template>
```

### With Error State

```vue
<template>
  <NumberInput
    v-model="quantity"
    :min="1"
    :error="quantity < 1 ? 'Minimum quantity is 1' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <NumberInput v-model="value" disabled />
</template>
```

### In a Form (Quantity Selector)

```vue
<template>
  <FormGroup label="Quantity">
    <NumberInput
      v-model="form.quantity"
      :min="1"
      :max="99"
      :error="errors.quantity"
    />
  </FormGroup>
</template>
```

### E-commerce Cart Item

```vue
<template>
  <div class="flex items-center gap-4">
    <img :src="product.image" class="h-16 w-16 rounded" />

    <div class="flex-1">
      <p class="font-medium">{{ product.name }}</p>
      <p class="text-sm text-gray-500">{{ formatPrice(product.price) }}</p>
    </div>

    <NumberInput
      v-model="quantity"
      :min="1"
      :max="product.stock"
      size="sm"
    />

    <p class="w-24 text-right font-medium">
      {{ formatPrice(product.price * quantity) }}
    </p>
  </div>
</template>
```

### Form with Multiple Numbers

```vue
<template>
  <form @submit.prevent="save">
    <div class="grid grid-cols-2 gap-4">
      <FormGroup label="Width (cm)">
        <NumberInput
          v-model="dimensions.width"
          :min="0"
          :step="0.1"
          :precision="1"
        />
      </FormGroup>

      <FormGroup label="Height (cm)">
        <NumberInput
          v-model="dimensions.height"
          :min="0"
          :step="0.1"
          :precision="1"
        />
      </FormGroup>

      <FormGroup label="Depth (cm)">
        <NumberInput
          v-model="dimensions.depth"
          :min="0"
          :step="0.1"
          :precision="1"
        />
      </FormGroup>

      <FormGroup label="Weight (kg)">
        <NumberInput
          v-model="dimensions.weight"
          :min="0"
          :step="0.01"
          :precision="2"
        />
      </FormGroup>
    </div>
  </form>
</template>
```

### Stepper Control

```vue
<template>
  <div class="flex items-center gap-4">
    <span>Adults:</span>
    <NumberInput
      v-model="guests.adults"
      :min="1"
      :max="10"
      size="sm"
    />

    <span>Children:</span>
    <NumberInput
      v-model="guests.children"
      :min="0"
      :max="10"
      size="sm"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'

const guests = ref({
  adults: 2,
  children: 0
})
</script>
```

### Percentage Input

```vue
<template>
  <FormGroup label="Discount (%)">
    <NumberInput
      v-model="discount"
      :min="0"
      :max="100"
      :step="5"
      controls-position="right"
    />
  </FormGroup>
</template>

<script setup>
import { ref } from 'vue'
const discount = ref(0)
</script>
```

### Rating Score Input

```vue
<template>
  <FormGroup label="Score (1-10)">
    <NumberInput
      v-model="score"
      :min="1"
      :max="10"
      :step="0.5"
      :precision="1"
    />
  </FormGroup>
</template>

<script setup>
import { ref } from 'vue'
const score = ref(5)
</script>
```

### Compact Quantity Selector

```vue
<template>
  <NumberInput
    v-model="qty"
    :min="0"
    :max="99"
    size="sm"
    controls-position="sides"
    class="w-32"
  />
</template>
```

## Keyboard Navigation

| Key | Action |
|-----|--------|
| `ArrowUp` | Increment value by step |
| `ArrowDown` | Decrement value by step |

## Features

- Increment/decrement button controls
- Two control positions (sides or stacked right)
- Min/max value constraints
- Custom step increments
- Decimal precision control
- Value clamping on blur
- Keyboard arrow key support
- Numeric keyboard on mobile (inputmode)
- Center-aligned text
- Disabled increment at max
- Disabled decrement at min
- Three size variants
- Focus management

## Playground

Try the NumberInput component:

<LiveDemo>
  <DemoNumberInput />

  <template #code>

```vue
<script setup>
import { NumberInput } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const quantity = ref(1)
</script>

<template>
    <NumberInput
        v-model="quantity"
        :min="1"
        :max="99"
        :step="1"
    />
</template>
```

  </template>
</LiveDemo>
