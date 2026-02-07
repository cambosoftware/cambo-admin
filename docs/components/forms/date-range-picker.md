# DateRangePicker

A date range picker component with dual calendar view for selecting start and end dates, with preset support.

## Import

```vue
<script setup>
import { DateRangePicker } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Array` | `[null, null]` | The selected date range as [startDate, endDate] in YYYY-MM-DD format (v-model) |
| `placeholder` | `String` | `'Select a period'` | Placeholder text when no range is selected |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the date range picker |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `minDate` | `String` | `null` | Minimum selectable date (YYYY-MM-DD format) |
| `maxDate` | `String` | `null` | Maximum selectable date (YYYY-MM-DD format) |
| `clearable` | `Boolean` | `true` | Show clear button when a range is selected |
| `firstDayOfWeek` | `Number` | `1` | First day of week (0 = Sunday, 1 = Monday) |
| `presets` | `Array` | `[]` | Array of preset date ranges |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Array` | Emitted when date range changes (for v-model) |
| `change` | `Array` | Emitted when date range selection is complete |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <DateRangePicker v-model="dateRange" />
</template>

<script setup>
import { ref } from 'vue'
const dateRange = ref([null, null])
</script>
```

### With Presets

```vue
<template>
  <DateRangePicker
    v-model="dateRange"
    :presets="presets"
  />
</template>

<script setup>
import { ref } from 'vue'

const dateRange = ref([null, null])

const today = new Date()
const formatDate = (d) => d.toISOString().split('T')[0]

const presets = [
  {
    label: 'Today',
    start: formatDate(today),
    end: formatDate(today)
  },
  {
    label: 'Last 7 days',
    start: formatDate(new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000)),
    end: formatDate(today)
  },
  {
    label: 'Last 30 days',
    start: formatDate(new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000)),
    end: formatDate(today)
  },
  {
    label: 'This month',
    start: formatDate(new Date(today.getFullYear(), today.getMonth(), 1)),
    end: formatDate(new Date(today.getFullYear(), today.getMonth() + 1, 0))
  },
  {
    label: 'Last month',
    start: formatDate(new Date(today.getFullYear(), today.getMonth() - 1, 1)),
    end: formatDate(new Date(today.getFullYear(), today.getMonth(), 0))
  }
]
</script>
```

### With Min and Max Dates

```vue
<template>
  <DateRangePicker
    v-model="dateRange"
    min-date="2024-01-01"
    max-date="2024-12-31"
    placeholder="Select dates in 2024"
  />
</template>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <DateRangePicker v-model="dateRange" size="sm" />
    <DateRangePicker v-model="dateRange" size="md" />
    <DateRangePicker v-model="dateRange" size="lg" />
  </div>
</template>
```

### With Error State

```vue
<template>
  <DateRangePicker
    v-model="dateRange"
    :error="!dateRange[0] ? 'Please select a date range' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <DateRangePicker
    v-model="dateRange"
    disabled
    placeholder="Disabled"
  />
</template>
```

### In a Form

```vue
<template>
  <FormGroup label="Report Period" required>
    <DateRangePicker
      v-model="form.period"
      :presets="presets"
      :error="errors.period"
    />
  </FormGroup>
</template>
```

## Features

- Dual calendar view for easy range selection
- Visual range highlighting with hover preview
- Preset date ranges for quick selection
- Automatic range swap if end date is before start date
- Support for min/max date constraints
- Keyboard navigation support
- Click outside to close
- Responsive design with side-by-side calendars

## Playground

Try the DateRangePicker component:

<LiveDemo>
  <DemoDateRangePicker />

  <template #code>

```vue
<script setup>
import { DateRangePicker } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const dateRange = ref([null, null])
</script>

<template>
    <DateRangePicker v-model="dateRange" placeholder="Select a period" />
</template>
```

  </template>
</LiveDemo>
