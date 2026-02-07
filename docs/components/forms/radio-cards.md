# RadioCards

A visually rich radio selection component that displays options as selectable cards. Ideal for plan selection, feature comparison, or any scenario where options benefit from visual emphasis.

## Import

```vue
<script setup>
import { RadioCards } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String \| Number \| null` | `null` | The selected value (v-model) |
| `options` | `Array` | `[]` | Array of options (strings, numbers, or objects) |
| `disabled` | `Boolean` | `false` | Disables all cards |
| `error` | `String \| Boolean` | `null` | Error state. When truthy, shows error styling |
| `cols` | `Number` | `3` | Number of columns in the grid. Allowed: `1`, `2`, `3`, `4` |
| `optionLabel` | `String` | `'label'` | Property name to use for option label |
| `optionValue` | `String` | `'value'` | Property name to use for option value |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| Number` | Emitted when selection changes |
| `change` | `String \| Number` | Emitted when selection changes |

## Slots

### label

Customize the label rendering for each option.

| Slot Prop | Type | Description |
|-----------|------|-------------|
| `option` | `Object` | The normalized option object |

## Option Object Properties

Options can include these properties:

| Property | Type | Description |
|----------|------|-------------|
| `value` | `String \| Number` | The option value |
| `label` | `String` | Display text |
| `description` | `String` | Optional description text |
| `icon` | `Component` | Optional icon component |
| `disabled` | `Boolean` | Disable this specific option |

## Basic Example

```vue
<script setup>
import { RadioCards } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const selectedPlan = ref(null)
const plans = [
    { value: 'starter', label: 'Starter', description: 'For individuals' },
    { value: 'pro', label: 'Professional', description: 'For small teams' },
    { value: 'enterprise', label: 'Enterprise', description: 'For organizations' }
]
</script>

<template>
    <RadioCards v-model="selectedPlan" :options="plans" />
</template>
```

## Column Configuration

```vue
<!-- 1 column (full width cards) -->
<RadioCards v-model="value" :options="options" :cols="1" />

<!-- 2 columns -->
<RadioCards v-model="value" :options="options" :cols="2" />

<!-- 3 columns (default) -->
<RadioCards v-model="value" :options="options" :cols="3" />

<!-- 4 columns -->
<RadioCards v-model="value" :options="options" :cols="4" />
```

### Responsive Behavior

The grid is responsive:
- **1 column**: Always single column
- **2 columns**: 1 on mobile, 2 on sm+
- **3 columns**: 1 on mobile, 2 on sm+, 3 on lg+
- **4 columns**: 1 on mobile, 2 on sm+, 4 on lg+

## With Icons

```vue
<script setup>
import { RadioCards } from '@cambosoftware/cambo-admin'
import {
    UserIcon,
    UsersIcon,
    BuildingOfficeIcon
} from '@heroicons/vue/24/outline'

const plans = [
    {
        value: 'personal',
        label: 'Personal',
        description: 'For individual use',
        icon: UserIcon
    },
    {
        value: 'team',
        label: 'Team',
        description: 'For small teams up to 10',
        icon: UsersIcon
    },
    {
        value: 'business',
        label: 'Business',
        description: 'For larger organizations',
        icon: BuildingOfficeIcon
    }
]
</script>

<template>
    <RadioCards v-model="selectedPlan" :options="plans" />
</template>
```

## With Descriptions

```vue
<script setup>
const serverOptions = [
    {
        value: 'basic',
        label: 'Basic',
        description: '1 vCPU, 1GB RAM, 25GB SSD'
    },
    {
        value: 'standard',
        label: 'Standard',
        description: '2 vCPU, 4GB RAM, 80GB SSD'
    },
    {
        value: 'performance',
        label: 'Performance',
        description: '4 vCPU, 8GB RAM, 160GB SSD'
    },
    {
        value: 'premium',
        label: 'Premium',
        description: '8 vCPU, 16GB RAM, 320GB SSD'
    }
]
</script>

<template>
    <RadioCards
        v-model="selectedServer"
        :options="serverOptions"
        :cols="2"
    />
</template>
```

## Error State

```vue
<FormGroup label="Select a plan" :error="form.errors.plan">
    <RadioCards
        v-model="form.plan"
        :options="plans"
        :error="form.errors.plan"
    />
</FormGroup>
```

## Disabled State

```vue
<!-- All disabled -->
<RadioCards v-model="value" :options="options" disabled />

<!-- Individual options disabled -->
<RadioCards
    v-model="value"
    :options="[
        { value: 'a', label: 'Available' },
        { value: 'b', label: 'Coming Soon', disabled: true },
        { value: 'c', label: 'Available' }
    ]"
/>
```

## Custom Label Slot

```vue
<script setup>
const plans = [
    { value: 'free', label: 'Free', price: '$0', period: 'forever' },
    { value: 'pro', label: 'Pro', price: '$29', period: '/month' },
    { value: 'enterprise', label: 'Enterprise', price: 'Custom', period: '' }
]
</script>

