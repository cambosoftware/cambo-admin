# MiniChart

A compact sparkline chart for inline data visualization.

## Import

```vue
<script setup>
import MiniChart from '@/Components/Charts/MiniChart.vue'
</script>
```

## Basic Usage

```vue
<template>
  <MiniChart :data="[10, 15, 8, 22, 18, 25, 30]" />
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `data` | `array` | `[]` | Data points |
| `type` | `string` | `'line'` | Chart type: `line`, `bar`, `area` |
| `width` | `number` | `100` | Chart width |
| `height` | `number` | `32` | Chart height |
| `color` | `string` | `'#6366f1'` | Chart color |
| `showChange` | `boolean` | `false` | Show percentage change |

## Types

### Line Sparkline

```vue
<MiniChart type="line" :data="data" />
```

### Area Sparkline

```vue
<MiniChart type="area" :data="data" />
```

### Bar Sparkline

```vue
<MiniChart type="bar" :data="data" />
```

## Examples

### In a Table

```vue
<template>
  <table>
    <tr v-for="product in products" :key="product.id">
      <td>{{ product.name }}</td>
      <td>{{ product.sales }}</td>
      <td>
        <MiniChart
          :data="product.salesHistory"
          :color="product.trend > 0 ? '#22c55e' : '#ef4444'"
        />
      </td>
    </tr>
  </table>
</template>
```

### With Change Indicator

```vue
<div class="flex items-center gap-2">
  <span class="font-medium">$12,500</span>
  <MiniChart :data="revenueHistory" show-change />
</div>
```

### Custom Sizes

```vue
<!-- Small -->
<MiniChart :data="data" :width="60" :height="20" />

<!-- Medium (default) -->
<MiniChart :data="data" :width="100" :height="32" />

<!-- Large -->
<MiniChart :data="data" :width="150" :height="48" />
```

### Custom Colors

```vue
<!-- Success color -->
<MiniChart :data="data" color="#22c55e" />

<!-- Warning color -->
<MiniChart :data="data" color="#f59e0b" />

<!-- Danger color -->
<MiniChart :data="data" color="#ef4444" />
```

## Real-World Example

```vue
<template>
  <Card>
    <table class="w-full">
      <thead>
        <tr>
          <th>Metric</th>
          <th>Current</th>
          <th>Trend</th>
          <th>Change</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="metric in metrics" :key="metric.name">
          <td>{{ metric.name }}</td>
          <td class="font-medium">{{ metric.value }}</td>
          <td>
            <MiniChart
              :data="metric.history"
              type="area"
              :color="metric.change >= 0 ? '#22c55e' : '#ef4444'"
            />
          </td>
          <td :class="metric.change >= 0 ? 'text-green-600' : 'text-red-600'">
            {{ metric.change >= 0 ? '+' : '' }}{{ metric.change }}%
          </td>
        </tr>
      </tbody>
    </table>
  </Card>
</template>

<script setup>
const metrics = [
  {
    name: 'Page Views',
    value: '12,543',
    history: [800, 950, 1100, 1050, 1200, 1350, 1500],
    change: 12.5
  },
  {
    name: 'Bounce Rate',
    value: '42.3%',
    history: [50, 48, 45, 47, 44, 43, 42],
    change: -8.2
  },
  {
    name: 'Session Duration',
    value: '3m 24s',
    history: [180, 190, 185, 195, 200, 205, 204],
    change: 5.1
  }
]
</script>
```

## Playground

Try the MiniChart component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
      <thead>
        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
          <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Metric</th>
          <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Current</th>
          <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Trend</th>
          <th style="padding: 12px 16px; text-align: right; font-weight: 600;">Change</th>
        </tr>
      </thead>
      <tbody>
        <tr style="border-bottom: 1px solid #e2e8f0;">
          <td style="padding: 12px 16px;">Page Views</td>
          <td style="padding: 12px 16px; font-weight: 500;">12,543</td>
          <td style="padding: 12px 16px;">
            <svg width="80" height="24" viewBox="0 0 80 24">
              <path d="M 0 18 L 12 16 L 24 12 L 36 14 L 48 10 L 60 8 L 72 4 L 80 6" fill="none" stroke="#22c55e" stroke-width="2" />
            </svg>
          </td>
          <td style="padding: 12px 16px; text-align: right; color: #22c55e;">+12.5%</td>
        </tr>
        <tr style="border-bottom: 1px solid #e2e8f0;">
          <td style="padding: 12px 16px;">Bounce Rate</td>
          <td style="padding: 12px 16px; font-weight: 500;">42.3%</td>
          <td style="padding: 12px 16px;">
            <svg width="80" height="24" viewBox="0 0 80 24">
              <path d="M 0 6 L 12 8 L 24 10 L 36 9 L 48 12 L 60 14 L 72 16 L 80 18" fill="none" stroke="#ef4444" stroke-width="2" />
            </svg>
          </td>
          <td style="padding: 12px 16px; text-align: right; color: #ef4444;">-8.2%</td>
        </tr>
        <tr>
          <td style="padding: 12px 16px;">Sessions</td>
          <td style="padding: 12px 16px; font-weight: 500;">8,921</td>
          <td style="padding: 12px 16px;">
            <svg width="80" height="24" viewBox="0 0 80 24">
              <path d="M 0 16 L 12 14 L 24 15 L 36 12 L 48 10 L 60 11 L 72 8 L 80 6" fill="none" stroke="#22c55e" stroke-width="2" />
            </svg>
          </td>
          <td style="padding: 12px 16px; text-align: right; color: #22c55e;">+5.1%</td>
        </tr>
      </tbody>
    </table>
  </div>

  <template #code>

```vue
<template>
  <table>
    <tr v-for="metric in metrics" :key="metric.name">
      <td>{{ metric.name }}</td>
      <td>{{ metric.value }}</td>
      <td>
        <MiniChart
          :data="metric.history"
          type="line"
          :color="metric.change >= 0 ? '#22c55e' : '#ef4444'"
        />
      </td>
      <td :class="metric.change >= 0 ? 'text-green-600' : 'text-red-600'">
        {{ metric.change >= 0 ? '+' : '' }}{{ metric.change }}%
      </td>
    </tr>
  </table>
</template>

<script setup>
import MiniChart from '@/Components/Charts/MiniChart.vue'

const metrics = [
  { name: 'Page Views', value: '12,543', history: [8, 9, 12, 10, 14, 16, 18], change: 12.5 },
  { name: 'Bounce Rate', value: '42.3%', history: [18, 16, 14, 15, 12, 10, 8], change: -8.2 },
  { name: 'Sessions', value: '8,921', history: [10, 12, 11, 14, 16, 15, 18], change: 5.1 }
]
</script>
```

  </template>
</LiveDemo>
