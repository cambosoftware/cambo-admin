# Pagination

A flexible pagination component with page numbers, navigation controls, per-page selector, and item count display.

## Import

```js
import { Pagination } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `currentPage` | `Number` | **required** | Current active page number |
| `totalPages` | `Number` | **required** | Total number of pages |
| `totalItems` | `Number` | `null` | Total number of items (for info display) |
| `perPage` | `Number` | `null` | Items per page (for info and selector) |
| `perPageOptions` | `Array` | `[10, 25, 50, 100]` | Options for per-page selector |
| `showPerPage` | `Boolean` | `true` | Show per-page selector |
| `showInfo` | `Boolean` | `true` | Show items info (e.g., "1-10 of 100") |
| `showFirstLast` | `Boolean` | `true` | Show first/last page buttons |
| `maxVisiblePages` | `Number` | `5` | Maximum number of visible page buttons |
| `size` | `String` | `'md'` | Size variant: `'sm'`, `'md'`, `'lg'` |
| `simple` | `Boolean` | `false` | Use simple mode (shows "Page X / Y" only) |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:currentPage` | `number` | Emitted when page changes |
| `update:perPage` | `number` | Emitted when per-page changes |

## Basic Example

```vue
<template>
  <Pagination
    :current-page="currentPage"
    :total-pages="10"
    @update:current-page="currentPage = $event"
  />
</template>

<script setup>
import { ref } from 'vue'
import { Pagination } from '@cambosoftware/cambo-admin'

const currentPage = ref(1)
</script>
```

## With Item Info

```vue
<template>
  <Pagination
    :current-page="currentPage"
    :total-pages="totalPages"
    :total-items="250"
    :per-page="25"
    @update:current-page="currentPage = $event"
  />
</template>

<script setup>
import { ref, computed } from 'vue'

const currentPage = ref(1)
const perPage = 25
const totalItems = 250
const totalPages = computed(() => Math.ceil(totalItems / perPage))
</script>
```

## With Per-Page Selector

```vue
<template>
  <Pagination
    :current-page="currentPage"
    :total-pages="totalPages"
    :total-items="totalItems"
    :per-page="perPage"
    :per-page-options="[10, 25, 50, 100]"
    @update:current-page="currentPage = $event"
    @update:per-page="handlePerPageChange"
  />
</template>

<script setup>
import { ref, computed } from 'vue'

const currentPage = ref(1)
const perPage = ref(25)
const totalItems = 250

const totalPages = computed(() => Math.ceil(totalItems / perPage.value))

const handlePerPageChange = (value) => {
  perPage.value = value
  currentPage.value = 1 // Reset to first page
}
</script>
```

## Simple Mode

```vue
<template>
  <Pagination
    :current-page="currentPage"
    :total-pages="10"
    simple
    @update:current-page="currentPage = $event"
  />
</template>
```

## Size Variants

```vue
<template>
  <div class="space-y-4">
    <!-- Small -->
    <Pagination
      :current-page="1"
      :total-pages="10"
      size="sm"
    />

    <!-- Medium (default) -->
    <Pagination
      :current-page="1"
      :total-pages="10"
      size="md"
    />

    <!-- Large -->
    <Pagination
      :current-page="1"
      :total-pages="10"
      size="lg"
    />
  </div>
</template>
```

## Minimal Pagination

```vue
<template>
  <Pagination
    :current-page="currentPage"
    :total-pages="10"
    :show-per-page="false"
    :show-info="false"
    :show-first-last="false"
    @update:current-page="currentPage = $event"
  />
</template>
```

## With Inertia.js

```vue
<template>
  <div>
    <DataList :items="users.data" />

    <Pagination
      :current-page="users.meta.current_page"
      :total-pages="users.meta.last_page"
      :total-items="users.meta.total"
      :per-page="users.meta.per_page"
      @update:current-page="goToPage"
      @update:per-page="changePerPage"
    />
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
  users: Object
})

const goToPage = (page) => {
  router.get(route('users.index'), { page }, {
    preserveState: true,
    preserveScroll: true
  })
}

const changePerPage = (perPage) => {
  router.get(route('users.index'), { per_page: perPage, page: 1 }, {
    preserveState: true,
    preserveScroll: true
  })
}
</script>
```

## Custom Page Numbers Display

```vue
<template>
  <!-- Show more page numbers -->
  <Pagination
    :current-page="currentPage"
    :total-pages="50"
    :max-visible-pages="7"
    @update:current-page="currentPage = $event"
  />

  <!-- Show fewer page numbers -->
  <Pagination
    :current-page="currentPage"
    :total-pages="50"
    :max-visible-pages="3"
    @update:current-page="currentPage = $event"
  />
</template>
```

## Accessibility

The Pagination component includes proper accessibility attributes:

- Navigation buttons have proper disabled states
- Current page is visually indicated
- Arrow buttons are keyboard accessible

## Playground

Try the pagination component:

<LiveDemo>
  <DemoPagination :totalPages="10" :currentPage="3" />

  <template #code>

```vue
<Pagination
  :current-page="currentPage"
  :total-pages="10"
  @update:current-page="currentPage = $event"
/>
```

  </template>
</LiveDemo>
