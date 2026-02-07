# Collapse

A simple collapsible container that can be toggled open or closed. More flexible than Accordion for custom trigger elements.

## Import

```vue
<script setup>
import { Collapse } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Boolean` | `null` | Controlled open state (for v-model) |
| `defaultOpen` | `Boolean` | `false` | Initial open state (uncontrolled mode) |

## Slots

| Slot | Props | Description |
|------|-------|-------------|
| `trigger` | `{ open: Boolean, toggle: Function }` | Custom trigger element |
| `default` | - | Collapsible content |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Boolean` | Emitted when open state changes (v-model) |

## Examples

### Basic Collapse

```vue
<template>
    <Collapse>
        <template #trigger="{ open, toggle }">
            <button
                @click="toggle"
                class="flex items-center gap-2 text-primary-600"
            >
                {{ open ? 'Hide' : 'Show' }} details
                <ChevronDownIcon
                    :class="['h-5 w-5 transition-transform', open ? 'rotate-180' : '']"
                />
            </button>
        </template>

        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            <p>This content can be shown or hidden.</p>
        </div>
    </Collapse>
</template>
```

### Default Open

```vue
<template>
    <Collapse defaultOpen>
        <template #trigger="{ open, toggle }">
            <button @click="toggle" class="text-primary-600">
                {{ open ? 'Collapse' : 'Expand' }}
            </button>
        </template>

        <p class="mt-2">This content is visible by default.</p>
    </Collapse>
</template>
```

### Controlled with v-model

```vue
<template>
    <div class="space-y-4">
        <Button @click="isOpen = !isOpen">
            {{ isOpen ? 'Close' : 'Open' }} Panel
        </Button>

        <Collapse v-model="isOpen">
            <template #trigger="{ open, toggle }">
                <!-- Trigger can also be used alongside external control -->
                <button @click="toggle" class="text-sm text-gray-500">
                    or click here
                </button>
            </template>

            <Card title="Collapsible Content">
                <p>This panel is controlled by v-model.</p>
            </Card>
        </Collapse>
    </div>
</template>

<script setup>
import { ref } from 'vue'
const isOpen = ref(false)
</script>
```

### Read More Pattern

```vue
<template>
    <div class="prose">
        <p>{{ article.excerpt }}</p>

        <Collapse>
            <template #trigger="{ open, toggle }">
                <button
                    @click="toggle"
                    class="text-primary-600 hover:text-primary-800 font-medium"
                >
                    {{ open ? 'Read less' : 'Read more' }}
                </button>
            </template>

            <div class="mt-4">
                <p v-for="paragraph in article.content" :key="paragraph">
                    {{ paragraph }}
                </p>
            </div>
        </Collapse>
    </div>
</template>
```

### Card with Collapsible Section

```vue
<template>
    <Card title="Order Summary">
        <div class="space-y-2">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>$99.00</span>
            </div>
            <div class="flex justify-between">
                <span>Shipping</span>
                <span>$5.00</span>
            </div>
            <div class="flex justify-between font-semibold">
                <span>Total</span>
                <span>$104.00</span>
            </div>
        </div>

        <Collapse>
            <template #trigger="{ open, toggle }">
                <button
                    @click="toggle"
                    class="mt-4 text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1"
                >
                    <span>{{ open ? 'Hide' : 'Show' }} order details</span>
                    <ChevronDownIcon :class="['h-4 w-4 transition-transform', open ? 'rotate-180' : '']" />
                </button>
            </template>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <ul class="space-y-2 text-sm">
                    <li v-for="item in orderItems" :key="item.id" class="flex justify-between">
                        <span>{{ item.name }} x {{ item.quantity }}</span>
                        <span>{{ item.price }}</span>
                    </li>
                </ul>
            </div>
        </Collapse>
    </Card>
</template>
```

### Filter Panel

```vue
<template>
    <div class="space-y-4">
        <Collapse v-for="filter in filters" :key="filter.id" defaultOpen>
            <template #trigger="{ open, toggle }">
                <button
                    @click="toggle"
                    class="flex items-center justify-between w-full py-2 font-medium"
                >
                    {{ filter.label }}
                    <ChevronDownIcon
                        :class="['h-5 w-5 text-gray-400 transition-transform', open ? 'rotate-180' : '']"
                    />
                </button>
            </template>

            <div class="py-2 space-y-2">
                <Checkbox
                    v-for="option in filter.options"
                    :key="option.value"
                    :label="option.label"
                    v-model="selectedFilters[filter.id]"
                    :value="option.value"
                />
            </div>
        </Collapse>
    </div>
</template>
```

### Nested Collapses

```vue
<template>
    <Collapse>
        <template #trigger="{ open, toggle }">
            <button @click="toggle" class="font-semibold">
                {{ open ? '-' : '+' }} Parent Section
            </button>
        </template>

        <div class="ml-4 mt-2 space-y-2">
            <Collapse v-for="i in 3" :key="i">
                <template #trigger="{ open, toggle }">
                    <button @click="toggle" class="text-sm text-gray-600">
                        {{ open ? '-' : '+' }} Child Section {{ i }}
                    </button>
                </template>

                <div class="ml-4 mt-1 text-sm text-gray-500">
                    Nested content for child {{ i }}
                </div>
            </Collapse>
        </div>
    </Collapse>
</template>
```

### Animated Icon Button

```vue
<template>
    <Collapse>
        <template #trigger="{ open, toggle }">
            <button
                @click="toggle"
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
                <PlusIcon
                    :class="[
                        'h-5 w-5 transition-transform duration-200',
                        open ? 'rotate-45' : ''
                    ]"
                />
                <span>{{ open ? 'Less options' : 'More options' }}</span>
            </button>
        </template>

        <div class="mt-4 p-4 border rounded-lg">
            <p>Additional options and settings...</p>
        </div>
    </Collapse>
</template>
```

## Animation

The Collapse component uses Vue's `<Transition>` with:
- Enter: `duration-200 ease-out`
- Leave: `duration-150 ease-in`
- Max height animation from `0` to `max-h-screen`
- Opacity transition for smooth appearance

## Usage Notes

- Use `Collapse` for single collapsible sections
- Use `Accordion` when you have multiple related collapsible sections
- The trigger slot gives you full control over the toggle button appearance
- For uncontrolled usage, use `defaultOpen`; for controlled usage, use `v-model`
- The `toggle` function in the trigger slot handles all the state management

## Playground

Try the collapse component:

<LiveDemo>
  <DemoCollapse />

  <template #code>

```vue
<Collapse>
  <template #trigger="{ open, toggle }">
    <button @click="toggle" class="text-primary-600">
      {{ open ? 'Hide' : 'Show' }} details
    </button>
  </template>

  <div class="mt-4 p-4 bg-gray-50 rounded-lg">
    <p>This content can be shown or hidden.</p>
  </div>
</Collapse>
```

  </template>
</LiveDemo>
