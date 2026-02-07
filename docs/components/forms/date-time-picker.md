# DateTimePicker

A combined date and time picker component with calendar and time selection in a single dropdown.

## Import

```vue
<script setup>
import { DateTimePicker } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String` | `null` | The selected datetime in 'YYYY-MM-DD HH:mm' format (v-model) |
| `placeholder` | `String` | `'Select date and time'` | Placeholder text when no datetime is selected |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the datetime picker |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `minDate` | `String` | `null` | Minimum selectable date (YYYY-MM-DD format) |
| `maxDate` | `String` | `null` | Maximum selectable date (YYYY-MM-DD format) |
| `minuteStep` | `Number` | `5` | Step interval for minutes |
| `clearable` | `Boolean` | `true` | Show clear button when a datetime is selected |
| `firstDayOfWeek` | `Number` | `1` | First day of week (0 = Sunday, 1 = Monday) |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| null` | Emitted when datetime changes (for v-model) |
| `change` | `String \| null` | Emitted when datetime changes |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <DateTimePicker v-model="datetime" />
</template>

<script setup>
import { ref } from 'vue'
const datetime = ref(null)
</script>
```

### With Default Value

```vue
<template>
  <DateTimePicker v-model="datetime" />
</template>

<script setup>
import { ref } from 'vue'
const datetime = ref('2024-06-15 14:30')
</script>
```

### With Date Constraints

```vue
<template>
  <DateTimePicker
    v-model="datetime"
    min-date="2024-01-01"
    max-date="2024-12-31"
    placeholder="Select a datetime in 2024"
  />
</template>
```

### Custom Minute Step

```vue
<template>
  <DateTimePicker
    v-model="datetime"
    :minute-step="15"
    placeholder="15-minute intervals"
  />
</template>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <DateTimePicker v-model="datetime" size="sm" placeholder="Small" />
    <DateTimePicker v-model="datetime" size="md" placeholder="Medium" />
    <DateTimePicker v-model="datetime" size="lg" placeholder="Large" />
  </div>
</template>
```

### With Error State

```vue
<template>
  <DateTimePicker
    v-model="datetime"
    :error="!datetime ? 'Please select a date and time' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <DateTimePicker
    v-model="datetime"
    disabled
    placeholder="Disabled"
  />
</template>
```

### Starting Week on Sunday

```vue
<template>
  <DateTimePicker
    v-model="datetime"
    :first-day-of-week="0"
  />
</template>
```

### In a Form (Event Scheduling)

```vue
<template>
  <form @submit.prevent="submit">
    <FormGroup label="Event Start" required>
      <DateTimePicker
        v-model="form.startAt"
        min-date="2024-01-01"
        :minute-step="15"
        :error="errors.startAt"
      />
    </FormGroup>

    <FormGroup label="Event End" required>
      <DateTimePicker
        v-model="form.endAt"
        :min-date="form.startAt?.split(' ')[0]"
        :minute-step="15"
        :error="errors.endAt"
      />
    </FormGroup>
  </form>
</template>
```

## Features

- Combined calendar and time selection in one interface
- Side-by-side layout with calendar on left, time on right
- Scrollable hour and minute columns
- Quick "Now" button to select current date and time
- OK button to confirm selection
- Support for date constraints (min/max)
- Configurable minute intervals
- Today highlighting on calendar
- Keyboard navigation support
- Click outside to close

## Playground

Try the DateTimePicker component:

<LiveDemo>
  <DemoDateTimePicker />

  <template #code>

```vue
<script setup>
import { DateTimePicker } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const datetime = ref(null)
</script>

<template>
    <DateTimePicker v-model="datetime" placeholder="Select date and time" />
</template>
```

  </template>
</LiveDemo>
