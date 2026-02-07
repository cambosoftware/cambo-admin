# Charts

Data visualization components powered by Chart.js.

## Components

| Component | Description |
|-----------|-------------|
| [Chart](./chart.md) | Base chart wrapper |
| [LineChart](./line-chart.md) | Line/trend chart |
| [AreaChart](./area-chart.md) | Filled area chart |
| [BarChart](./bar-chart.md) | Bar/column chart |
| [DonutChart](./donut-chart.md) | Donut/ring chart |
| [PieChart](./pie-chart.md) | Pie chart |
| [StatCard](./stat-card.md) | Statistic display card |
| [StatGrid](./stat-grid.md) | Grid of stat cards |
| [MiniChart](./mini-chart.md) | Inline sparkline chart |

## Usage

### Line Chart

```vue
<LineChart
  :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May']"
  :datasets="[
    { label: 'Sales', data: [100, 200, 150, 300, 250] },
    { label: 'Orders', data: [50, 100, 75, 150, 125] },
  ]"
/>
```

### Stat Cards

```vue
<StatGrid :columns="4">
  <StatCard
    title="Total Revenue"
    value="$45,231"
    change="+12.5%"
    trend="up"
    :icon="CurrencyDollarIcon"
  />
  <StatCard
    title="Active Users"
    value="2,345"
    change="+5.2%"
    trend="up"
    :icon="UsersIcon"
  />
</StatGrid>
```

### Donut Chart

```vue
<DonutChart
  :labels="['Desktop', 'Mobile', 'Tablet']"
  :data="[60, 30, 10]"
  :colors="['#6366f1', '#8b5cf6', '#a78bfa']"
/>
```
