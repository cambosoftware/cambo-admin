# Select

A custom dropdown select component with keyboard navigation, clearable option, and customizable option rendering.

## Import

```vue
<script setup>
import { Select } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String \| Number \| null` | `null` | The selected value (v-model) |
| `options` | `Array` | `[]` | Array of options (strings, numbers, or objects) |
| `placeholder` | `String` | `'Select...'` | Placeholder text when no option is selected |
| `size` | `String` | `'md'` | Select size. Allowed: `'sm'`, `'md'`, `'lg'` |
| `disabled` | `Boolean` | `false` | Disables the select |
| `error` | `String \| Boolean` | `null` | Error state or message. When truthy, shows error styling |
| `clearable` | `Boolean` | `false` | Shows a clear button when an option is selected |
| `optionLabel` | `String` | `'label'` | Property name to use for option label (when options are objects) |
| `optionValue` | `String` | `'value'` | Property name to use for option value (when options are objects) |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| Number \| null` | Emitted when selection changes |
| `change` | `String \| Number \| null` | Emitted when selection changes (same payload) |

## Slots

### option

Customize how individual options are rendered in the dropdown.

| Slot Prop | Type | Description |
|-----------|------|-------------|
| `option` | `Object` | The normalized option object with `label`, `value`, `disabled` |

## Option Formats

The `options` prop accepts various formats:

```vue
<!-- Simple strings -->
<Select :options="['Option A', 'Option B', 'Option C']" />

<!-- Simple numbers -->
<Select :options="[1, 2, 3, 4, 5]" />

<!-- Objects with label/value -->
<Select :options="[
    { label: 'Active', value: 'active' },
    { label: 'Inactive', value: 'inactive' },
    { label: 'Pending', value: 'pending' }
]" />

<!-- Custom property names -->
<Select
    :options="users"
    option-label="name"
    option-value="id"
/>

<!-- With disabled options -->
<Select :options="[
    { label: 'Available', value: 'available' },
    { label: 'Coming Soon', value: 'soon', disabled: true },
    { label: 'Sold Out', value: 'sold', disabled: true }
]" />
```

## Basic Example

```vue
<script setup>
import { Select } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const status = ref(null)
const options = [
    { label: 'Active', value: 'active' },
    { label: 'Inactive', value: 'inactive' },
    { label: 'Pending', value: 'pending' }
]
</script>

<template>
    <Select v-model="status" :options="options" placeholder="Select status" />
</template>
```

## Sizes

```vue
<!-- Small -->
<Select v-model="value" :options="options" size="sm" />

<!-- Medium (default) -->
<Select v-model="value" :options="options" size="md" />

<!-- Large -->
<Select v-model="value" :options="options" size="lg" />
```

## Clearable Select

```vue
<Select
    v-model="country"
    :options="countries"
    clearable
    placeholder="Select country"
/>
```

## Error State

```vue
<FormGroup label="Status" :error="form.errors.status">
    <Select
        v-model="form.status"
        :options="statusOptions"
        :error="form.errors.status"
        placeholder="Select status"
    />
</FormGroup>
```

## Disabled State

```vue
<Select
    v-model="value"
    :options="options"
    disabled
/>
```

## Custom Option Rendering

```vue
<script setup>
const countries = [
    { value: 'us', label: 'United States', flag: '🇺🇸' },
    { value: 'uk', label: 'United Kingdom', flag: '🇬🇧' },
    { value: 'fr', label: 'France', flag: '🇫🇷' }
]
</script>

<template>
    <Select v-model="country" :options="countries">
        <template #option="{ option }">
            <span class="flex items-center gap-2">
                <span>{{ option.flag }}</span>
                <span>{{ option.label }}</span>
            </span>
        </template>
    </Select>
</template>
```

## Custom Property Names

When your data uses different property names:

```vue
<script setup>
const users = [
    { id: 1, name: 'John Doe' },
    { id: 2, name: 'Jane Smith' },
    { id: 3, name: 'Bob Wilson' }
]
</script>

