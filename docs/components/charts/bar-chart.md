# BarChart

A bar chart component for comparing categorical data.

## Import

```vue
<script setup>
import BarChart from '@/Components/Charts/BarChart.vue'
</script>
```

## Basic Usage

```vue
<template>
  <BarChart
    :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May']"
    :datasets="[
      {
        label: 'Sales',
        data: [65, 59, 80, 81, 56]
      }
    ]"
  />
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `labels` | `array` | `[]` | Category labels |
| `datasets` | `array` | `[]` | Data series |
| `height` | `number` | `300` | Chart height |
| `horizontal` | `boolean` | `false` | Horizontal bars |
| `stacked` | `boolean` | `false` | Stack bars |
| `showValues` | `boolean` | `false` | Show values on bars |
| `borderRadius` | `number` | `4` | Bar corner radius |

## Examples

### Horizontal Bars

```vue
<BarChart
  :labels="['Product A', 'Product B', 'Product C', 'Product D']"
  :datasets="[{ data: [120, 95, 80, 65] }]"
  horizontal
/>
```

### Grouped Bars

```vue
<BarChart
  :labels="quarters"
  :datasets="[
    {
      label: '2024',
      data: [65, 72, 78, 85],
      backgroundColor: '#6366f1'
    },
    {
      label: '2023',
      data: [55, 60, 65, 70],
      backgroundColor: '#94a3b8'
    }
  ]"
/>
```

### Stacked Bars

```vue
<BarChart
  :labels="months"
  :datasets="[
    { label: 'Desktop', data: [400, 450, 500], backgroundColor: '#6366f1' },
    { label: 'Mobile', data: [300, 350, 400], backgroundColor: '#22c55e' },
    { label: 'Tablet', data: [100, 120, 150], backgroundColor: '#f59e0b' }
  ]"
  stacked
/>
```

### With Values on Bars

```vue
<BarChart
  :labels="products"
  :datasets="[{ data: salesData }]"
  show-values
/>
```

### Custom Colors

```vue
<BarChart
  :labels="['Red', 'Blue', 'Green', 'Yellow', 'Purple']"
  :datasets="[{
    data: [12, 19, 8, 15, 10],
    backgroundColor: ['#ef4444', '#3b82f6', '#22c55e', '#eab308', '#a855f7']
  }]"
/>
```

## Real-World Example

```vue
<template>
  <Card>
    <template #header>
      <div class="flex justify-between items-center">
        <h3 class="font-semibold">Sales by Category</h3>
        <Toggle
          v-model="showComparison"
          label="Compare with last year"
        />
      </div>
    </template>

    <BarChart
      :labels="categories"
      :datasets="chartDatasets"
      :height="350"
      :border-radius="6"
    />

    <template #footer>
      <div class="flex justify-between text-sm text-gray-500">
        <span>Total: ${{ totalSales.toLocaleString() }}</span>
        <span class="text-green-600">+12.5% vs last year</span>
      </div>
    </template>
  </Card>
</template>

<script setup>
import { computed, ref } from 'vue'

const showComparison = ref(false)
const categories = ['Electronics', 'Clothing', 'Home', 'Sports', 'Books']

const chartDatasets = computed(() => {
  const datasets = [{
    label: 'This Year',
    data: [12500, 9800, 7500, 5200, 3800],
    backgroundColor: '#6366f1'
  }]

  if (showComparison.value) {
    datasets.push({
      label: 'Last Year',
      data: [11000, 8500, 7000, 4800, 3500],
      backgroundColor: '#cbd5e1'
    })
  }

  return datasets
})
</script>
```

## Playground

Try the BarChart component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
    <div style="height: 180px; display: flex; align-items: flex-end; gap: 16px; padding: 0 20px;">
      <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
        <div style="display: flex; gap: 4px; align-items: flex-end; width: 100%; height: 140px;">
          <div style="flex: 1; background: #6366f1; border-radius: 4px 4px 0 0; height: 70%;"></div>
          <div style="flex: 1; background: #cbd5e1; border-radius: 4px 4px 0 0; height: 55%;"></div>
        </div>
        <span style="font-size: 12px; color: #64748b;">Q1</span>
      </div>
      <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
        <div style="display: flex; gap: 4px; align-items: flex-end; width: 100%; height: 140px;">
          <div style="flex: 1; background: #6366f1; border-radius: 4px 4px 0 0; height: 85%;"></div>
          <div style="flex: 1; background: #cbd5e1; border-radius: 4px 4px 0 0; height: 70%;"></div>
        </div>
        <span style="font-size: 12px; color: #64748b;">Q2</span>
      </div>
      <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
        <div style="display: flex; gap: 4px; align-items: flex-end; width: 100%; height: 140px;">
          <div style="flex: 1; background: #6366f1; border-radius: 4px 4px 0 0; height: 90%;"></div>
          <div style="flex: 1; background: #cbd5e1; border-radius: 4px 4px 0 0; height: 75%;"></div>
        </div>
        <span style="font-size: 12px; color: #64748b;">Q3</span>
      </div>
      <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
        <div style="display: flex; gap: 4px; align-items: flex-end; width: 100%; height: 140px;">
          <div style="flex: 1; background: #6366f1; border-radius: 4px 4px 0 0; height: 100%;"></div>
          <div style="flex: 1; background: #cbd5e1; border-radius: 4px 4px 0 0; height: 82%;"></div>
        </div>
        <span style="font-size: 12px; color: #64748b;">Q4</span>
      </div>
    </div>
    <div style="display: flex; justify-content: center; gap: 16px; margin-top: 12px;">
      <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: #64748b;">
        <span style="width: 12px; height: 12px; background: #6366f1; border-radius: 2px;"></span>
        2024
      </span>
      <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: #64748b;">
        <span style="width: 12px; height: 12px; background: #cbd5e1; border-radius: 2px;"></span>
        2023
      </span>
    </div>
  </div>

  <template #code>

```vue
<template>
  <BarChart
    :labels="['Q1', 'Q2', 'Q3', 'Q4']"
    :datasets="[
      {
        label: '2024',
        data: [65, 72, 78, 85],
        backgroundColor: '#6366f1'
      },
      {
        label: '2023',
        data: [55, 60, 65, 70],
        backgroundColor: '#cbd5e1'
      }
    ]"
  />
</template>

<script setup>
import BarChart from '@/Components/Charts/BarChart.vue'
</script>
```

  </template>
</LiveDemo>
