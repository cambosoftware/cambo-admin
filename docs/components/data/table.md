# Table

A basic table component for displaying tabular data with support for striped rows, hover effects, borders, and loading states.

## Import

```js
import { Table } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `striped` | `Boolean` | `false` | Apply alternating row background colors |
| `hoverable` | `Boolean` | `true` | Apply hover effect on rows |
| `bordered` | `Boolean` | `false` | Add border around the table |
| `compact` | `Boolean` | `false` | Use compact cell padding |
| `stickyHeader` | `Boolean` | `false` | Make header sticky on scroll |
| `loading` | `Boolean` | `false` | Show loading overlay with spinner |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Table content (thead, tbody, etc.) |

## Basic Example

```vue
<template>
  <Table>
    <thead>
      <tr>
        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Role</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users" :key="user.id">
        <td class="px-4 py-3 text-sm text-gray-900">{{ user.name }}</td>
        <td class="px-4 py-3 text-sm text-gray-500">{{ user.email }}</td>
        <td class="px-4 py-3 text-sm text-gray-500">{{ user.role }}</td>
      </tr>
    </tbody>
  </Table>
</template>

<script setup>
import { Table } from '@cambosoftware/cambo-admin'

const users = [
  { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Admin' },
  { id: 2, name: 'Jane Smith', email: 'jane@example.com', role: 'User' },
]
</script>
```

## Striped Table

```vue
<template>
  <Table striped>
    <thead>
      <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Stock</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
      <tr v-for="product in products" :key="product.id">
        <td>{{ product.name }}</td>
        <td>{{ product.price }}</td>
        <td>{{ product.stock }}</td>
      </tr>
    </tbody>
  </Table>
</template>
```

## Bordered Table

```vue
<template>
  <Table bordered hoverable>
    <thead>
      <tr>
        <th>Column 1</th>
        <th>Column 2</th>
        <th>Column 3</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Data 1</td>
        <td>Data 2</td>
        <td>Data 3</td>
      </tr>
    </tbody>
  </Table>
</template>
```

## Loading State

```vue
<template>
  <Table :loading="isLoading">
    <thead>
      <tr>
        <th>Name</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="item in items" :key="item.id">
        <td>{{ item.name }}</td>
        <td>{{ item.status }}</td>
      </tr>
    </tbody>
  </Table>
</template>

<script setup>
import { ref } from 'vue'

const isLoading = ref(true)

// Simulating data fetch
setTimeout(() => {
  isLoading.value = false
}, 2000)
</script>
```

## With Sub-Components

For more complex tables, use the Table sub-components:

```vue
<template>
  <Table striped hoverable>
    <TableHead sticky>
      <tr>
        <TableCell header>Name</TableCell>
        <TableCell header align="center">Status</TableCell>
        <TableCell header align="right">Actions</TableCell>
      </tr>
    </TableHead>
    <TableBody striped hoverable>
      <TableRow
        v-for="user in users"
        :key="user.id"
        :selected="selectedId === user.id"
        clickable
        @click="selectUser(user.id)"
      >
        <TableCell>{{ user.name }}</TableCell>
        <TableCell align="center">
          <Badge :variant="user.active ? 'success' : 'danger'">
            {{ user.active ? 'Active' : 'Inactive' }}
          </Badge>
        </TableCell>
        <TableCell align="right">
          <Button size="sm" variant="ghost">Edit</Button>
        </TableCell>
      </TableRow>
    </TableBody>
  </Table>
</template>
```

## Compact Table

```vue
<template>
  <Table compact bordered>
    <thead>
      <tr>
        <th class="px-2 py-1">ID</th>
        <th class="px-2 py-1">Name</th>
        <th class="px-2 py-1">Value</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="item in items" :key="item.id">
        <td class="px-2 py-1">{{ item.id }}</td>
        <td class="px-2 py-1">{{ item.name }}</td>
        <td class="px-2 py-1">{{ item.value }}</td>
      </tr>
    </tbody>
  </Table>
</template>
```

## Responsive Table

The Table component automatically handles horizontal overflow for responsive layouts:

```vue
<template>
  <Table>
    <thead>
      <tr>
        <th>Col 1</th>
        <th>Col 2</th>
        <th>Col 3</th>
        <th>Col 4</th>
        <th>Col 5</th>
        <th>Col 6</th>
      </tr>
    </thead>
    <tbody>
      <!-- Content will scroll horizontally on small screens -->
    </tbody>
  </Table>
</template>
```

## Playground

Try the table component with different props:

<LiveDemo>
  <DemoTable />

  <template #code>

```vue
<Table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="user in users" :key="user.id">
      <td>{{ user.name }}</td>
      <td>{{ user.email }}</td>
      <td>{{ user.role }}</td>
    </tr>
  </tbody>
</Table>
```

  </template>
</LiveDemo>

### Striped Table

<LiveDemo>
  <DemoTable striped />

  <template #code>

```vue
<Table striped>
  <thead>...</thead>
  <tbody>...</tbody>
</Table>
```

  </template>
</LiveDemo>

### Hoverable Table

<LiveDemo>
  <DemoTable hoverable />

  <template #code>

```vue
<Table hoverable>
  <thead>...</thead>
  <tbody>...</tbody>
</Table>
```

  </template>
</LiveDemo>
