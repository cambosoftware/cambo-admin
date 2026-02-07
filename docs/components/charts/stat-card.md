# StatCard

A card component for displaying key metrics with optional trends and charts.

## Import

```vue
<script setup>
import StatCard from '@/Components/Charts/StatCard.vue'
</script>
```

## Basic Usage

```vue
<template>
  <StatCard
    title="Total Revenue"
    value="$45,231"
    change="+12.5%"
    trend="up"
  />
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `string` | `''` | Metric title |
| `value` | `string\|number` | `''` | Main value |
| `change` | `string` | `null` | Change amount/percentage |
| `trend` | `string` | `null` | Trend direction: `up`, `down`, `neutral` |
| `icon` | `Component` | `null` | Icon component |
| `iconColor` | `string` | `'primary'` | Icon background color |
| `loading` | `boolean` | `false` | Show loading state |
| `href` | `string` | `null` | Link URL |
| `sparkline` | `array` | `null` | Mini chart data |

## Variants

### With Icon

```vue
<script setup>
import { CurrencyDollarIcon } from '@heroicons/vue/24/outline'
</script>

<template>
  <StatCard
    title="Revenue"
    value="$45,231"
    change="+12.5%"
    trend="up"
    :icon="CurrencyDollarIcon"
    icon-color="primary"
  />
</template>
```

### With Sparkline

```vue
<StatCard
  title="Page Views"
  value="12,543"
  change="+8.2%"
  trend="up"
  :sparkline="[10, 15, 8, 22, 18, 25, 30]"
/>
```

### Different Trends

```vue
<!-- Positive trend -->
<StatCard title="Sales" value="$12,500" change="+15%" trend="up" />

<!-- Negative trend -->
<StatCard title="Refunds" value="$1,200" change="-8%" trend="down" />

<!-- Neutral trend -->
<StatCard title="Orders" value="156" change="0%" trend="neutral" />
```

### Loading State

```vue
<StatCard
  title="Loading..."
  loading
/>
```

### As Link

```vue
<StatCard
  title="Active Users"
  value="1,234"
  href="/admin/users?status=active"
/>
```

## Icon Colors

Available colors for `iconColor`:

- `primary` - Indigo
- `success` - Green
- `warning` - Yellow
- `danger` - Red
- `info` - Blue

## Real-World Example

```vue
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <StatCard
      title="Total Revenue"
      :value="formatCurrency(stats.revenue)"
      :change="stats.revenueChange"
      :trend="stats.revenueChange.startsWith('+') ? 'up' : 'down'"
      :icon="CurrencyDollarIcon"
      icon-color="primary"
      :sparkline="stats.revenueHistory"
    />

    <StatCard
      title="Orders"
      :value="stats.orders.toLocaleString()"
      :change="stats.ordersChange"
      :trend="stats.ordersChange.startsWith('+') ? 'up' : 'down'"
      :icon="ShoppingCartIcon"
      icon-color="success"
    />

    <StatCard
      title="Customers"
      :value="stats.customers.toLocaleString()"
      :change="stats.customersChange"
      :trend="stats.customersChange.startsWith('+') ? 'up' : 'down'"
      :icon="UsersIcon"
      icon-color="info"
    />

    <StatCard
      title="Conversion Rate"
      :value="stats.conversionRate + '%'"
      :change="stats.conversionChange"
      :trend="stats.conversionChange.startsWith('+') ? 'up' : 'down'"
      :icon="ChartBarIcon"
      icon-color="warning"
    />
  </div>
</template>
```

## Slots

| Slot | Description |
|------|-------------|
| `icon` | Custom icon content |
| `value` | Custom value display |
| `footer` | Additional content below |

### Custom Footer

```vue
<StatCard title="Tasks" value="24">
  <template #footer>
    <div class="flex gap-4 text-sm">
      <span class="text-green-600">12 completed</span>
      <span class="text-yellow-600">8 in progress</span>
      <span class="text-gray-500">4 pending</span>
    </div>
  </template>
</StatCard>
```

## Playground

Try the stat card component:

<LiveDemo>
  <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
    <DemoStatCard title="Revenue" value="$45,231" change="+12.5%" trend="up" />
    <DemoStatCard title="Orders" value="1,234" change="+8.2%" trend="up" />
    <DemoStatCard title="Refunds" value="$1,200" change="-8%" trend="down" />
    <DemoStatCard title="Customers" value="5,678" change="0%" trend="neutral" />
  </div>

  <template #code>

```vue
<StatCard title="Revenue" value="$45,231" change="+12.5%" trend="up" />
<StatCard title="Orders" value="1,234" change="+8.2%" trend="up" />
<StatCard title="Refunds" value="$1,200" change="-8%" trend="down" />
<StatCard title="Customers" value="5,678" change="0%" trend="neutral" />
```

  </template>
</LiveDemo>
