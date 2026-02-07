# CountUp

Animated number counter that counts up from 0 to a target value.

## Import

```vue
<script setup>
import CountUp from '@/Components/Utilities/CountUp.vue'
</script>
```

## Basic Usage

```vue
<template>
  <CountUp :end="1234" />
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `end` | `number` | `0` | Target value |
| `start` | `number` | `0` | Starting value |
| `duration` | `number` | `2000` | Animation duration (ms) |
| `decimals` | `number` | `0` | Decimal places |
| `prefix` | `string` | `''` | Prefix (e.g., "$") |
| `suffix` | `string` | `''` | Suffix (e.g., "%") |
| `separator` | `string` | `','` | Thousands separator |
| `easing` | `boolean` | `true` | Use easing animation |
| `autoplay` | `boolean` | `true` | Start on mount |

## Examples

### Currency

```vue
<CountUp :end="45231" prefix="$" />
```
Output: $45,231

### Percentage

```vue
<CountUp :end="87.5" :decimals="1" suffix="%" />
```
Output: 87.5%

### Large Numbers

```vue
<CountUp :end="1234567" separator="," />
```
Output: 1,234,567

### Slower Animation

```vue
<CountUp :end="100" :duration="5000" />
```

### No Easing

```vue
<CountUp :end="1000" :easing="false" />
```

### With Decimals

```vue
<CountUp :end="99.99" :decimals="2" prefix="$" />
```
Output: $99.99

## Methods

### Manual Control

```vue
<template>
  <CountUp ref="counter" :end="1000" :autoplay="false" />
  <Button @click="startCount">Start</Button>
  <Button @click="resetCount">Reset</Button>
</template>

<script setup>
import { ref } from 'vue'

const counter = ref()

const startCount = () => counter.value.start()
const resetCount = () => counter.value.reset()
</script>
```

## Real-World Example

```vue
<template>
  <div class="grid grid-cols-4 gap-6">
    <Card class="text-center p-6">
      <p class="text-3xl font-bold text-indigo-600">
        <CountUp :end="stats.revenue" prefix="$" />
      </p>
      <p class="text-gray-500">Revenue</p>
    </Card>

    <Card class="text-center p-6">
      <p class="text-3xl font-bold text-green-600">
        <CountUp :end="stats.orders" />
      </p>
      <p class="text-gray-500">Orders</p>
    </Card>

    <Card class="text-center p-6">
      <p class="text-3xl font-bold text-blue-600">
        <CountUp :end="stats.customers" />
      </p>
      <p class="text-gray-500">Customers</p>
    </Card>

    <Card class="text-center p-6">
      <p class="text-3xl font-bold text-yellow-600">
        <CountUp :end="stats.conversion" :decimals="1" suffix="%" />
      </p>
      <p class="text-gray-500">Conversion</p>
    </Card>
  </div>
</template>

<script setup>
const stats = {
  revenue: 125430,
  orders: 1847,
  customers: 923,
  conversion: 3.8
}
</script>
```

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `complete` | - | Animation finished |

## Playground

Try the CountUp component:

<LiveDemo>
  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
    <div style="text-align: center; padding: 16px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <p style="font-size: 24px; font-weight: 700; color: #4f46e5;">$125,430</p>
      <p style="color: #6b7280; font-size: 14px;">Revenue</p>
    </div>
    <div style="text-align: center; padding: 16px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <p style="font-size: 24px; font-weight: 700; color: #059669;">1,847</p>
      <p style="color: #6b7280; font-size: 14px;">Orders</p>
    </div>
    <div style="text-align: center; padding: 16px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <p style="font-size: 24px; font-weight: 700; color: #2563eb;">923</p>
      <p style="color: #6b7280; font-size: 14px;">Customers</p>
    </div>
    <div style="text-align: center; padding: 16px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <p style="font-size: 24px; font-weight: 700; color: #d97706;">3.8%</p>
      <p style="color: #6b7280; font-size: 14px;">Conversion</p>
    </div>
  </div>

  <template #code>

```vue
<template>
  <div class="grid grid-cols-4 gap-4">
    <Card class="text-center p-4">
      <p class="text-2xl font-bold text-indigo-600">
        <CountUp :end="125430" prefix="$" />
      </p>
      <p class="text-gray-500 text-sm">Revenue</p>
    </Card>

    <Card class="text-center p-4">
      <p class="text-2xl font-bold text-green-600">
        <CountUp :end="1847" />
      </p>
      <p class="text-gray-500 text-sm">Orders</p>
    </Card>

    <Card class="text-center p-4">
      <p class="text-2xl font-bold text-blue-600">
        <CountUp :end="923" />
      </p>
      <p class="text-gray-500 text-sm">Customers</p>
    </Card>

    <Card class="text-center p-4">
      <p class="text-2xl font-bold text-amber-600">
        <CountUp :end="3.8" :decimals="1" suffix="%" />
      </p>
      <p class="text-gray-500 text-sm">Conversion</p>
    </Card>
  </div>
</template>

<script setup>
import CountUp from '@/Components/Utilities/CountUp.vue'
</script>
```

  </template>
</LiveDemo>
