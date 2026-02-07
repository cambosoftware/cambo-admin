# DatePicker

A customizable date picker component with calendar dropdown, month/year navigation, and locale support.

## Import

```vue
<script setup>
import { DatePicker } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String` | `null` | The selected date in YYYY-MM-DD format (v-model) |
| `placeholder` | `String` | `'Select a date'` | Placeholder text when no date is selected |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the date picker |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `minDate` | `String` | `null` | Minimum selectable date (YYYY-MM-DD format) |
| `maxDate` | `String` | `null` | Maximum selectable date (YYYY-MM-DD format) |
| `format` | `String` | `'dd/MM/yyyy'` | Display format for the selected date |
| `clearable` | `Boolean` | `true` | Show clear button when a date is selected |
| `firstDayOfWeek` | `Number` | `1` | First day of week (0 = Sunday, 1 = Monday) |
| `locale` | `String` | `'fr-FR'` | Locale for date formatting |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| null` | Emitted when date changes (for v-model) |
| `change` | `String \| null` | Emitted when date changes |
| `open` | - | Emitted when calendar dropdown opens |
| `close` | - | Emitted when calendar dropdown closes |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <DatePicker v-model="date" />
</template>

<script setup>
import { ref } from 'vue'
const date = ref(null)
</script>
```

### With Min and Max Dates

```vue
<template>
  <DatePicker
    v-model="date"
    min-date="2024-01-01"
    max-date="2024-12-31"
    placeholder="Select a date in 2024"
  />
</template>

<script setup>
import { ref } from 'vue'
const date = ref(null)
</script>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <DatePicker v-model="date" size="sm" placeholder="Small" />
    <DatePicker v-model="date" size="md" placeholder="Medium" />
    <DatePicker v-model="date" size="lg" placeholder="Large" />
  </div>
</template>
```

### Custom Format

```vue
<template>
  <DatePicker
    v-model="date"
    format="MM/dd/yyyy"
    placeholder="MM/DD/YYYY"
  />
</template>
```

### With Error State

```vue
<template>
  <DatePicker
    v-model="date"
    :error="!date ? 'Please select a date' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <DatePicker
    v-model="date"
    disabled
    placeholder="Disabled date picker"
  />
</template>
```

### Non-clearable

```vue
<template>
  <DatePicker
    v-model="date"
    :clearable="false"
    placeholder="Cannot be cleared"
  />
</template>
```

### Starting Week on Sunday

```vue
<template>
  <DatePicker
    v-model="date"
    :first-day-of-week="0"
    placeholder="Week starts on Sunday"
  />
</template>
```

## Features

- Interactive calendar dropdown with smooth animations
- Navigate by month and year
- Quick select today's date
- Support for min/max date constraints
- Customizable date display format
- Keyboard navigation support (Enter to toggle, Escape to close)
- Click outside to close
- Accessible with proper focus management

## Playground

Try the DatePicker component:

<LiveDemo>
  <DemoDatePicker />

  <template #code>

```vue
<script setup>
import { DatePicker } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const date = ref(null)
</script>

<template>
    <DatePicker v-model="date" placeholder="Select a date" />
</template>
```

  </template>
</LiveDemo>
