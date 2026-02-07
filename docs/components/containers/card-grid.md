# CardGrid

A responsive grid layout component for organizing cards or other content in columns.

## Import

```vue
<script setup>
import { CardGrid } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `cols` | `Number \| Object` | `3` | Number of columns (1-6). Automatically responsive. |
| `gap` | `String` | `'md'` | Gap between items. Values: `'sm'`, `'md'`, `'lg'` |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Grid items (typically Card components) |

## Events

This component does not emit any events.

## Column Breakpoints

The `cols` prop creates responsive layouts automatically:

| cols | Mobile | Small (sm) | Large (lg) | Extra Large (xl) |
|------|--------|------------|------------|------------------|
| 1 | 1 | 1 | 1 | 1 |
| 2 | 1 | 2 | 2 | 2 |
| 3 | 1 | 2 | 3 | 3 |
| 4 | 1 | 2 | 4 | 4 |
| 5 | 1 | 2 | 3 | 5 |
| 6 | 1 | 2 | 3 | 6 |

## Gap Sizes

| gap | Tailwind Class |
|-----|----------------|
| `'sm'` | `gap-3` |
| `'md'` | `gap-5` |
| `'lg'` | `gap-8` |

## Examples

### Basic Grid

```vue
<template>
    <CardGrid>
        <Card title="Card 1">Content 1</Card>
        <Card title="Card 2">Content 2</Card>
        <Card title="Card 3">Content 3</Card>
    </CardGrid>
</template>
```

### Two Column Grid

```vue
<template>
    <CardGrid :cols="2">
        <Card title="Left">Left content</Card>
        <Card title="Right">Right content</Card>
    </CardGrid>
</template>
```

### Four Column Grid

```vue
<template>
    <CardGrid :cols="4">
        <Card v-for="i in 8" :key="i" :title="`Card ${i}`">
            Content {{ i }}
        </Card>
    </CardGrid>
</template>
```

### Grid with Small Gap

```vue
<template>
    <CardGrid :cols="3" gap="sm">
        <Card title="Compact 1">Content</Card>
        <Card title="Compact 2">Content</Card>
        <Card title="Compact 3">Content</Card>
    </CardGrid>
</template>
```

### Grid with Large Gap

```vue
<template>
    <CardGrid :cols="2" gap="lg">
        <Card title="Spacious 1">
            <p>More breathing room between cards.</p>
        </Card>
        <Card title="Spacious 2">
            <p>Great for featured content.</p>
        </Card>
    </CardGrid>
</template>
```

### Dashboard Layout

```vue
<template>
    <div class="space-y-6">
        <!-- Stats row -->
        <CardGrid :cols="4">
            <StatCard title="Revenue" value="$45,231" />
            <StatCard title="Orders" value="1,234" />
            <StatCard title="Customers" value="567" />
            <StatCard title="Products" value="89" />
        </CardGrid>

        <!-- Content row -->
        <CardGrid :cols="2">
            <Card title="Recent Activity">
                <Timeline :items="activities" />
            </Card>
            <Card title="Quick Actions">
                <ActionList :actions="actions" />
            </Card>
        </CardGrid>
    </div>
</template>
```

### Single Column on Desktop

```vue
<template>
    <CardGrid :cols="1">
        <Card title="Full Width Card">
            <p>This card spans the full width.</p>
        </Card>
    </CardGrid>
</template>
```

### Feature Cards

```vue
<template>
    <CardGrid :cols="3" gap="md">
        <Card hoverable v-for="feature in features" :key="feature.id">
            <div class="text-center">
                <component :is="feature.icon" class="h-12 w-12 mx-auto text-primary-500" />
                <h3 class="mt-4 font-semibold">{{ feature.title }}</h3>
                <p class="mt-2 text-gray-600">{{ feature.description }}</p>
            </div>
        </Card>
    </CardGrid>
</template>
```

## Styling

The CardGrid component uses CSS Grid with the following classes:

- Base: `grid`
- Gap classes based on `gap` prop
- Responsive column classes based on `cols` prop

## Tips

- Use `CardGrid` with `Card` components for consistent spacing
- The grid automatically handles responsive behavior
- For more complex layouts, consider using Tailwind's grid utilities directly
- Combine with other components like `StatCard` for dashboard layouts

## Playground

Try the CardGrid component:

<LiveDemo>
  <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px;">
      <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #111827;">Card 1</h3>
      <p style="margin: 0; font-size: 14px; color: #6b7280;">Grid item content</p>
    </div>
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px;">
      <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #111827;">Card 2</h3>
      <p style="margin: 0; font-size: 14px; color: #6b7280;">Grid item content</p>
    </div>
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px;">
      <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #111827;">Card 3</h3>
      <p style="margin: 0; font-size: 14px; color: #6b7280;">Grid item content</p>
    </div>
  </div>

  <template #code>

```vue
<template>
  <CardGrid :cols="3" gap="md">
    <Card title="Card 1">Grid item content</Card>
    <Card title="Card 2">Grid item content</Card>
    <Card title="Card 3">Grid item content</Card>
  </CardGrid>
</template>
```

  </template>
</LiveDemo>
