# CheckboxGroup

A component for managing multiple checkboxes with a shared array value. Ideal for multi-select scenarios with predefined options.

## Import

```vue
<script setup>
import { CheckboxGroup } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Array` | `[]` | Array of selected values (v-model) |
| `options` | `Array` | `[]` | Array of options (strings, numbers, or objects) |
| `size` | `String` | `'md'` | Checkbox size. Allowed: `'sm'`, `'md'`, `'lg'` |
| `disabled` | `Boolean` | `false` | Disables all checkboxes |
| `error` | `String \| Boolean` | `null` | Error state for all checkboxes |
| `inline` | `Boolean` | `false` | Displays checkboxes horizontally |
| `optionLabel` | `String` | `'label'` | Property name to use for option label |
| `optionValue` | `String` | `'value'` | Property name to use for option value |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Array` | Emitted when selection changes |
| `change` | `Array` | Emitted when selection changes |

## Option Formats

```vue
<!-- Simple strings -->
<CheckboxGroup :options="['Red', 'Green', 'Blue']" />

<!-- Simple numbers -->
<CheckboxGroup :options="[1, 2, 3, 4, 5]" />

<!-- Objects with label/value -->
<CheckboxGroup :options="[
    { label: 'Email', value: 'email' },
    { label: 'SMS', value: 'sms' },
    { label: 'Push', value: 'push' }
]" />

<!-- With description -->
<CheckboxGroup :options="[
    { label: 'Email', value: 'email', description: 'Receive via email' },
    { label: 'SMS', value: 'sms', description: 'Receive via text message' }
]" />

<!-- With disabled options -->
<CheckboxGroup :options="[
    { label: 'Available', value: 'available' },
    { label: 'Coming Soon', value: 'soon', disabled: true }
]" />
```

## Basic Example

```vue
<script setup>
import { CheckboxGroup } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const selectedNotifications = ref([])
const notifications = [
    { value: 'email', label: 'Email notifications' },
    { value: 'sms', label: 'SMS notifications' },
    { value: 'push', label: 'Push notifications' },
    { value: 'slack', label: 'Slack notifications' }
]
</script>

<template>
    <CheckboxGroup
        v-model="selectedNotifications"
        :options="notifications"
    />
</template>
```

## Inline Layout

Display checkboxes horizontally:

```vue
<CheckboxGroup
    v-model="selectedDays"
    :options="['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']"
    inline
/>
```

## Sizes

```vue
<!-- Small -->
<CheckboxGroup v-model="values" :options="options" size="sm" />

<!-- Medium (default) -->
<CheckboxGroup v-model="values" :options="options" size="md" />

<!-- Large -->
<CheckboxGroup v-model="values" :options="options" size="lg" />
```

## With Descriptions

```vue
<script setup>
const features = [
    {
        value: 'analytics',
        label: 'Analytics',
        description: 'Track user behavior and metrics'
    },
    {
        value: 'reports',
        label: 'Reports',
        description: 'Generate detailed reports'
    },
    {
        value: 'exports',
        label: 'Data Export',
        description: 'Export data in various formats'
    }
]
</script>

<template>
    <CheckboxGroup v-model="selectedFeatures" :options="features" />
</template>
```

## Error State

```vue
<FormGroup label="Notifications" :error="form.errors.notifications">
    <CheckboxGroup
        v-model="form.notifications"
        :options="notificationOptions"
        :error="form.errors.notifications"
    />
</FormGroup>
```

## Disabled State

```vue
<!-- All disabled -->
<CheckboxGroup v-model="values" :options="options" disabled />

<!-- Individual options disabled -->
<CheckboxGroup
    v-model="values"
    :options="[
        { value: 'a', label: 'Option A' },
        { value: 'b', label: 'Option B', disabled: true },
        { value: 'c', label: 'Option C' }
    ]"
/>
```

## Custom Property Names

```vue
<script setup>
const categories = [
    { id: 1, name: 'Technology' },
    { id: 2, name: 'Design' },
    { id: 3, name: 'Business' }
]
</script>

