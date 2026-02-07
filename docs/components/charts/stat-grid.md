# StatGrid

A responsive grid layout for displaying multiple StatCards.

## Import

```vue
<script setup>
import StatGrid from '@/Components/Charts/StatGrid.vue'
import StatCard from '@/Components/Charts/StatCard.vue'
</script>
```

## Basic Usage

```vue
<template>
  <StatGrid>
    <StatCard title="Revenue" value="$45,231" change="+12%" trend="up" />
    <StatCard title="Orders" value="1,234" change="+8%" trend="up" />
    <StatCard title="Customers" value="567" change="+15%" trend="up" />
    <StatCard title="Conversion" value="3.2%" change="-2%" trend="down" />
  </StatGrid>
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `cols` | `number` | `4` | Columns on large screens |
| `gap` | `number` | `6` | Gap between cards (Tailwind spacing) |

## Responsive Behavior

The grid automatically adjusts:

- **Mobile**: 1 column
- **Tablet**: 2 columns
- **Desktop**: 3-4 columns (based on `cols` prop)

```vue
<!-- 4 columns on desktop -->
<StatGrid :cols="4">...</StatGrid>

<!-- 3 columns on desktop -->
<StatGrid :cols="3">...</StatGrid>

<!-- 2 columns on desktop -->
<StatGrid :cols="2">...</StatGrid>
```

## Example with Data

```vue
<template>
  <StatGrid :cols="4">
    <StatCard
      v-for="stat in stats"
      :key="stat.title"
      :title="stat.title"
      :value="stat.value"
      :change="stat.change"
      :trend="stat.trend"
      :icon="stat.icon"
      :icon-color="stat.color"
    />
  </StatGrid>
</template>

<script setup>
import {
  CurrencyDollarIcon,
  ShoppingCartIcon,
  UsersIcon,
  ChartBarIcon
} from '@heroicons/vue/24/outline'

const stats = [
  {
    title: 'Total Revenue',
    value: '$45,231',
    change: '+12.5%',
    trend: 'up',
    icon: CurrencyDollarIcon,
    color: 'primary'
  },
  {
    title: 'Orders',
    value: '1,234',
    change: '+8.2%',
    trend: 'up',
    icon: ShoppingCartIcon,
    color: 'success'
  },
  {
    title: 'Customers',
    value: '567',
    change: '+15.3%',
    trend: 'up',
    icon: UsersIcon,
    color: 'info'
  },
  {
    title: 'Conversion',
    value: '3.2%',
    change: '-2.1%',
    trend: 'down',
    icon: ChartBarIcon,
    color: 'warning'
  }
]
</script>
```

## Playground

Try the StatGrid component:

<LiveDemo>
  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
          <p style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Revenue</p>
          <p style="font-size: 24px; font-weight: 700;">$45,231</p>
        </div>
        <div style="width: 40px; height: 40px; background: #eff6ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 18px;">$</div>
      </div>
      <div style="margin-top: 12px; display: flex; align-items: center; gap: 4px;">
        <span style="color: #22c55e; font-size: 12px;">+12.5%</span>
        <span style="font-size: 12px; color: #64748b;">vs last month</span>
      </div>
    </div>
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
          <p style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Orders</p>
          <p style="font-size: 24px; font-weight: 700;">1,234</p>
        </div>
        <div style="width: 40px; height: 40px; background: #f0fdf4; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #22c55e; font-size: 18px;">&#128722;</div>
      </div>
      <div style="margin-top: 12px; display: flex; align-items: center; gap: 4px;">
        <span style="color: #22c55e; font-size: 12px;">+8.2%</span>
        <span style="font-size: 12px; color: #64748b;">vs last month</span>
      </div>
    </div>
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
          <p style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Customers</p>
          <p style="font-size: 24px; font-weight: 700;">567</p>
        </div>
        <div style="width: 40px; height: 40px; background: #fef3c7; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 18px;">&#128101;</div>
      </div>
      <div style="margin-top: 12px; display: flex; align-items: center; gap: 4px;">
        <span style="color: #22c55e; font-size: 12px;">+15.3%</span>
        <span style="font-size: 12px; color: #64748b;">vs last month</span>
      </div>
    </div>
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
          <p style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Conversion</p>
          <p style="font-size: 24px; font-weight: 700;">3.2%</p>
        </div>
        <div style="width: 40px; height: 40px; background: #fef2f2; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ef4444; font-size: 18px;">&#128200;</div>
      </div>
      <div style="margin-top: 12px; display: flex; align-items: center; gap: 4px;">
        <span style="color: #ef4444; font-size: 12px;">-2.1%</span>
        <span style="font-size: 12px; color: #64748b;">vs last month</span>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <StatGrid :cols="4">
    <StatCard
      v-for="stat in stats"
      :key="stat.title"
      :title="stat.title"
      :value="stat.value"
      :change="stat.change"
      :trend="stat.trend"
      :icon="stat.icon"
    />
  </StatGrid>
</template>

<script setup>
import { StatGrid, StatCard } from '@cambosoftware/cambo-admin'

const stats = [
  { title: 'Revenue', value: '$45,231', change: '+12.5%', trend: 'up' },
  { title: 'Orders', value: '1,234', change: '+8.2%', trend: 'up' },
  { title: 'Customers', value: '567', change: '+15.3%', trend: 'up' },
  { title: 'Conversion', value: '3.2%', change: '-2.1%', trend: 'down' }
]
</script>
```

  </template>
</LiveDemo>
