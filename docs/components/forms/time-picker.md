# TimePicker

A time picker component with scrollable hour and minute selection, supporting 12/24 hour formats.

## Import

```vue
<script setup>
import { TimePicker } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String` | `null` | The selected time in HH:mm format (v-model) |
| `placeholder` | `String` | `'Select a time'` | Placeholder text when no time is selected |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the time picker |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `minuteStep` | `Number` | `5` | Step interval for minutes (e.g., 5, 10, 15, 30) |
| `minTime` | `String` | `null` | Minimum selectable time (HH:mm format) |
| `maxTime` | `String` | `null` | Maximum selectable time (HH:mm format) |
| `clearable` | `Boolean` | `true` | Show clear button when a time is selected |
| `format24h` | `Boolean` | `true` | Use 24-hour format (false for 12-hour with AM/PM) |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| null` | Emitted when time changes (for v-model) |
| `change` | `String \| null` | Emitted when time changes |
| `open` | - | Emitted when dropdown opens |
| `close` | - | Emitted when dropdown closes |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <TimePicker v-model="time" />
</template>

<script setup>
import { ref } from 'vue'
const time = ref(null)
</script>
```

### With Default Time

```vue
<template>
  <TimePicker v-model="time" />
</template>

<script setup>
import { ref } from 'vue'
const time = ref('09:00')
</script>
```

### 12-Hour Format

```vue
<template>
  <TimePicker
    v-model="time"
    :format24h="false"
    placeholder="Select time (AM/PM)"
  />
</template>
```

### Custom Minute Step

```vue
<template>
  <div class="space-y-4">
    <!-- Every 15 minutes -->
    <TimePicker v-model="time" :minute-step="15" placeholder="15-min intervals" />

    <!-- Every 30 minutes -->
    <TimePicker v-model="time" :minute-step="30" placeholder="30-min intervals" />

    <!-- Every minute -->
    <TimePicker v-model="time" :minute-step="1" placeholder="1-min intervals" />
  </div>
</template>
```

### With Time Constraints

```vue
<template>
  <TimePicker
    v-model="time"
    min-time="08:00"
    max-time="18:00"
    placeholder="Business hours only"
  />
</template>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <TimePicker v-model="time" size="sm" placeholder="Small" />
    <TimePicker v-model="time" size="md" placeholder="Medium" />
    <TimePicker v-model="time" size="lg" placeholder="Large" />
  </div>
</template>
```

### With Error State

```vue
<template>
  <TimePicker
    v-model="time"
    :error="!time ? 'Please select a time' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <TimePicker
    v-model="time"
    disabled
    placeholder="Disabled"
  />
</template>
```

### In a Form

```vue
<template>
  <FormGroup label="Appointment Time" required>
    <TimePicker
      v-model="form.appointmentTime"
      min-time="09:00"
      max-time="17:00"
      :minute-step="30"
      :error="errors.appointmentTime"
    />
  </FormGroup>
</template>
```

## Features

- Scrollable hour and minute columns
- Auto-scroll to selected time when opening
- Quick "Now" button to select current time
- Support for 12-hour and 24-hour formats
- Configurable minute intervals
- Time range constraints (min/max)
- Keyboard navigation (Enter to toggle, Escape to close)
- Click outside to close

## Playground

Try the TimePicker component:

<LiveDemo>
  <DemoTimePicker />

  <template #code>

```vue
<script setup>
import { TimePicker } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const time = ref(null)
</script>

<template>
    <TimePicker v-model="time" placeholder="Select a time" />
</template>
```

  </template>
</LiveDemo>
