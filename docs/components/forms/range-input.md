# RangeInput

A dual-handle range slider for selecting a range of values (min and max).

## Import

```vue
<script setup>
import { RangeInput } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Array` | `[0, 100]` | The selected range as [low, high] (v-model) |
| `min` | `Number` | `0` | Minimum value |
| `max` | `Number` | `100` | Maximum value |
| `step` | `Number` | `1` | Step increment |
| `size` | `String` | `'md'` | Slider size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the slider |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `showValues` | `Boolean` | `false` | Show current values above slider |
| `showMinMax` | `Boolean` | `false` | Show min/max labels below slider |
| `formatValue` | `Function` | `null` | Custom value formatting function |
| `color` | `String` | `'primary'` | Color theme: `'primary'`, `'success'`, `'danger'`, `'warning'`, `'info'` |
| `minGap` | `Number` | `0` | Minimum gap between low and high values |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Array` | Emitted when range changes (for v-model) |
| `change` | `Array` | Emitted when range changes |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <RangeInput v-model="range" />
</template>

<script setup>
import { ref } from 'vue'
const range = ref([25, 75])
</script>
```

### With Value Display

```vue
<template>
  <RangeInput v-model="range" show-values />
</template>
```

### With Min/Max Labels

```vue
<template>
  <RangeInput v-model="range" show-min-max />
</template>
```

### Custom Range

```vue
<template>
  <RangeInput
    v-model="priceRange"
    :min="0"
    :max="1000"
    :step="10"
    show-values
    show-min-max
  />
</template>

<script setup>
import { ref } from 'vue'
const priceRange = ref([100, 500])
</script>
```

### With Minimum Gap

```vue
<template>
  <RangeInput
    v-model="range"
    :min-gap="10"
    show-values
  />
</template>

<script setup>
import { ref } from 'vue'
const range = ref([30, 70])
</script>
```

### With Custom Formatting

```vue
<template>
  <div class="space-y-4">
    <!-- Price range -->
    <RangeInput
      v-model="priceRange"
      :min="0"
      :max="1000"
      :format-value="v => '$' + v"
      show-values
    />

    <!-- Percentage range -->
    <RangeInput
      v-model="percentRange"
      :format-value="v => v + '%'"
      show-values
    />

    <!-- Year range -->
    <RangeInput
      v-model="yearRange"
      :min="2000"
      :max="2024"
      show-values
    />
  </div>
</template>
```

### Different Colors

```vue
<template>
  <div class="space-y-4">
    <RangeInput v-model="range" color="primary" />
    <RangeInput v-model="range" color="success" />
    <RangeInput v-model="range" color="danger" />
    <RangeInput v-model="range" color="warning" />
    <RangeInput v-model="range" color="info" />
  </div>
</template>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <RangeInput v-model="range" size="sm" />
    <RangeInput v-model="range" size="md" />
    <RangeInput v-model="range" size="lg" />
  </div>
</template>
```

### With Error State

```vue
<template>
  <RangeInput
    v-model="range"
    :error="range[1] - range[0] < 20 ? 'Range must be at least 20' : false"
    show-values
  />
</template>
```

### Disabled State

```vue
<template>
  <RangeInput
    v-model="range"
    disabled
    show-values
  />
</template>
```

### In a Form (Price Filter)

```vue
<template>
  <FormGroup label="Price Range">
    <RangeInput
      v-model="filters.price"
      :min="0"
      :max="1000"
      :step="10"
      :format-value="v => '$' + v"
      show-values
    />
  </FormGroup>
</template>
```

### Age Filter

```vue
<template>
  <FormGroup label="Age Range">
    <RangeInput
      v-model="ageRange"
      :min="18"
      :max="100"
      :min-gap="5"
      :format-value="v => v + ' years'"
      show-values
    />
  </FormGroup>
</template>

<script setup>
import { ref } from 'vue'
const ageRange = ref([25, 45])
</script>
```

### Salary Range Selector

```vue
<template>
  <div class="space-y-4">
    <FormGroup label="Salary Range (Annual)">
      <RangeInput
        v-model="salary"
        :min="30000"
        :max="200000"
        :step="5000"
        :min-gap="10000"
        :format-value="formatSalary"
        show-values
        show-min-max
      />
    </FormGroup>

    <p class="text-sm text-gray-600">
      Looking for jobs between {{ formatSalary(salary[0]) }} and {{ formatSalary(salary[1]) }}
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const salary = ref([50000, 100000])

const formatSalary = (v) => {
  return '$' + (v / 1000) + 'k'
}
</script>
```

### Date Range (Year Filter)

```vue
<template>
  <FormGroup label="Year Published">
    <RangeInput
      v-model="yearRange"
      :min="1990"
      :max="2024"
      show-values
    />
  </FormGroup>
</template>

<script setup>
import { ref } from 'vue'
const yearRange = ref([2010, 2024])
</script>
```

### Product Filters

```vue
<template>
  <div class="space-y-6">
    <FormGroup label="Price">
      <RangeInput
        v-model="filters.price"
        :min="0"
        :max="500"
        :format-value="v => '$' + v"
        show-values
      />
    </FormGroup>

    <FormGroup label="Rating">
      <RangeInput
        v-model="filters.rating"
        :min="0"
        :max="5"
        :step="0.5"
        show-values
      />
    </FormGroup>

    <FormGroup label="Discount">
      <RangeInput
        v-model="filters.discount"
        :min="0"
        :max="100"
        :format-value="v => v + '%'"
        color="success"
        show-values
      />
    </FormGroup>
  </div>
</template>
```

## Keyboard Navigation

| Key | Action |
|-----|--------|
| `ArrowRight` / `ArrowUp` | Increase focused thumb value |
| `ArrowLeft` / `ArrowDown` | Decrease focused thumb value |
| `Tab` | Move focus between thumbs |

## Features

- Dual handles for range selection
- Pointer drag interaction
- Click on track to move nearest thumb
- Minimum gap enforcement
- Handles cannot cross each other
- Customizable step increments
- Multiple color themes
- Three size variants
- Value display options
- Custom value formatting
- Keyboard accessible with ARIA
- Filled track between handles
- Smooth drag interaction

## Playground

Try the RangeInput component:

<LiveDemo>
  <DemoRangeInput />

  <template #code>

```vue
<script setup>
import { RangeInput } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const range = ref([25, 75])
</script>

<template>
    <RangeInput
        v-model="range"
        :min="0"
        :max="100"
        show-values
        show-min-max
    />
</template>
```

  </template>
</LiveDemo>