<template>
    <RadioCards v-model="selectedPlan" :options="plans">
        <template #label="{ option }">
            <div class="text-center">
                <div class="text-lg font-bold">{{ option.label }}</div>
                <div class="text-2xl font-bold text-primary-600 mt-1">
                    {{ option.price }}
                    <span class="text-sm text-gray-500">{{ option.period }}</span>
                </div>
            </div>
        </template>
    </RadioCards>
</template>
```

## Pricing Table Example

```vue
<script setup>
import { RadioCards } from '@cambosoftware/cambo-admin'
import { CheckIcon } from '@heroicons/vue/24/solid'
import { ref } from 'vue'

const selectedPlan = ref(null)

const plans = [
    {
        value: 'starter',
        label: 'Starter',
        description: 'Best for trying out',
        price: 'Free',
        features: ['5 projects', '1 GB storage', 'Community support']
    },
    {
        value: 'pro',
        label: 'Professional',
        description: 'Best for growing teams',
        price: '$29/mo',
        features: ['Unlimited projects', '100 GB storage', 'Priority support', 'Analytics']
    },
    {
        value: 'enterprise',
        label: 'Enterprise',
        description: 'Best for large teams',
        price: 'Contact us',
        features: ['Everything in Pro', 'Unlimited storage', 'Dedicated support', 'SSO', 'Custom integrations']
    }
]
</script>

<template>
    <RadioCards v-model="selectedPlan" :options="plans">
        <template #label="{ option }">
            <div class="text-left">
                <div class="font-semibold">{{ option.label }}</div>
                <div class="text-2xl font-bold mt-2">{{ option.price }}</div>
                <div class="text-gray-500 text-xs mt-1">{{ option.description }}</div>
                <ul class="mt-4 space-y-2">
                    <li
                        v-for="feature in option.features"
                        :key="feature"
                        class="flex items-center gap-2 text-xs"
                    >
                        <CheckIcon class="h-4 w-4 text-green-500" />
                        {{ feature }}
                    </li>
                </ul>
            </div>
        </template>
    </RadioCards>
</template>
```

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, RadioCards, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'
import { CreditCardIcon, BanknotesIcon, DevicePhoneMobileIcon } from '@heroicons/vue/24/outline'

const form = useForm({
    email: '',
    payment_method: null
})

const paymentMethods = [
    {
        value: 'card',
        label: 'Credit Card',
        description: 'Pay with Visa, Mastercard, etc.',
        icon: CreditCardIcon
    },
    {
        value: 'bank',
        label: 'Bank Transfer',
        description: 'Direct bank payment',
        icon: BanknotesIcon
    },
    {
        value: 'mobile',
        label: 'Mobile Payment',
        description: 'Apple Pay, Google Pay',
        icon: DevicePhoneMobileIcon
    }
]

const submit = (form) => {
    form.post('/checkout')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Email" required :error="form.errors.email">
            <Input v-model="form.email" type="email" :error="form.errors.email" />
        </FormGroup>

        <FormGroup label="Payment Method" required :error="form.errors.payment_method">
            <RadioCards
                v-model="form.payment_method"
                :options="paymentMethods"
                :error="form.errors.payment_method"
            />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Continue to Payment
        </Button>
    </Form>
</template>
```

## When to Use RadioCards vs RadioGroup

| Use RadioCards | Use RadioGroup |
|----------------|----------------|
| Visual emphasis needed | Simple text options |
| Options have descriptions | Compact form layout |
| Icons enhance understanding | Many options (5+) |
| Pricing/plan selection | Settings/preferences |
| Feature comparison | Quick selections |

## Styling

The RadioCards component includes:
- Card-based layout with grid
- Border highlight on selection
- Radio indicator in top-right corner
- Icon support with dynamic coloring
- Shadow on hover
- Primary color accent when selected

### Visual States

| State | Appearance |
|-------|------------|
| Unselected | Gray border, white background |
| Selected | Primary border + ring, light primary background |
| Hover | Shadow effect |
| Error | Red border |
| Disabled | 50% opacity, gray background |

## Accessibility

- Container has `role="radiogroup"`
- Each card has `role="radio"` and `aria-checked`
- Keyboard accessible with `Space` to select
- Tab navigation between cards
- Focus visible indicator

## Playground

Try the RadioCards component:

<LiveDemo>
  <DemoRadioCards />

  <template #code>

```vue
<script setup>
import { RadioCards } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const selectedPlan = ref(null)
const plans = [
    { value: 'starter', label: 'Starter', description: 'For individuals' },
    { value: 'pro', label: 'Professional', description: 'For small teams' },
    { value: 'enterprise', label: 'Enterprise', description: 'For organizations' }
]
</script>

<template>
    <RadioCards v-model="selectedPlan" :options="plans" />
</template>
```

  </template>
</LiveDemo>
