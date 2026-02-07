# AreaChart

A line chart with filled area, ideal for showing volume or cumulative data.

## Import

```vue
<script setup>
import AreaChart from '@/Components/Charts/AreaChart.vue'
</script>
```

## Basic Usage

```vue
<template>
  <AreaChart
    :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']"
    :datasets="[
      {
        label: 'Page Views',
        data: [1200, 1900, 1500, 2500, 2200, 3000]
      }
    ]"
  />
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `labels` | `array` | `[]` | X-axis labels |
| `datasets` | `array` | `[]` | Data series |
| `height` | `number` | `300` | Chart height |
| `stacked` | `boolean` | `false` | Stack multiple series |
| `gradient` | `boolean` | `true` | Use gradient fill |
| `tension` | `number` | `0.4` | Line smoothness |

## Examples

### Gradient Area

```vue
<AreaChart
  :labels="months"
  :datasets="[{
    label: 'Users',
    data: [500, 800, 1200, 1500, 2000, 2500],
    backgroundColor: 'rgba(99, 102, 241, 0.3)',
    borderColor: '#6366f1'
  }]"
  gradient
/>
```

### Stacked Areas

```vue
<AreaChart
  :labels="months"
  :datasets="[
    {
      label: 'Organic',
      data: [400, 600, 800, 1000, 1200, 1400],
      backgroundColor: 'rgba(34, 197, 94, 0.5)'
    },
    {
      label: 'Direct',
      data: [200, 300, 350, 400, 500, 600],
      backgroundColor: 'rgba(59, 130, 246, 0.5)'
    },
    {
      label: 'Referral',
      data: [100, 150, 200, 250, 300, 350],
      backgroundColor: 'rgba(249, 115, 22, 0.5)'
    }
  ]"
  stacked
/>
```

### Comparison Chart

```vue
<AreaChart
  :labels="days"
  :datasets="[
    {
      label: 'This Week',
      data: thisWeekData,
      backgroundColor: 'rgba(99, 102, 241, 0.3)',
      borderColor: '#6366f1'
    },
    {
      label: 'Last Week',
      data: lastWeekData,
      backgroundColor: 'rgba(156, 163, 175, 0.2)',
      borderColor: '#9ca3af',
      borderDash: [5, 5]
    }
  ]"
/>
```

## Real-World Example

```vue
<template>
  <Card>
    <template #header>
      <h3 class="font-semibold">Traffic Overview</h3>
    </template>

    <AreaChart
      :labels="trafficLabels"
      :datasets="[
        {
          label: 'Page Views',
          data: pageViews,
          backgroundColor: 'rgba(99, 102, 241, 0.2)',
          borderColor: '#6366f1'
        },
        {
          label: 'Unique Visitors',
          data: uniqueVisitors,
          backgroundColor: 'rgba(34, 197, 94, 0.2)',
          borderColor: '#22c55e'
        }
      ]"
      :height="300"
    />

    <div class="flex justify-center gap-8 mt-4">
      <div class="text-center">
        <p class="text-2xl font-bold">{{ totalViews }}</p>
        <p class="text-sm text-gray-500">Total Views</p>
      </div>
      <div class="text-center">
        <p class="text-2xl font-bold">{{ totalVisitors }}</p>
        <p class="text-sm text-gray-500">Unique Visitors</p>
      </div>
    </div>
  </Card>
</template>
```

## Playground

Try the AreaChart component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
    <svg viewBox="0 0 400 150" style="width: 100%; height: 150px;">
      <defs>
        <linearGradient id="areaGradient1" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" style="stop-color:#22c55e;stop-opacity:0.4" />
          <stop offset="100%" style="stop-color:#22c55e;stop-opacity:0.1" />
        </linearGradient>
        <linearGradient id="areaGradient2" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:0.4" />
          <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:0.1" />
        </linearGradient>
      </defs>
      <path d="M 20 90 Q 60 85 100 75 T 180 60 T 260 50 T 340 35 L 340 130 L 20 130 Z" fill="url(#areaGradient1)" />
      <path d="M 20 90 Q 60 85 100 75 T 180 60 T 260 50 T 340 35" fill="none" stroke="#22c55e" stroke-width="2" />
      <path d="M 20 110 Q 60 100 100 95 T 180 85 T 260 75 T 340 60 L 340 130 L 20 130 Z" fill="url(#areaGradient2)" />
      <path d="M 20 110 Q 60 100 100 95 T 180 85 T 260 75 T 340 60" fill="none" stroke="#3b82f6" stroke-width="2" />
      <text x="20" y="145" font-size="10" fill="#64748b" text-anchor="middle">Jan</text>
      <text x="100" y="145" font-size="10" fill="#64748b" text-anchor="middle">Feb</text>
      <text x="180" y="145" font-size="10" fill="#64748b" text-anchor="middle">Mar</text>
      <text x="260" y="145" font-size="10" fill="#64748b" text-anchor="middle">Apr</text>
      <text x="340" y="145" font-size="10" fill="#64748b" text-anchor="middle">May</text>
    </svg>
    <div style="display: flex; justify-content: center; gap: 16px; margin-top: 8px;">
      <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: #64748b;">
        <span style="width: 12px; height: 12px; background: rgba(34, 197, 94, 0.4); border: 2px solid #22c55e; border-radius: 2px;"></span>
        Page Views
      </span>
      <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: #64748b;">
        <span style="width: 12px; height: 12px; background: rgba(59, 130, 246, 0.4); border: 2px solid #3b82f6; border-radius: 2px;"></span>
        Unique Visitors
      </span>
    </div>
  </div>

  <template #code>

```vue
<template>
  <AreaChart
    :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May']"
    :datasets="[
      {
        label: 'Page Views',
        data: [1200, 1500, 1800, 2100, 2500],
        backgroundColor: 'rgba(34, 197, 94, 0.3)',
        borderColor: '#22c55e'
      },
      {
        label: 'Unique Visitors',
        data: [800, 1000, 1200, 1400, 1700],
        backgroundColor: 'rgba(59, 130, 246, 0.3)',
        borderColor: '#3b82f6'
      }
    ]"
    gradient
  />
</template>

<script setup>
import AreaChart from '@/Components/Charts/AreaChart.vue'
</script>
```

  </template>
</LiveDemo>
