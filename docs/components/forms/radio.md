# Radio

A customizable radio button component for single-choice selections with support for labels and descriptions.

## Import

```vue
<script setup>
import { Radio } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String \| Number \| Boolean \| null` | `null` | The currently selected value (v-model) |
| `value` | `String \| Number \| Boolean` | **required** | The value this radio represents |
| `label` | `String` | `null` | Text label for the radio |
| `description` | `String` | `null` | Description text below the label |
| `size` | `String` | `'md'` | Radio size. Allowed: `'sm'`, `'md'`, `'lg'` |
| `disabled` | `Boolean` | `false` | Disables the radio |
| `error` | `String \| Boolean` | `null` | Error state. When truthy, shows error styling |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| Number \| Boolean` | Emitted when this radio is selected |
| `change` | `String \| Number \| Boolean` | Emitted when this radio is selected |

## Slots

### Default Slot

Use the default slot for custom label content instead of the `label` prop.

```vue
<Radio v-model="plan" value="pro">
    <span class="font-bold">Pro Plan</span> - $29/month
</Radio>
```

## Basic Example

```vue
<script setup>
import { Radio } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const gender = ref(null)
</script>

<template>
    <div class="space-y-2">
        <Radio v-model="gender" value="male" label="Male" />
        <Radio v-model="gender" value="female" label="Female" />
        <Radio v-model="gender" value="other" label="Other" />
    </div>
</template>
```

## With Description

```vue
<div class="space-y-3">
    <Radio
        v-model="plan"
        value="free"
        label="Free Plan"
        description="Basic features for personal use"
    />
    <Radio
        v-model="plan"
        value="pro"
        label="Pro Plan"
        description="Advanced features for professionals"
    />
    <Radio
        v-model="plan"
        value="enterprise"
        label="Enterprise Plan"
        description="Full features with dedicated support"
    />
</div>
```

## Sizes

```vue
<!-- Small -->
<Radio v-model="value" value="a" size="sm" label="Small radio" />

<!-- Medium (default) -->
<Radio v-model="value" value="b" size="md" label="Medium radio" />

<!-- Large -->
<Radio v-model="value" value="c" size="lg" label="Large radio" />
```

### Size Specifications

| Size | Circle Size | Dot Size | Label Font |
|------|-------------|----------|------------|
| `sm` | `14px` (h-3.5 w-3.5) | `6px` (h-1.5 w-1.5) | `text-xs` |
| `md` | `16px` (h-4 w-4) | `8px` (h-2 w-2) | `text-sm` |
| `lg` | `20px` (h-5 w-5) | `10px` (h-2.5 w-2.5) | `text-base` |

## Error State

```vue
<div class="space-y-2">
    <Radio v-model="selection" value="a" :error="!selection" label="Option A" />
    <Radio v-model="selection" value="b" :error="!selection" label="Option B" />
</div>
<p v-if="!selection" class="text-red-600 text-sm mt-1">Please select an option</p>
```

## Disabled State

```vue
<div class="space-y-2">
    <Radio v-model="value" value="a" label="Available option" />
    <Radio v-model="value" value="b" disabled label="Disabled option" />
    <Radio :model-value="'c'" value="c" disabled label="Disabled (selected)" />
</div>
```

## Custom Label with Slot

```vue
<div class="space-y-2">
    <Radio v-model="shipping" value="standard">
        <div class="flex justify-between w-full">
            <span>Standard Shipping</span>
            <span class="text-gray-500">Free</span>
        </div>
    </Radio>

    <Radio v-model="shipping" value="express">
        <div class="flex justify-between w-full">
            <span>Express Shipping</span>
            <span class="text-gray-500">$9.99</span>
        </div>
    </Radio>

    <Radio v-model="shipping" value="overnight">
        <div class="flex justify-between w-full">
            <span>Overnight Shipping</span>
            <span class="text-gray-500">$24.99</span>
        </div>
    </Radio>
</div>
```

## Boolean Values

```vue
<script setup>
const isActive = ref(true)
</script>