<template>
    <CheckboxGroup
        v-model="selectedCategories"
        :options="categories"
        option-label="name"
        option-value="id"
    />
</template>
```

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, CheckboxGroup, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    interests: [],
    notifications: []
})

const interestOptions = [
    { value: 'tech', label: 'Technology', description: 'Latest tech news and trends' },
    { value: 'design', label: 'Design', description: 'UI/UX and graphic design' },
    { value: 'business', label: 'Business', description: 'Business strategies and tips' },
    { value: 'lifestyle', label: 'Lifestyle', description: 'Health, travel, and more' }
]

const notificationOptions = [
    { value: 'email', label: 'Email' },
    { value: 'sms', label: 'SMS' },
    { value: 'push', label: 'Push' }
]

const submit = (form) => {
    form.post('/profile/preferences')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Name" required :error="form.errors.name">
            <Input v-model="form.name" :error="form.errors.name" />
        </FormGroup>

        <FormGroup
            label="Interests"
            :error="form.errors.interests"
            hint="Select topics you're interested in"
        >
            <CheckboxGroup
                v-model="form.interests"
                :options="interestOptions"
                :error="form.errors.interests"
            />
        </FormGroup>

        <FormGroup
            label="Notification Preferences"
            :error="form.errors.notifications"
        >
            <CheckboxGroup
                v-model="form.notifications"
                :options="notificationOptions"
                :error="form.errors.notifications"
                inline
            />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Save Preferences
        </Button>
    </Form>
</template>
```

## Select All / Clear Pattern

```vue
<script setup>
import { computed } from 'vue'

const options = [
    { value: 'read', label: 'Read' },
    { value: 'write', label: 'Write' },
    { value: 'delete', label: 'Delete' },
    { value: 'admin', label: 'Admin' }
]

const selectedPermissions = ref([])

const allSelected = computed(() =>
    selectedPermissions.value.length === options.length
)

const selectAll = () => {
    if (allSelected.value) {
        selectedPermissions.value = []
    } else {
        selectedPermissions.value = options.map(o => o.value)
    }
}
</script>

<template>
    <div class="space-y-3">
        <Checkbox
            :model-value="allSelected"
            :indeterminate="selectedPermissions.length > 0 && !allSelected"
            label="Select all permissions"
            @update:model-value="selectAll"
        />

        <div class="ml-6">
            <CheckboxGroup
                v-model="selectedPermissions"
                :options="options"
            />
        </div>
    </div>
</template>
```

## Comparing CheckboxGroup vs Multiple Checkboxes

### Use CheckboxGroup when:
- Options come from an array/API
- Values should be collected into an array
- Options are similar/parallel choices

### Use individual Checkboxes when:
- Each checkbox controls a different field
- Values are independent boolean flags
- Checkboxes need different behaviors

## Styling

The CheckboxGroup component:
- Renders vertically by default with `gap-2`
- Inline mode uses `flex-wrap` with `gap-x-6 gap-y-2`
- Each checkbox inherits [Checkbox](./checkbox.md) styling

## Accessibility

- Each checkbox is independently focusable
- Full keyboard navigation with Tab
- Space key toggles individual checkboxes
- Uses [Checkbox](./checkbox.md) component with all its ARIA attributes

## Playground

Try the CheckboxGroup component:

<LiveDemo>
  <DemoCheckboxGroup />

  <template #code>

```vue
<script setup>
import { CheckboxGroup } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const selectedNotifications = ref([])
const notifications = [
    { value: 'email', label: 'Email notifications' },
    { value: 'sms', label: 'SMS notifications' },
    { value: 'push', label: 'Push notifications' },
    { value: 'slack', label: 'Slack notifications' }
]
</script>

<template>
    <CheckboxGroup
        v-model="selectedNotifications"
        :options="notifications"
    />
</template>
```

  </template>
</LiveDemo>
