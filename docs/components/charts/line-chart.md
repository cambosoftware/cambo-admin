# LineChart

A pre-configured line chart component for displaying trends and time-series data.

## Import

```vue
<script setup>
import LineChart from '@/Components/Charts/LineChart.vue'
</script>
```

## Basic Usage

```vue
<template>
  <LineChart
    :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']"
    :datasets="[
      {
        label: 'Revenue',
        data: [4000, 4500, 5200, 4800, 5500, 6000]
      }
    ]"
  />
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `labels` | `array` | `[]` | X-axis labels |
| `datasets` | `array` | `[]` | Data series array |
| `height` | `number` | `300` | Chart height in pixels |
| `showLegend` | `boolean` | `true` | Show legend |
| `showGrid` | `boolean` | `true` | Show grid lines |
| `tension` | `number` | `0.4` | Line curve tension (0 = straight) |
| `fill` | `boolean` | `false` | Fill area under line |
| `gradient` | `boolean` | `false` | Use gradient fill |

## Dataset Options

Each dataset can include:

```javascript
{
  label: 'Series Name',
  data: [1, 2, 3, 4, 5],
  borderColor: '#6366f1',      // Line color
  backgroundColor: '#6366f1',   // Point/fill color
  tension: 0.4,                 // Curve smoothness
  fill: false,                  // Fill area under line
  pointRadius: 4,               // Point size
  pointHoverRadius: 6           // Point size on hover
}
```

## Examples

### Multiple Series

```vue
<LineChart
  :labels="months"
  :datasets="[
    {
      label: 'This Year',
      data: [12, 19, 15, 25, 22, 30],
      borderColor: '#6366f1'
    },
    {
      label: 'Last Year',
      data: [8, 15, 12, 18, 20, 25],
      borderColor: '#94a3b8',
      borderDash: [5, 5]
    }
  ]"
/>
```

### Area Chart (Filled)

```vue
<LineChart
  :labels="labels"
  :datasets="[{ label: 'Users', data: data }]"
  fill
  gradient
/>
```

### Smooth vs Straight Lines

```vue
<!-- Smooth curved lines -->
<LineChart :datasets="data" :tension="0.4" />

<!-- Straight lines -->
<LineChart :datasets="data" :tension="0" />
```

### Without Grid

```vue
<LineChart :datasets="data" :show-grid="false" />
```

### Stacked Area

```vue
<LineChart
  :labels="months"
  :datasets="[
    { label: 'Product A', data: [10, 20, 30], fill: true },
    { label: 'Product B', data: [15, 25, 20], fill: true },
    { label: 'Product C', data: [8, 12, 18], fill: true }
  ]"
  stacked
/>
```

## Real-World Example

```vue
<template>
  <Card>
    <template #header>
      <div class="flex justify-between items-center">
        <h3 class="font-semibold">Revenue Trend</h3>
        <Select v-model="period" :options="periodOptions" size="sm" />
      </div>
    </template>

    <LineChart
      :labels="chartLabels"
      :datasets="[
        {
          label: 'Revenue',
          data: revenueData,
          borderColor: '#6366f1'
        },
        {
          label: 'Expenses',
          data: expenseData,
          borderColor: '#ef4444'
        }
      ]"
      :height="350"
      fill
      gradient
    />
  </Card>
</template>
```

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `click` | `{ point, dataset }` | Point clicked |
| `hover` | `{ point, dataset }` | Point hovered |

## Playground

Try the LineChart component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
    <svg viewBox="0 0 400 150" style="width: 100%; height: 150px;">
      <defs>
        <linearGradient id="lineGradient" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" style="stop-color:#6366f1;stop-opacity:0.3" />
          <stop offset="100%" style="stop-color:#6366f1;stop-opacity:0" />
        </linearGradient>
      </defs>
      <path d="M 20 100 Q 60 80 100 70 T 180 50 T 260 60 T 340 30 L 340 130 L 20 130 Z" fill="url(#lineGradient)" />
      <path d="M 20 100 Q 60 80 100 70 T 180 50 T 260 60 T 340 30" fill="none" stroke="#6366f1" stroke-width="2" />
      <circle cx="20" cy="100" r="4" fill="#6366f1" />
      <circle cx="100" cy="70" r="4" fill="#6366f1" />
      <circle cx="180" cy="50" r="4" fill="#6366f1" />
      <circle cx="260" cy="60" r="4" fill="#6366f1" />
      <circle cx="340" cy="30" r="4" fill="#6366f1" />
      <text x="20" y="145" font-size="10" fill="#64748b" text-anchor="middle">Jan</text>
      <text x="100" y="145" font-size="10" fill="#64748b" text-anchor="middle">Feb</text>
      <text x="180" y="145" font-size="10" fill="#64748b" text-anchor="middle">Mar</text>
      <text x="260" y="145" font-size="10" fill="#64748b" text-anchor="middle">Apr</text>
      <text x="340" y="145" font-size="10" fill="#64748b" text-anchor="middle">May</text>
    </svg>
    <div style="text-align: center; margin-top: 8px;">
      <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: #64748b;">
        <span style="width: 12px; height: 3px; background: #6366f1; border-radius: 2px;"></span>
        Revenue
      </span>
    </div>
  </div>

  <template #code>

```vue
<template>
  <LineChart
    :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May']"
    :datasets="[
      {
        label: 'Revenue',
        data: [4000, 5200, 6000, 5500, 7000],
        borderColor: '#6366f1'
      }
    ]"
    fill
    gradient
  />
</template>

<script setup>
import LineChart from '@/Components/Charts/LineChart.vue'
</script>
```

  </template>
</LiveDemo>
