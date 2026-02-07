# RadioGroup

A component for managing a group of radio buttons with a shared value. Provides a cleaner API for common radio selection patterns.

## Import

```vue
<script setup>
import { RadioGroup } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String \| Number \| Boolean \| null` | `null` | The selected value (v-model) |
| `options` | `Array` | `[]` | Array of options (strings, numbers, or objects) |
| `size` | `String` | `'md'` | Radio size. Allowed: `'sm'`, `'md'`, `'lg'` |
| `disabled` | `Boolean` | `false` | Disables all radio buttons |
| `error` | `String \| Boolean` | `null` | Error state for all radios |
| `inline` | `Boolean` | `false` | Displays radios horizontally |
| `optionLabel` | `String` | `'label'` | Property name to use for option label |
| `optionValue` | `String` | `'value'` | Property name to use for option value |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| Number \| Boolean` | Emitted when selection changes |
| `change` | `String \| Number \| Boolean` | Emitted when selection changes |

## Option Formats

```vue
<!-- Simple strings -->
<RadioGroup :options="['Small', 'Medium', 'Large']" />

<!-- Simple numbers -->
<RadioGroup :options="[1, 2, 3, 4, 5]" />

<!-- Objects with label/value -->
<RadioGroup :options="[
    { label: 'Monthly', value: 'monthly' },
    { label: 'Yearly', value: 'yearly' }
]" />

<!-- With description -->
<RadioGroup :options="[
    { label: 'Standard', value: 'standard', description: 'Free shipping in 5-7 days' },
    { label: 'Express', value: 'express', description: '$9.99 - arrives in 2-3 days' }
]" />

<!-- With disabled options -->
<RadioGroup :options="[
    { label: 'Available', value: 'available' },
    { label: 'Coming Soon', value: 'soon', disabled: true }
]" />
```

## Basic Example

```vue
<script setup>
import { RadioGroup } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const selectedPlan = ref(null)
const plans = [
    { value: 'free', label: 'Free' },
    { value: 'pro', label: 'Pro' },
    { value: 'enterprise', label: 'Enterprise' }
]
</script>

<template>
    <RadioGroup v-model="selectedPlan" :options="plans" />
</template>
```

## Inline Layout

Display radios horizontally:

```vue
<RadioGroup
    v-model="size"
    :options="['S', 'M', 'L', 'XL']"
    inline
/>
```

## Sizes

```vue
<!-- Small -->
<RadioGroup v-model="value" :options="options" size="sm" />

<!-- Medium (default) -->
<RadioGroup v-model="value" :options="options" size="md" />

<!-- Large -->
<RadioGroup v-model="value" :options="options" size="lg" />
```

## With Descriptions

```vue
<script setup>
const shippingOptions = [
    {
        value: 'standard',
        label: 'Standard Shipping',
        description: 'Free - Arrives in 5-7 business days'
    },
    {
        value: 'express',
        label: 'Express Shipping',
        description: '$9.99 - Arrives in 2-3 business days'
    },
    {
        value: 'overnight',
        label: 'Overnight Shipping',
        description: '$24.99 - Arrives next business day'
    }
]
</script>

<template>
    <RadioGroup v-model="selectedShipping" :options="shippingOptions" />
</template>
```

## Error State

```vue
<FormGroup label="Payment Method" :error="form.errors.payment_method">
    <RadioGroup
        v-model="form.payment_method"
        :options="paymentOptions"
        :error="form.errors.payment_method"
    />
</FormGroup>
```

## Disabled State

```vue
<!-- All disabled -->
<RadioGroup v-model="value" :options="options" disabled />

<!-- Individual options disabled -->
<RadioGroup
    v-model="value"
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
const countries = [
    { code: 'us', name: 'United States' },
    { code: 'uk', name: 'United Kingdom' },
    { code: 'ca', name: 'Canada' }
]
</script>

