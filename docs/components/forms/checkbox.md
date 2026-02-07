# Checkbox

A customizable checkbox component with support for labels, descriptions, and indeterminate state.

## Import

```vue
<script setup>
import { Checkbox } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Boolean` | `false` | The checked state (v-model) |
| `label` | `String` | `null` | Text label for the checkbox |
| `description` | `String` | `null` | Description text below the label |
| `size` | `String` | `'md'` | Checkbox size. Allowed: `'sm'`, `'md'`, `'lg'` |
| `disabled` | `Boolean` | `false` | Disables the checkbox |
| `error` | `String \| Boolean` | `null` | Error state. When truthy, shows error styling |
| `indeterminate` | `Boolean` | `false` | Shows indeterminate state (minus icon instead of check) |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Boolean` | Emitted when checked state changes |
| `change` | `Boolean` | Emitted when checked state changes |

## Slots

### Default Slot

Use the default slot for custom label content instead of the `label` prop.

```vue
<Checkbox v-model="agreed">
    I agree to the <a href="/terms" class="text-primary-600">Terms of Service</a>
</Checkbox>
```

## Basic Example

```vue
<script setup>
import { Checkbox } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const isSubscribed = ref(false)
</script>

<template>
    <Checkbox v-model="isSubscribed" label="Subscribe to newsletter" />
</template>
```

## With Description

```vue
<Checkbox
    v-model="notifications"
    label="Email notifications"
    description="Receive updates about new features and announcements"
/>
```

## Sizes

```vue
<!-- Small -->
<Checkbox v-model="value" size="sm" label="Small checkbox" />

<!-- Medium (default) -->
<Checkbox v-model="value" size="md" label="Medium checkbox" />

<!-- Large -->
<Checkbox v-model="value" size="lg" label="Large checkbox" />
```

### Size Specifications

| Size | Box Size | Label Font | Description Font |
|------|----------|------------|------------------|
| `sm` | `14px` (h-3.5 w-3.5) | `text-xs` | `text-xs` |
| `md` | `16px` (h-4 w-4) | `text-sm` | `text-xs` |
| `lg` | `20px` (h-5 w-5) | `text-base` | `text-xs` |

## Indeterminate State

The indeterminate state is useful for "select all" checkboxes when some (but not all) items are selected:

```vue
<script setup>
import { computed } from 'vue'

const items = ref(['item1', 'item2', 'item3'])
const selectedItems = ref(['item1'])

const allSelected = computed(() => selectedItems.value.length === items.value.length)
const someSelected = computed(() => selectedItems.value.length > 0 && !allSelected.value)

const toggleAll = () => {
    if (allSelected.value) {
        selectedItems.value = []
    } else {
        selectedItems.value = [...items.value]
    }
}
</script>

<template>
    <Checkbox
        :model-value="allSelected"
        :indeterminate="someSelected"
        label="Select all"
        @update:model-value="toggleAll"
    />
</template>
```

## Error State

```vue
<Checkbox
    v-model="agreed"
    :error="!agreed"
    label="I agree to the terms and conditions"
/>
```

## Disabled State

```vue
<!-- Disabled unchecked -->
<Checkbox v-model="value1" disabled label="Disabled option" />

<!-- Disabled checked -->
<Checkbox :model-value="true" disabled label="Disabled (checked)" />
```

## Custom Label with Slot

```vue
<Checkbox v-model="agreed">
    <span>
        I agree to the
        <a href="/terms" class="text-primary-600 hover:underline">Terms of Service</a>
        and
        <a href="/privacy" class="text-primary-600 hover:underline">Privacy Policy</a>
    </span>
</Checkbox>
```

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, Checkbox, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: '',
    remember: false,
    terms: false
})

const submit = (form) => {
    form.post('/login')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Email" required :error="form.errors.email">
            <Input v-model="form.email" type="email" :error="form.errors.email" />
        </FormGroup>

        <FormGroup label="Password" required :error="form.errors.password">
            <Input v-model="form.password" type="password" :error="form.errors.password" />
        </FormGroup>

        <div class="space-y-3">
            <Checkbox
                v-model="form.remember"
                label="Remember me"
                description="Stay logged in for 30 days"
            />

            <Checkbox
                v-model="form.terms"
                :error="form.errors.terms"
            >
                I agree to the
                <a href="/terms" class="text-primary-600 hover:underline">Terms</a>
            </Checkbox>
        </div>

        <Button type="submit" :loading="form.processing">
            Sign In
        </Button>
    </Form>
</template>
```

## Multiple Checkboxes (Manual)

For a group of independent checkboxes:

```vue
<script setup>
const preferences = ref({
    newsletter: false,
    updates: true,
    marketing: false
})
</script>

<template>
    <div class="space-y-2">
        <Checkbox
            v-model="preferences.newsletter"
            label="Newsletter"
            description="Weekly digest of top articles"
        />
        <Checkbox
            v-model="preferences.updates"
            label="Product updates"
            description="New features and improvements"
        />
        <Checkbox
            v-model="preferences.marketing"
            label="Marketing emails"
            description="Promotions and special offers"
        />
    </div>
</template>
```

For grouped checkboxes with array values, use [CheckboxGroup](./checkbox-group.md) instead.

## Styling

The Checkbox component includes:
- Custom styled checkbox box
- Smooth check animation
- Primary color when checked
- Error state with red border
- Disabled state with opacity

### Visual States

| State | Box Appearance |
|-------|----------------|
| Unchecked | White background, gray border |
| Checked | Primary background with white check icon |
| Indeterminate | Primary background with white minus icon |
| Error | White background, red border |
| Disabled | 50% opacity, not clickable |

## Accessibility

- Uses `role="checkbox"` for ARIA
- Supports `aria-checked` with `true`, `false`, or `mixed` (indeterminate)
- Keyboard accessible with `Space` to toggle
- Tab navigation support
- Label is clickable to toggle state
- Focus visible indicator

## Playground

Try the checkbox component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoCheckbox label="Default checkbox" />
    <DemoCheckbox label="Checked checkbox" :modelValue="true" />
    <DemoCheckbox label="Indeterminate" indeterminate />
    <DemoCheckbox label="Disabled checkbox" disabled />
  </div>

  <template #code>

```vue
<Checkbox label="Default checkbox" />
<Checkbox v-model="checked" label="Checked checkbox" />
<Checkbox label="Indeterminate" indeterminate />
<Checkbox label="Disabled checkbox" disabled />
```

  </template>
</LiveDemo>

### Checkbox Colors

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoCheckbox label="Primary color" color="primary" :modelValue="true" />
    <DemoCheckbox label="Success color" color="success" :modelValue="true" />
    <DemoCheckbox label="Danger color" color="danger" :modelValue="true" />
  </div>

  <template #code>

```vue
<Checkbox label="Primary color" color="primary" v-model="checked" />
<Checkbox label="Success color" color="success" v-model="checked" />
<Checkbox label="Danger color" color="danger" v-model="checked" />
```

  </template>
</LiveDemo>
