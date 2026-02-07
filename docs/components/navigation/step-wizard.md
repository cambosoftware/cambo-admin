# StepWizard

A step indicator component for multi-step processes like wizards, checkouts, or onboarding flows.

## Import

```vue
<script setup>
import { StepWizard } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `steps` | `Array` | **required** | Array of step objects or strings |
| `currentStep` | `String \| Number` | `null` | Current active step ID or index |
| `variant` | `String` | `'horizontal'` | Display style. Values: `'horizontal'`, `'vertical'`, `'simple'` |
| `clickable` | `Boolean` | `false` | Allow clicking on completed steps to navigate |
| `showNumbers` | `Boolean` | `true` | Show step numbers in indicators |

## Step Object Structure

```typescript
interface Step {
    id: string | number;    // Unique identifier
    label: string;          // Step title
    description?: string;   // Optional description
}

// Or simply use strings:
const steps = ['Step 1', 'Step 2', 'Step 3']
```

## Slots

| Slot | Description |
|------|-------------|
| `default` | Optional content below the step indicators |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:currentStep` | `String \| Number` | Emitted when a step is clicked (clickable mode) |
| `step-click` | `{ step, index }` | Emitted when a step is clicked with full details |

## Step Statuses

- **completed**: Steps before the current step
- **current**: The active step
- **upcoming**: Steps after the current step

## Variants

### Horizontal
Full step indicators with labels, connected by lines. Best for wide layouts.

### Vertical
Stacked steps with vertical connector lines. Best for sidebars or narrow layouts.

### Simple
Minimal dot indicators without labels. Best for compact spaces.

## Examples

### Basic Horizontal Wizard

```vue
<template>
    <StepWizard
        :steps="steps"
        :currentStep="currentStep"
    />
</template>

<script setup>
import { ref } from 'vue'

const steps = [
    { id: 'account', label: 'Account' },
    { id: 'profile', label: 'Profile' },
    { id: 'confirm', label: 'Confirm' }
]
const currentStep = ref('account')
</script>
```

### With Descriptions

```vue
<template>
    <StepWizard
        :steps="steps"
        :currentStep="currentStep"
    />
</template>

<script setup>
const steps = [
    { id: 1, label: 'Account', description: 'Create your account' },
    { id: 2, label: 'Profile', description: 'Set up your profile' },
    { id: 3, label: 'Preferences', description: 'Configure settings' },
    { id: 4, label: 'Complete', description: 'Review and finish' }
]
</script>
```

### Vertical Wizard

```vue
<template>
    <div class="max-w-md">
        <StepWizard
            :steps="steps"
            :currentStep="currentStep"
            variant="vertical"
        />
    </div>
</template>
```

### Simple Dots

```vue
<template>
    <StepWizard
        :steps="['', '', '', '']"
        :currentStep="currentIndex"
        variant="simple"
    />
</template>
```

### Clickable Steps

```vue
<template>
    <StepWizard
        :steps="steps"
        v-model:currentStep="currentStep"
        clickable
        @step-click="handleStepClick"
    />
</template>

<script setup>
import { ref } from 'vue'

const steps = [
    { id: 'info', label: 'Information' },
    { id: 'payment', label: 'Payment' },
    { id: 'review', label: 'Review' }
]
const currentStep = ref('info')

const handleStepClick = ({ step, index }) => {
    console.log('Clicked step:', step, 'at index:', index)
}
</script>
```

### Without Step Numbers

```vue
<template>
    <StepWizard
        :steps="steps"
        :currentStep="currentStep"
        :showNumbers="false"
    />
</template>
```

### String Array Steps

```vue
<template>
    <StepWizard
        :steps="['Cart', 'Shipping', 'Payment', 'Confirmation']"
        :currentStep="1"
    />
</template>
```

### Checkout Flow

