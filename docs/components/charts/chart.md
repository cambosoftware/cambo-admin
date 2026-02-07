# Chart

A flexible wrapper component for creating various chart types using Chart.js.

## Import

```vue
<script setup>
import Chart from '@/Components/Charts/Chart.vue'
</script>
```

## Basic Usage

```vue
<template>
  <Chart type="bar" :data="chartData" :options="chartOptions" />
</template>

<script setup>
const chartData = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
  datasets: [{
    label: 'Sales',
    data: [12, 19, 3, 5, 2, 3],
    backgroundColor: '#6366f1'
  }]
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false
}
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `type` | `string` | `'line'` | Chart type: `line`, `bar`, `pie`, `doughnut`, `radar`, `polarArea` |
| `data` | `object` | `{}` | Chart.js data object |
| `options` | `object` | `{}` | Chart.js options object |
| `height` | `number\|string` | `300` | Chart height |
| `width` | `number\|string` | `'100%'` | Chart width |

## Chart Types

### Line Chart

```vue
<Chart
  type="line"
  :data="{
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
    datasets: [{
      label: 'Revenue',
      data: [1200, 1900, 1500, 2500, 2200],
      borderColor: '#6366f1',
      tension: 0.4
    }]
  }"
/>
```

### Bar Chart

```vue
<Chart
  type="bar"
  :data="{
    labels: ['Q1', 'Q2', 'Q3', 'Q4'],
    datasets: [{
      label: 'Sales',
      data: [65, 59, 80, 81],
      backgroundColor: ['#6366f1', '#8b5cf6', '#a855f7', '#c084fc']
    }]
  }"
/>
```

### Pie Chart

```vue
<Chart
  type="pie"
  :data="{
    labels: ['Desktop', 'Mobile', 'Tablet'],
    datasets: [{
      data: [55, 35, 10],
      backgroundColor: ['#6366f1', '#22c55e', '#f59e0b']
    }]
  }"
/>
```

### Doughnut Chart

```vue
<Chart
  type="doughnut"
  :data="{
    labels: ['Completed', 'In Progress', 'Pending'],
    datasets: [{
      data: [40, 35, 25],
      backgroundColor: ['#22c55e', '#3b82f6', '#f59e0b']
    }]
  }"
/>
```

## Common Options

```javascript
const options = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        usePointStyle: true
      }
    },
    title: {
      display: true,
      text: 'Chart Title'
    },
    tooltip: {
      mode: 'index',
      intersect: false
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        color: 'rgba(0, 0, 0, 0.05)'
      }
    },
    x: {
      grid: {
        display: false
      }
    }
  }
}
```

## Methods

Access the chart instance via ref:

```vue
<template>
  <Chart ref="chartRef" :data="data" />
  <Button @click="updateChart">Update</Button>
</template>

<script setup>
import { ref } from 'vue'

const chartRef = ref()

const updateChart = () => {
  // Access Chart.js instance
  chartRef.value.chart.update()
}
</script>
```

## Dark Mode Support

The chart automatically adapts to dark mode when using the theme system.

## See Also

- [LineChart](./line-chart) - Specialized line chart
- [BarChart](./bar-chart) - Specialized bar chart
- [DonutChart](./donut-chart) - Specialized donut chart

## Playground

Try the Chart component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
    <div style="height: 200px; display: flex; align-items: flex-end; gap: 12px; padding: 0 20px;">
      <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
        <div style="width: 100%; background: #6366f1; border-radius: 4px 4px 0 0; height: 60%;"></div>
        <span style="font-size: 12px; color: #64748b;">Jan</span>
      </div>
      <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
        <div style="width: 100%; background: #6366f1; border-radius: 4px 4px 0 0; height: 80%;"></div>
        <span style="font-size: 12px; color: #64748b;">Feb</span>
      </div>
      <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
        <div style="width: 100%; background: #6366f1; border-radius: 4px 4px 0 0; height: 45%;"></div>
        <span style="font-size: 12px; color: #64748b;">Mar</span>
      </div>
      <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
        <div style="width: 100%; background: #6366f1; border-radius: 4px 4px 0 0; height: 70%;"></div>
        <span style="font-size: 12px; color: #64748b;">Apr</span>
      </div>
      <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
        <div style="width: 100%; background: #6366f1; border-radius: 4px 4px 0 0; height: 55%;"></div>
        <span style="font-size: 12px; color: #64748b;">May</span>
      </div>
      <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
        <div style="width: 100%; background: #6366f1; border-radius: 4px 4px 0 0; height: 90%;"></div>
        <span style="font-size: 12px; color: #64748b;">Jun</span>
      </div>
    </div>
    <div style="text-align: center; margin-top: 12px;">
      <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: #64748b;">
        <span style="width: 12px; height: 12px; background: #6366f1; border-radius: 2px;"></span>
        Sales
      </span>
    </div>
  </div>

  <template #code>

```vue
<template>
  <Chart type="bar" :data="chartData" :options="chartOptions" />
</template>

<script setup>
import Chart from '@/Components/Charts/Chart.vue'

const chartData = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
  datasets: [{
    label: 'Sales',
    data: [12, 19, 8, 15, 10, 22],
    backgroundColor: '#6366f1'
  }]
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false
}
</script>
```

  </template>
</LiveDemo>