<template>
    <RadioGroup
        v-model="selectedCountry"
        :options="countries"
        option-label="name"
        option-value="code"
    />
</template>
```

## Boolean Options

```vue
<script setup>
const statusOptions = [
    { value: true, label: 'Active' },
    { value: false, label: 'Inactive' }
]
const isActive = ref(true)
</script>

<template>
    <RadioGroup v-model="isActive" :options="statusOptions" inline />
</template>
```

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, RadioGroup, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    plan: null,
    billing: 'monthly'
})

const planOptions = [
    {
        value: 'starter',
        label: 'Starter',
        description: 'For individuals and small teams'
    },
    {
        value: 'professional',
        label: 'Professional',
        description: 'For growing businesses'
    },
    {
        value: 'enterprise',
        label: 'Enterprise',
        description: 'For large organizations'
    }
]

const billingOptions = [
    { value: 'monthly', label: 'Monthly' },
    { value: 'yearly', label: 'Yearly (Save 20%)' }
]

const submit = (form) => {
    form.post('/subscribe')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Email" required :error="form.errors.email">
            <Input v-model="form.email" type="email" :error="form.errors.email" />
        </FormGroup>

        <FormGroup label="Plan" required :error="form.errors.plan">
            <RadioGroup
                v-model="form.plan"
                :options="planOptions"
                :error="form.errors.plan"
            />
        </FormGroup>

        <FormGroup label="Billing Cycle" :error="form.errors.billing">
            <RadioGroup
                v-model="form.billing"
                :options="billingOptions"
                :error="form.errors.billing"
                inline
            />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Subscribe
        </Button>
    </Form>
</template>
```

## Rating Example

```vue
<script setup>
const rating = ref(null)
const ratingOptions = [1, 2, 3, 4, 5].map(n => ({
    value: n,
    label: n.toString()
}))
</script>

<template>
    <FormGroup label="How would you rate your experience?">
        <RadioGroup
            v-model="rating"
            :options="ratingOptions"
            inline
        />
    </FormGroup>
</template>
```

## Survey Question Pattern

```vue
<script setup>
const satisfaction = ref(null)
const satisfactionOptions = [
    { value: 5, label: 'Very Satisfied' },
    { value: 4, label: 'Satisfied' },
    { value: 3, label: 'Neutral' },
    { value: 2, label: 'Dissatisfied' },
    { value: 1, label: 'Very Dissatisfied' }
]
</script>

<template>
    <FormGroup label="How satisfied are you with our service?">
        <RadioGroup
            v-model="satisfaction"
            :options="satisfactionOptions"
        />
    </FormGroup>
</template>
```

## Comparing RadioGroup vs Individual Radios

### Use RadioGroup when:
- Options come from an array/API
- Standard layout is sufficient
- You want cleaner, more maintainable code

### Use individual Radio components when:
- Complex custom layout needed
- Each option has unique behavior
- Radios are spread across different parts of UI

## Styling

The RadioGroup component:
- Renders vertically by default with `gap-2`
- Inline mode uses `flex-wrap` with `gap-x-6 gap-y-2`
- Uses `role="radiogroup"` for accessibility
- Each radio inherits [Radio](./radio.md) styling

## Accessibility

- Container has `role="radiogroup"`
- Each radio is independently focusable
- Arrow keys can navigate between options
- Only one option can be selected at a time
- Full keyboard support via [Radio](./radio.md) component

## Playground

Try the RadioGroup component:

<LiveDemo>
  <DemoRadioGroup />

  <template #code>

```vue
<script setup>
import { RadioGroup } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const selectedPlan = ref(null)
const plans = [
    { value: 'free', label: 'Free' },
    { value: 'pro', label: 'Pro' },
    { value: 'enterprise', label: 'Enterprise' }
]
</script>

<template>
    <RadioGroup v-model="selectedPlan" :options="plans" />
</template>
```

  </template>
</LiveDemo>