```vue
<template>
    <div class="max-w-4xl mx-auto">
        <StepWizard
            :steps="checkoutSteps"
            v-model:currentStep="currentStep"
            clickable
        />

        <div class="mt-8">
            <component :is="currentComponent" @next="nextStep" @back="prevStep" />
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const checkoutSteps = [
    { id: 'cart', label: 'Shopping Cart', description: 'Review your items' },
    { id: 'shipping', label: 'Shipping', description: 'Delivery address' },
    { id: 'payment', label: 'Payment', description: 'Payment method' },
    { id: 'confirm', label: 'Confirmation', description: 'Order summary' }
]

const currentStep = ref('cart')

const stepComponents = {
    cart: CartStep,
    shipping: ShippingStep,
    payment: PaymentStep,
    confirm: ConfirmStep
}

const currentComponent = computed(() => stepComponents[currentStep.value])

const nextStep = () => {
    const currentIndex = checkoutSteps.findIndex(s => s.id === currentStep.value)
    if (currentIndex < checkoutSteps.length - 1) {
        currentStep.value = checkoutSteps[currentIndex + 1].id
    }
}

const prevStep = () => {
    const currentIndex = checkoutSteps.findIndex(s => s.id === currentStep.value)
    if (currentIndex > 0) {
        currentStep.value = checkoutSteps[currentIndex - 1].id
    }
}
</script>
```

### Onboarding Wizard

```vue
<template>
    <Card>
        <div class="p-6">
            <StepWizard
                :steps="onboardingSteps"
                :currentStep="step"
                variant="simple"
            />

            <div class="mt-8">
                <h2 class="text-xl font-semibold">{{ currentStepData.label }}</h2>
                <p class="text-gray-600 mt-1">{{ currentStepData.description }}</p>

                <!-- Step content -->
                <div class="mt-6">
                    <slot :name="`step-${step}`" />
                </div>
            </div>

            <div class="mt-8 flex justify-between">
                <Button v-if="step > 0" variant="outline" @click="step--">
                    Back
                </Button>
                <Button @click="step++" class="ml-auto">
                    {{ step < onboardingSteps.length - 1 ? 'Continue' : 'Finish' }}
                </Button>
            </div>
        </div>
    </Card>
</template>
```

### Vertical Sidebar Wizard

```vue
<template>
    <div class="flex gap-8">
        <aside class="w-64 flex-shrink-0">
            <StepWizard
                :steps="steps"
                :currentStep="currentStep"
                variant="vertical"
                clickable
                @update:currentStep="navigateToStep"
            />
        </aside>

        <main class="flex-1">
            <Card>
                <router-view />
            </Card>
        </main>
    </div>
</template>
```

## Styling

### Horizontal Variant
- Step indicators: 40x40px circles with border
- Completed: `bg-primary-500 border-primary-500` with white checkmark
- Current: `border-primary-500 bg-white` with primary text
- Upcoming: `border-gray-300 bg-white` with gray text
- Connector: `h-0.5 mx-4` horizontal line

### Vertical Variant
- Same indicator styles as horizontal
- Connector: `w-0.5` vertical line positioned absolutely

### Simple Variant
- Dots: `h-2.5 w-2.5 rounded-full`
- Completed: `bg-primary-300`
- Current: `bg-primary-500`
- Upcoming: `bg-gray-200`

## Accessibility

- Uses `<nav>` and `<ol>` elements for semantic structure
- Current step has `aria-current="step"`
- Clickable steps are `<button>` elements
- Disabled states prevent skipping ahead
- Checkmark icon for completed steps provides visual feedback

## Playground

Try the step wizard component:

<LiveDemo>
  <DemoStepWizard />

  <template #code>

```vue
<StepWizard
  :steps="[
    { id: 'account', label: 'Account' },
    { id: 'profile', label: 'Profile' },
    { id: 'confirm', label: 'Confirm' }
  ]"
  currentStep="profile"
/>
```

  </template>
</LiveDemo>
