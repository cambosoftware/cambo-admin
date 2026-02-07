# Data Display

Components for displaying and organizing data.

## Components

| Component | Description |
|-----------|-------------|
| [DataTable](./data-table.md) | Full-featured data table |
| [Table](./table.md) | Basic table components |
| [Pagination](./pagination.md) | Page navigation |
| [List](./list.md) | Vertical list display |
| [DescriptionList](./description-list.md) | Key-value pairs display |
| [Tree](./tree.md) | Hierarchical tree view |
| [Timeline](./timeline.md) | Chronological events |
| [Calendar](./calendar.md) | Calendar display |
| [KanbanBoard](./kanban-board.md) | Kanban-style board |

## Usage

```vue
<template>
  <DataTable
    :data="users"
    :columns="columns"
    :searchable="['name', 'email']"
    :sortable="['name', 'created_at']"
  >
    <template #actions="{ row }">
      <Button size="sm" @click="edit(row)">Edit</Button>
    </template>
  </DataTable>
</template>

<script setup>
const columns = [
  { key: 'name', label: 'Name' },
  { key: 'email', label: 'Email' },
  { key: 'created_at', label: 'Created', format: 'date' },
  { key: 'actions', label: '' },
]
</script>
```