<template>
    <div class="space-y-2">
        <Radio v-model="isActive" :value="true" label="Active" />
        <Radio v-model="isActive" :value="false" label="Inactive" />
    </div>
</template>
```

## Number Values

```vue
<script setup>
const rating = ref(null)
</script>

<template>
    <div class="flex gap-4">
        <Radio v-model="rating" :value="1" label="1" />
        <Radio v-model="rating" :value="2" label="2" />
        <Radio v-model="rating" :value="3" label="3" />
        <Radio v-model="rating" :value="4" label="4" />
        <Radio v-model="rating" :value="5" label="5" />
    </div>
</template>
```

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, Radio, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    subscription: null
})

const submit = (form) => {
    form.post('/subscribe')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Name" required :error="form.errors.name">
            <Input v-model="form.name" :error="form.errors.name" />
        </FormGroup>

        <FormGroup label="Subscription Plan" required :error="form.errors.subscription">
            <div class="space-y-3">
                <Radio
                    v-model="form.subscription"
                    value="monthly"
                    :error="form.errors.subscription"
                    label="Monthly"
                    description="$9.99/month, billed monthly"
                />
                <Radio
                    v-model="form.subscription"
                    value="yearly"
                    :error="form.errors.subscription"
                    label="Yearly"
                    description="$99.99/year, save 17%"
                />
                <Radio
                    v-model="form.subscription"
                    value="lifetime"
                    :error="form.errors.subscription"
                    label="Lifetime"
                    description="$299.99 one-time payment"
                />
            </div>
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Subscribe
        </Button>
    </Form>
</template>
```

## When to Use Radio vs RadioGroup

### Use individual Radio components when:
- You need custom layout or spacing
- Each option has complex custom content
- Options are dynamically rendered with specific logic

### Use RadioGroup when:
- Options come from an array/API
- Standard vertical or horizontal layout is sufficient
- You want simpler, more maintainable code

See [RadioGroup](./radio-group.md) for grouped radio buttons.

## Styling

The Radio component includes:
- Circular radio button with inner dot
- Primary color when selected
- Error state with red border
- Disabled state with opacity
- Smooth transition animations

### Visual States

| State | Circle Appearance |
|-------|-------------------|
| Unselected | White background, gray border |
| Selected | Primary background with white dot |
| Error | White background, red border |
| Disabled | 50% opacity, not clickable |

## Accessibility

- Uses `role="radio"` for ARIA
- Supports `aria-checked` attribute
- Keyboard accessible with `Space` to select
- Tab navigation support
- Label is clickable to select
- Focus visible indicator

## Playground

Try the radio component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.5rem;">
    <DemoRadio name="demo1" value="option1" label="Option 1" checked />
    <DemoRadio name="demo1" value="option2" label="Option 2" />
    <DemoRadio name="demo1" value="option3" label="Option 3" />
  </div>

  <template #code>

```vue
<Radio v-model="selected" value="option1" label="Option 1" />
<Radio v-model="selected" value="option2" label="Option 2" />
<Radio v-model="selected" value="option3" label="Option 3" />
```

  </template>
</LiveDemo>

### Radio Sizes

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.5rem;">
    <DemoRadio name="size-demo" value="sm" label="Small radio" size="sm" checked />
    <DemoRadio name="size-demo" value="md" label="Medium radio" size="md" />
    <DemoRadio name="size-demo" value="lg" label="Large radio" size="lg" />
  </div>

  <template #code>

```vue
<Radio v-model="value" size="sm" label="Small radio" />
<Radio v-model="value" size="md" label="Medium radio" />
<Radio v-model="value" size="lg" label="Large radio" />
```

  </template>
</LiveDemo>

### Radio States

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.5rem;">
    <DemoRadio name="state-demo" value="normal" label="Normal" />
    <DemoRadio name="state-demo" value="disabled" label="Disabled" disabled />
    <DemoRadio name="state-demo" value="error" label="Error state" error />
  </div>

  <template #code>

```vue
<Radio label="Normal" />
<Radio label="Disabled" disabled />
<Radio label="Error state" error />
```

  </template>
</LiveDemo>
