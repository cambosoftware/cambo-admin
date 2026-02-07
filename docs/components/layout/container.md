# Container

A simple wrapper component for constraining content width with consistent padding and optional centering.

## Import

```vue
<script setup>
import { Container } from 'cambo-admin'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `size` | `String` | `'default'` | Maximum width preset: `'sm'`, `'default'`, `'lg'`, `'xl'`, or `'full'` |
| `padding` | `Boolean` | `true` | Whether to apply horizontal padding |
| `centered` | `Boolean` | `true` | Whether to center the container horizontally |

## Slots

| Name | Description |
|------|-------------|
| `default` | Container content |

## Usage

### Basic Usage

```vue
<template>
  <Container>
    <h1>Welcome to the Dashboard</h1>
    <p>This content is constrained to a comfortable reading width.</p>
  </Container>
</template>
```

### Size Variants

```vue
<!-- Small container (max-w-3xl / 768px) -->
<Container size="sm">
  <p>Narrow content, ideal for forms or reading.</p>
</Container>

<!-- Default container (max-w-5xl / 1024px) -->
<Container size="default">
  <p>Standard content width.</p>
</Container>

<!-- Large container (max-w-6xl / 1152px) -->
<Container size="lg">
  <p>Wider content area.</p>
</Container>

<!-- Extra large container (max-w-7xl / 1280px) -->
<Container size="xl">
  <p>Maximum standard width.</p>
</Container>

<!-- Full width container (max-w-full) -->
<Container size="full">
  <p>Full width content with padding.</p>
</Container>
```

### Without Padding

```vue
<template>
  <Container :padding="false">
    <div class="custom-content">
      <!-- Content with custom padding/margin -->
    </div>
  </Container>
</template>
```

### Without Centering

```vue
<template>
  <Container :centered="false">
    <div>
      <!-- Left-aligned content -->
    </div>
  </Container>
</template>
```

### Form Layout

```vue
<template>
  <Container size="sm">
    <form @submit.prevent="handleSubmit">
      <div class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">
            Name
          </label>
          <input
            type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">
            Email
          </label>
          <input
            type="email"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          />
        </div>

        <button type="submit" class="btn btn-primary">
          Submit
        </button>
      </div>
    </form>
  </Container>
</template>
```

### Dashboard Layout

```vue
<template>
  <Container size="xl">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <StatCard title="Total Users" value="1,234" />
      <StatCard title="Revenue" value="$45,678" />
      <StatCard title="Orders" value="567" />
      <StatCard title="Conversion" value="12.5%" />
    </div>
  </Container>
</template>
```

### Full Width Table

```vue
<template>
  <Container size="full">
    <DataTable :data="users" :columns="columns" />
  </Container>
</template>
```

## Configuration Options

### Size Values

| Size | Tailwind Class | Approximate Width |
|------|----------------|-------------------|
| `sm` | `max-w-3xl` | 768px |
| `default` | `max-w-5xl` | 1024px |
| `lg` | `max-w-6xl` | 1152px |
| `xl` | `max-w-7xl` | 1280px |
| `full` | `max-w-full` | 100% |

### Padding Values

When `padding` is `true`, the following responsive padding is applied:

| Screen Size | Padding Class |
|-------------|---------------|
| Mobile | `px-4` (16px) |
| Tablet (>= 640px) | `sm:px-6` (24px) |
| Desktop (>= 1024px) | `lg:px-8` (32px) |

## Nesting Containers

Containers can be nested for different content sections:

```vue
<template>
  <Container size="xl">
    <PageHeader title="Settings" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2">
        <Container size="lg" :padding="false">
          <!-- Main content -->
        </Container>
      </div>

      <div>
        <!-- Sidebar content -->
      </div>
    </div>
  </Container>
</template>
```

## Comparison with AdminLayout Fluid Mode

| Feature | Container | AdminLayout (fluid) |
|---------|-----------|---------------------|
| Width control | Via `size` prop | `fluid` prop (boolean) |
| Granularity | Per-section | Page-wide |
| Use case | Sections with different widths | Consistent page layout |

### When to Use Each

- **Use Container**: When you need different max-widths for different sections within the same page
- **Use AdminLayout fluid**: When the entire page should be full-width (e.g., dashboards, data tables)

## Example: Mixed Width Layout

```vue
<template>
  <AdminLayout title="Dashboard" :fluid="true">
    <!-- Full-width stats -->
    <Container size="full" class="mb-8">
      <div class="grid grid-cols-4 gap-4">
        <StatCard v-for="stat in stats" :key="stat.id" v-bind="stat" />
      </div>
    </Container>

    <!-- Constrained content area -->
    <Container size="lg">
      <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
      <ActivityFeed :items="activities" />
    </Container>
  </AdminLayout>
</template>
```

## Playground

Try the Container component:

<LiveDemo>
  <div style="background: #f3f4f6; padding: 16px; border-radius: 8px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <h2 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 600; color: #111827;">Container: size="default"</h2>
      <p style="margin: 0; color: #6b7280; font-size: 14px;">This content is centered and constrained to a comfortable reading width with responsive padding.</p>
    </div>
  </div>

  <template #code>

```vue
<template>
  <Container size="default">
    <h2 class="text-lg font-semibold mb-2">Container: size="default"</h2>
    <p class="text-gray-600">
      This content is centered and constrained to a comfortable
      reading width with responsive padding.
    </p>
  </Container>
</template>
```

  </template>
</LiveDemo>
