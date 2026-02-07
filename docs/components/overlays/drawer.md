# Drawer

A panel that slides in from the edge of the screen, useful for forms, filters, or detailed views.

## Import

```vue
<script setup>
import Drawer from '@/Components/Overlays/Drawer.vue'
</script>
```

## Basic Usage

```vue
<template>
  <Button @click="showDrawer = true">Open Drawer</Button>

  <Drawer v-model="showDrawer" title="User Details">
    <p>Drawer content goes here.</p>
  </Drawer>
</template>

<script setup>
import { ref } from 'vue'
const showDrawer = ref(false)
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `boolean` | `false` | Controls visibility (v-model) |
| `title` | `string` | `''` | Drawer title |
| `position` | `string` | `'right'` | Position: `left`, `right`, `top`, `bottom` |
| `size` | `string` | `'md'` | Size: `sm`, `md`, `lg`, `xl`, `full` |
| `closable` | `boolean` | `true` | Show close button |
| `closeOnBackdrop` | `boolean` | `true` | Close when clicking backdrop |
| `closeOnEscape` | `boolean` | `true` | Close on Escape key |

## Positions

```vue
<Drawer position="right" />  <!-- Slides from right (default) -->
<Drawer position="left" />   <!-- Slides from left -->
<Drawer position="top" />    <!-- Slides from top -->
<Drawer position="bottom" /> <!-- Slides from bottom -->
```

## Sizes

| Size | Width (left/right) | Height (top/bottom) |
|------|-------------------|---------------------|
| `sm` | 320px | 200px |
| `md` | 400px | 300px |
| `lg` | 500px | 400px |
| `xl` | 600px | 500px |
| `full` | 100% | 100% |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Drawer body content |
| `header` | Custom header content |
| `footer` | Footer with actions |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `boolean` | Visibility changed |
| `close` | - | Drawer closed |
| `open` | - | Drawer opened |

## Examples

### Filter Drawer

```vue
<Drawer v-model="showFilters" title="Filters" position="left" size="sm">
  <div class="space-y-4">
    <FormGroup label="Status">
      <Select v-model="filters.status" :options="statusOptions" />
    </FormGroup>

    <FormGroup label="Date Range">
      <DateRangePicker v-model="filters.dateRange" />
    </FormGroup>

    <FormGroup label="Category">
      <CheckboxGroup v-model="filters.categories" :options="categoryOptions" />
    </FormGroup>
  </div>

  <template #footer>
    <Button variant="secondary" @click="resetFilters">Reset</Button>
    <Button variant="primary" @click="applyFilters">Apply Filters</Button>
  </template>
</Drawer>
```

### Edit Form Drawer

```vue
<Drawer v-model="showEdit" title="Edit Product" size="lg">
  <Form @submit="saveProduct">
    <FormGroup label="Name" :error="form.errors.name">
      <Input v-model="form.name" />
    </FormGroup>

    <FormGroup label="Description">
      <Textarea v-model="form.description" rows="4" />
    </FormGroup>

    <FormGroup label="Price">
      <CurrencyInput v-model="form.price" />
    </FormGroup>

    <FormGroup label="Images">
      <ImagePicker v-model="form.images" multiple />
    </FormGroup>
  </Form>

  <template #footer>
    <Button variant="secondary" @click="showEdit = false">Cancel</Button>
    <Button variant="primary" :loading="form.processing" @click="saveProduct">
      Save Changes
    </Button>
  </template>
</Drawer>
```

### Mobile Navigation Drawer

```vue
<Drawer v-model="showNav" position="left" size="sm">
  <template #header>
    <img src="/logo.svg" alt="Logo" class="h-8" />
  </template>

  <nav class="space-y-1">
    <NavLink href="/dashboard" :active="route().current('dashboard')">
      Dashboard
    </NavLink>
    <NavLink href="/products" :active="route().current('products.*')">
      Products
    </NavLink>
    <NavLink href="/orders" :active="route().current('orders.*')">
      Orders
    </NavLink>
    <NavLink href="/customers" :active="route().current('customers.*')">
      Customers
    </NavLink>
  </nav>
</Drawer>
```

### Bottom Sheet (Mobile)

```vue
<Drawer v-model="showActions" position="bottom" size="sm">
  <div class="space-y-2">
    <Button variant="ghost" class="w-full justify-start" @click="edit">
      <PencilIcon class="w-5 h-5 mr-3" /> Edit
    </Button>
    <Button variant="ghost" class="w-full justify-start" @click="duplicate">
      <DocumentDuplicateIcon class="w-5 h-5 mr-3" /> Duplicate
    </Button>
    <Button variant="ghost" class="w-full justify-start text-red-600" @click="remove">
      <TrashIcon class="w-5 h-5 mr-3" /> Delete
    </Button>
  </div>
</Drawer>
```

## Accessibility

- Focus is trapped within the drawer
- Escape key closes the drawer
- Proper ARIA attributes applied
- Body scroll is locked when open

## Playground

Try the drawer component:

<LiveDemo>
  <DemoDrawer />

  <template #code>

```vue
<script setup>
import { ref } from 'vue'
const showDrawer = ref(false)
</script>

<template>
  <Button @click="showDrawer = true">Open Drawer</Button>

  <Drawer v-model="showDrawer" title="Drawer Title">
    <p>Drawer content goes here.</p>
  </Drawer>
</template>
```

  </template>
</LiveDemo>
