# DonutChart

A circular chart with a center cutout, perfect for displaying proportions.

## Import

```vue
<script setup>
import DonutChart from '@/Components/Charts/DonutChart.vue'
</script>
```

## Basic Usage

```vue
<template>
  <DonutChart
    :labels="['Desktop', 'Mobile', 'Tablet']"
    :data="[55, 35, 10]"
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
| `cutout` | `string` | `'70%'` | Center hole size |
| `showLegend` | `boolean` | `true` | Show legend |
| `legendPosition` | `string` | `'bottom'` | Legend position |
| `showTotal` | `boolean` | `false` | Show total in center |
| `totalLabel` | `string` | `'Total'` | Center label text |

## Examples

### With Custom Colors

```vue
<DonutChart
  :labels="['Success', 'Warning', 'Error']"
  :data="[70, 20, 10]"
  :colors="['#22c55e', '#f59e0b', '#ef4444']"
/>
```

### Show Total in Center

```vue
<DonutChart
  :labels="['Completed', 'In Progress', 'Pending']"
  :data="[45, 30, 25]"
  show-total
  total-label="Tasks"
/>
```

### Different Cutout Sizes

```vue
<!-- Thin ring -->
<DonutChart :data="data" cutout="80%" />

<!-- Standard (default) -->
<DonutChart :data="data" cutout="70%" />

<!-- Thick ring -->
<DonutChart :data="data" cutout="50%" />

<!-- Pie chart (no hole) -->
<DonutChart :data="data" cutout="0%" />
```

### Legend Positions

```vue
<DonutChart :data="data" legend-position="top" />
<DonutChart :data="data" legend-position="bottom" />
<DonutChart :data="data" legend-position="left" />
<DonutChart :data="data" legend-position="right" />
```

### With Custom Center Content

```vue
<template>
  <div class="relative">
    <DonutChart
      :labels="labels"
      :data="data"
      :show-legend="false"
    />
    <div class="absolute inset-0 flex items-center justify-center">
      <div class="text-center">
        <p class="text-3xl font-bold">{{ percentage }}%</p>
        <p class="text-sm text-gray-500">Completion</p>
      </div>
    </div>
  </div>
</template>
```

## Real-World Example

```vue
<template>
  <Card>
    <template #header>
      <h3 class="font-semibold">Traffic Sources</h3>
    </template>

    <div class="flex items-center gap-8">
      <div class="w-48">
        <DonutChart
          :labels="sources.map(s => s.name)"
          :data="sources.map(s => s.value)"
          :colors="sources.map(s => s.color)"
          :show-legend="false"
          show-total
          total-label="Visits"
        />
      </div>

      <div class="flex-1 space-y-3">
        <div
          v-for="source in sources"
          :key="source.name"
          class="flex items-center justify-between"
        >
          <div class="flex items-center gap-2">
            <span
              class="w-3 h-3 rounded-full"
              :style="{ backgroundColor: source.color }"
            />
            <span>{{ source.name }}</span>
          </div>
          <div class="text-right">
            <span class="font-medium">{{ source.value.toLocaleString() }}</span>
            <span class="text-gray-500 text-sm ml-2">
              ({{ source.percentage }}%)
            </span>
          </div>
        </div>
      </div>
    </div>
  </Card>
</template>

<script setup>
const sources = [
  { name: 'Organic Search', value: 4500, percentage: 45, color: '#6366f1' },
  { name: 'Direct', value: 2500, percentage: 25, color: '#22c55e' },
  { name: 'Social Media', value: 1500, percentage: 15, color: '#f59e0b' },
  { name: 'Referral', value: 1000, percentage: 10, color: '#ef4444' },
  { name: 'Email', value: 500, percentage: 5, color: '#8b5cf6' }
]
</script>
```

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `click` | `{ label, value, index }` | Segment clicked |
| `hover` | `{ label, value, index }` | Segment hovered |

## Playground

Try the DonutChart component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
    <div style="display: flex; align-items: center; gap: 32px;">
      <div style="position: relative; width: 160px; height: 160px;">
        <svg viewBox="0 0 160 160" style="width: 100%; height: 100%; transform: rotate(-90deg);">
          <circle cx="80" cy="80" r="60" fill="none" stroke="#6366f1" stroke-width="24" stroke-dasharray="207 377" />
          <circle cx="80" cy="80" r="60" fill="none" stroke="#22c55e" stroke-width="24" stroke-dasharray="132 377" stroke-dashoffset="-207" />
          <circle cx="80" cy="80" r="60" fill="none" stroke="#f59e0b" stroke-width="24" stroke-dasharray="38 377" stroke-dashoffset="-339" />
        </svg>
        <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center;">
          <span style="font-size: 24px; font-weight: 700;">10K</span>
          <span style="font-size: 12px; color: #64748b;">Total</span>
        </div>
      </div>
      <div style="flex: 1;">
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="width: 12px; height: 12px; background: #6366f1; border-radius: 50%;"></span>
            <span style="font-size: 14px;">Desktop</span>
          </div>
          <span style="font-size: 14px; font-weight: 500;">55%</span>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="width: 12px; height: 12px; background: #22c55e; border-radius: 50%;"></span>
            <span style="font-size: 14px;">Mobile</span>
          </div>
          <span style="font-size: 14px; font-weight: 500;">35%</span>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="width: 12px; height: 12px; background: #f59e0b; border-radius: 50%;"></span>
            <span style="font-size: 14px;">Tablet</span>
          </div>
          <span style="font-size: 14px; font-weight: 500;">10%</span>
        </div>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <DonutChart
    :labels="['Desktop', 'Mobile', 'Tablet']"
    :data="[55, 35, 10]"
    :colors="['#6366f1', '#22c55e', '#f59e0b']"
    show-total
    total-label="Total"
  />
</template>

<script setup>
import DonutChart from '@/Components/Charts/DonutChart.vue'
</script>
```

  </template>
</LiveDemo>
