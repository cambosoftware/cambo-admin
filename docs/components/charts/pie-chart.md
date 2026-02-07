# PieChart

A classic pie chart for displaying proportional data.

## Import

```vue
<script setup>
import PieChart from '@/Components/Charts/PieChart.vue'
</script>
```

## Basic Usage

```vue
<template>
  <PieChart
    :labels="['Chrome', 'Firefox', 'Safari', 'Edge']"
    :data="[60, 20, 12, 8]"
  />
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `labels` | `array` | `[]` | Segment labels |
| `data` | `array` | `[]` | Segment values |
| `colors` | `array` | `null` | Custom colors |
| `height` | `number` | `300` | Chart height |
| `showLegend` | `boolean` | `true` | Show legend |
| `legendPosition` | `string` | `'bottom'` | Legend position |
| `showPercentages` | `boolean` | `false` | Show % on segments |

## Examples

### With Percentages

```vue
<PieChart
  :labels="['Desktop', 'Mobile', 'Tablet']"
  :data="[55, 35, 10]"
  show-percentages
/>
```

### Custom Colors

```vue
<PieChart
  :labels="['Red', 'Green', 'Blue']"
  :data="[33, 33, 34]"
  :colors="['#ef4444', '#22c55e', '#3b82f6']"
/>
```

### Legend on Right

```vue
<PieChart
  :labels="labels"
  :data="data"
  legend-position="right"
/>
```

## Real-World Example

```vue
<template>
  <Card>
    <template #header>
      <h3 class="font-semibold">Sales by Region</h3>
    </template>

    <PieChart
      :labels="regions.map(r => r.name)"
      :data="regions.map(r => r.sales)"
      :colors="regions.map(r => r.color)"
      legend-position="right"
      :height="250"
    />
  </Card>
</template>

<script setup>
const regions = [
  { name: 'North America', sales: 42000, color: '#6366f1' },
  { name: 'Europe', sales: 31000, color: '#22c55e' },
  { name: 'Asia Pacific', sales: 28000, color: '#f59e0b' },
  { name: 'Latin America', sales: 12000, color: '#ef4444' },
  { name: 'Africa', sales: 7000, color: '#8b5cf6' }
]
</script>
```

## Difference from DonutChart

- **PieChart**: Solid circle, good for 3-5 segments
- **DonutChart**: Has center hole, can display additional info in center

Use PieChart when you want a traditional pie visualization without the need for center content.

## Playground

Try the PieChart component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
    <div style="display: flex; align-items: center; gap: 32px;">
      <div style="width: 160px; height: 160px;">
        <svg viewBox="0 0 160 160" style="width: 100%; height: 100%; transform: rotate(-90deg);">
          <circle cx="80" cy="80" r="70" fill="none" stroke="#6366f1" stroke-width="70" stroke-dasharray="264 440" />
          <circle cx="80" cy="80" r="70" fill="none" stroke="#22c55e" stroke-width="70" stroke-dasharray="132 440" stroke-dashoffset="-264" />
          <circle cx="80" cy="80" r="70" fill="none" stroke="#f59e0b" stroke-width="70" stroke-dasharray="44 440" stroke-dashoffset="-396" />
        </svg>
      </div>
      <div style="flex: 1;">
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="width: 12px; height: 12px; background: #6366f1; border-radius: 2px;"></span>
            <span style="font-size: 14px;">Chrome</span>
          </div>
          <span style="font-size: 14px; font-weight: 500;">60%</span>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="width: 12px; height: 12px; background: #22c55e; border-radius: 2px;"></span>
            <span style="font-size: 14px;">Firefox</span>
          </div>
          <span style="font-size: 14px; font-weight: 500;">30%</span>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="width: 12px; height: 12px; background: #f59e0b; border-radius: 2px;"></span>
            <span style="font-size: 14px;">Safari</span>
          </div>
          <span style="font-size: 14px; font-weight: 500;">10%</span>
        </div>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <PieChart
    :labels="['Chrome', 'Firefox', 'Safari']"
    :data="[60, 30, 10]"
    :colors="['#6366f1', '#22c55e', '#f59e0b']"
    legend-position="right"
  />
</template>

<script setup>
import PieChart from '@/Components/Charts/PieChart.vue'
</script>
```

  </template>
</LiveDemo>