<template>
    <Select
        v-model="selectedUser"
        :options="users"
        option-label="name"
        option-value="id"
        placeholder="Select user"
    />
</template>
```

## Keyboard Navigation

The Select component supports full keyboard navigation:

| Key | Action |
|-----|--------|
| `Enter` / `Space` | Open dropdown (when closed) or select highlighted option |
| `ArrowDown` | Open dropdown or move to next option |
| `ArrowUp` | Open dropdown or move to previous option |
| `Escape` | Close dropdown |

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, Select, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    department: null,
    role: null
})

const departments = [
    { value: 'engineering', label: 'Engineering' },
    { value: 'marketing', label: 'Marketing' },
    { value: 'sales', label: 'Sales' },
    { value: 'hr', label: 'Human Resources' }
]

const roles = [
    { value: 'admin', label: 'Administrator' },
    { value: 'manager', label: 'Manager' },
    { value: 'employee', label: 'Employee' }
]

const submit = (form) => {
    form.post('/employees')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Name" required :error="form.errors.name">
            <Input v-model="form.name" :error="form.errors.name" />
        </FormGroup>

        <FormGroup label="Department" required :error="form.errors.department">
            <Select
                v-model="form.department"
                :options="departments"
                :error="form.errors.department"
                placeholder="Select department"
            />
        </FormGroup>

        <FormGroup label="Role" required :error="form.errors.role">
            <Select
                v-model="form.role"
                :options="roles"
                :error="form.errors.role"
                clearable
                placeholder="Select role"
            />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Add Employee
        </Button>
    </Form>
</template>
```

## Styling

The Select component includes:
- Custom dropdown with smooth animations
- Rounded corners (`rounded-lg`)
- Border with focus ring
- Hover and selected states for options
- Dark mode support (trigger only)
- Dropdown positioned with `z-50`

### Size Classes

| Size | Padding | Font Size |
|------|---------|-----------|
| `sm` | `px-2.5 py-1.5` | `text-xs` |
| `md` | `px-3 py-2` | `text-sm` |
| `lg` | `px-4 py-2.5` | `text-base` |

## Accessibility

- Uses `role="listbox"` for dropdown
- Uses `role="option"` for each option
- Includes `aria-selected` attribute
- Full keyboard navigation support
- Click outside closes dropdown
- Tab navigation works correctly

## Playground

Try the select component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 20rem;">
    <DemoSelect :options="['Option 1', 'Option 2', 'Option 3']" placeholder="Select an option" />
  </div>

  <template #code>

```vue
<Select
  v-model="selected"
  :options="['Option 1', 'Option 2', 'Option 3']"
  placeholder="Select an option"
/>
```

  </template>
</LiveDemo>

### Select Sizes

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 20rem;">
    <DemoSelect :options="['Small', 'Medium', 'Large']" size="sm" placeholder="Small select" />
    <DemoSelect :options="['Small', 'Medium', 'Large']" size="md" placeholder="Medium select" />
    <DemoSelect :options="['Small', 'Medium', 'Large']" size="lg" placeholder="Large select" />
  </div>

  <template #code>

```vue
<Select :options="options" size="sm" placeholder="Small select" />
<Select :options="options" size="md" placeholder="Medium select" />
<Select :options="options" size="lg" placeholder="Large select" />
```

  </template>
</LiveDemo>

### Select States

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 20rem;">
    <DemoSelect :options="['Active', 'Inactive']" placeholder="Normal" />
    <DemoSelect :options="['Active', 'Inactive']" placeholder="Disabled" disabled />
    <DemoSelect :options="['Active', 'Inactive']" placeholder="Error state" error />
  </div>

  <template #code>

```vue
<Select :options="options" placeholder="Normal" />
<Select :options="options" placeholder="Disabled" disabled />
<Select :options="options" placeholder="Error state" error />
```

  </template>
</LiveDemo>
