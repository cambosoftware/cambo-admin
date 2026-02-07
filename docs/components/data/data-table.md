# DataTable

A comprehensive data table component with built-in search, filtering, sorting, pagination, row selection, bulk actions, and export capabilities. Integrates seamlessly with Laravel QueryBuilder.

## Import

```js
import { DataTable } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `resource` | `Object` | **required** | Data from QueryBuilder: `{ data: [], meta: { columns, filters, search, sort, pagination, actions, bulk_actions } }` |
| `columns` | `Array` | `null` | Manual columns definition (overrides meta.columns). Each column: `{ key, label, sortable?, align?, width?, component?, componentProps? }` |
| `searchable` | `Boolean` | `true` | Enable search functionality |
| `filterable` | `Boolean` | `true` | Enable filter functionality |
| `sortable` | `Boolean` | `true` | Enable column sorting |
| `selectable` | `Boolean` | `true` | Enable row selection with checkboxes |
| `exportable` | `Boolean` | `false` | Enable export dropdown |
| `striped` | `Boolean` | `false` | Apply striped row styling |
| `hoverable` | `Boolean` | `true` | Apply hover effect on rows |
| `bordered` | `Boolean` | `false` | Add borders to the table |
| `compact` | `Boolean` | `false` | Use compact cell padding |
| `stickyHeader` | `Boolean` | `false` | Make header sticky on scroll |
| `loading` | `Boolean` | `false` | Show loading overlay |
| `emptyTitle` | `String` | `'No data'` | Title for empty state |
| `emptyDescription` | `String` | `'No items match your criteria.'` | Description for empty state |
| `rowKey` | `String` | `'id'` | Unique key property for rows |
| `rowClass` | `Function` | `null` | Function to add custom classes to rows: `(row, index) => string` |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `search` | `string` | Emitted when search value changes |
| `filter` | `object` | Emitted when filters change |
| `sort` | `{ column, direction }` | Emitted when sort changes |
| `page-change` | `number` | Emitted when page changes |
| `per-page-change` | `number` | Emitted when items per page changes |
| `select` | `array` | Emitted when row selection changes |
| `select-all` | `array` | Emitted when all rows are selected |
| `row-click` | `{ row, index }` | Emitted when a row is clicked |
| `row-action` | `object` | Emitted when a row action is triggered |
| `bulk-action` | `{ action, rows }` | Emitted when a bulk action is triggered |
| `export` | `{ format, selection?, rows? }` | Emitted when export is requested |

## Slots

| Slot | Props | Description |
|------|-------|-------------|
| `toolbar-actions` | - | Additional actions in the toolbar |
| `cell-{columnKey}` | `{ value, row, column }` | Custom cell content for a specific column |

## Basic Example

```vue
<template>
  <DataTable
    :resource="users"
    :columns="columns"
    @row-click="handleRowClick"
  />
</template>

<script setup>
import { DataTable } from '@cambosoftware/cambo-admin'

const props = defineProps({
  users: Object
})

const columns = [
  { key: 'id', label: 'ID', sortable: true, width: '80px' },
  { key: 'name', label: 'Name', sortable: true },
  { key: 'email', label: 'Email', sortable: true },
  { key: 'created_at', label: 'Created', sortable: true }
]

const handleRowClick = ({ row, index }) => {
  console.log('Clicked row:', row)
}
</script>
```

## With Custom Cell Rendering

```vue
<template>
  <DataTable :resource="users" :columns="columns">
    <template #cell-status="{ value, row }">
      <Badge :variant="value === 'active' ? 'success' : 'danger'">
        {{ value }}
      </Badge>
    </template>

    <template #cell-avatar="{ value }">
      <img :src="value" class="w-8 h-8 rounded-full" />
    </template>
  </DataTable>
</template>
```

## With Component Columns

```vue
<script setup>
import StatusCell from '@/Components/Data/Formatters/StatusCell.vue'
import DateCell from '@/Components/Data/Formatters/DateCell.vue'

const columns = [
  { key: 'name', label: 'Name' },
  {
    key: 'status',
    label: 'Status',
    component: StatusCell,
    componentProps: {
      colors: { active: 'green', inactive: 'red' }
    }
  },
  {
    key: 'created_at',
    label: 'Created',
    component: DateCell,
    componentProps: { format: 'MMM D, YYYY' }
  }
]
</script>
```

## With Bulk Actions

```vue
<template>
  <DataTable
    :resource="users"
    selectable
    @bulk-action="handleBulkAction"
  />
</template>

<script setup>
// Resource meta should include bulk_actions:
// { bulk_actions: [{ key: 'delete', label: 'Delete', variant: 'danger' }] }

const handleBulkAction = ({ action, rows }) => {
  if (action.key === 'delete') {
    // Delete selected rows
    console.log('Deleting:', rows.map(r => r.id))
  }
}
</script>
```

## With Export

```vue
<template>
  <DataTable
    :resource="users"
    exportable
    @export="handleExport"
  />
</template>

<script setup>
const handleExport = ({ format, rows }) => {
  // Handle export - rows is null for all, or array for selection
  window.location.href = `/api/users/export?format=${format}`
}
</script>
```

## Full Featured Example

```vue
<template>
  <DataTable
    :resource="users"
    :columns="columns"
    searchable
    filterable
    sortable
    selectable
    exportable
    hoverable
    striped
    :row-class="getRowClass"
    @search="onSearch"
    @filter="onFilter"
    @sort="onSort"
    @page-change="onPageChange"
    @row-click="onRowClick"
    @bulk-action="onBulkAction"
    @export="onExport"
  >
    <template #toolbar-actions>
      <Button variant="primary" @click="createUser">
        Add User
      </Button>
    </template>

    <template #cell-name="{ value, row }">
      <div class="flex items-center gap-3">
        <Avatar :src="row.avatar" :name="value" size="sm" />
        <span>{{ value }}</span>
      </div>
    </template>
  </DataTable>
</template>
```

## Laravel Controller Example

```php
use App\Http\Resources\UserResource;
use Spatie\QueryBuilder\QueryBuilder;

public function index(Request $request)
{
    $users = QueryBuilder::for(User::class)
        ->allowedFilters(['name', 'email', 'status'])
        ->allowedSorts(['name', 'email', 'created_at'])
        ->paginate($request->get('per_page', 15));

    return inertia('Users/Index', [
        'users' => [
            'data' => UserResource::collection($users),
            'meta' => [
                'columns' => [
                    ['key' => 'id', 'label' => 'ID', 'sortable' => true],
                    ['key' => 'name', 'label' => 'Name', 'sortable' => true],
                    ['key' => 'email', 'label' => 'Email', 'sortable' => true],
                ],
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ],
                'actions' => [
                    ['key' => 'edit', 'label' => 'Edit'],
                    ['key' => 'delete', 'label' => 'Delete', 'variant' => 'danger'],
                ],
                'bulk_actions' => [
                    ['key' => 'delete', 'label' => 'Delete Selected', 'variant' => 'danger'],
                ],
            ],
        ],
    ]);
}
```

## Playground

Try the DataTable component:

<LiveDemo>
  <div style="background: #f8fafc; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0;">
    <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
      <input type="text" placeholder="Search..." style="padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 14px;" />
      <button style="background: #6366f1; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; border: none;">Add User</button>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr style="background: #f1f5f9;">
          <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 14px; color: #475569;">ID</th>
          <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 14px; color: #475569;">Name</th>
          <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 14px; color: #475569;">Email</th>
          <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 14px; color: #475569;">Status</th>
        </tr>
      </thead>
      <tbody>
        <tr style="border-bottom: 1px solid #e2e8f0;">
          <td style="padding: 12px 16px; font-size: 14px;">1</td>
          <td style="padding: 12px 16px; font-size: 14px;">John Doe</td>
          <td style="padding: 12px 16px; font-size: 14px; color: #64748b;">john@example.com</td>
          <td style="padding: 12px 16px;"><span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 9999px; font-size: 12px;">Active</span></td>
        </tr>
        <tr style="border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
          <td style="padding: 12px 16px; font-size: 14px;">2</td>
          <td style="padding: 12px 16px; font-size: 14px;">Jane Smith</td>
          <td style="padding: 12px 16px; font-size: 14px; color: #64748b;">jane@example.com</td>
          <td style="padding: 12px 16px;"><span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 9999px; font-size: 12px;">Active</span></td>
        </tr>
        <tr>
          <td style="padding: 12px 16px; font-size: 14px;">3</td>
          <td style="padding: 12px 16px; font-size: 14px;">Bob Wilson</td>
          <td style="padding: 12px 16px; font-size: 14px; color: #64748b;">bob@example.com</td>
          <td style="padding: 12px 16px;"><span style="background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 9999px; font-size: 12px;">Pending</span></td>
        </tr>
      </tbody>
    </table>
    <div style="padding: 12px 16px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 14px; color: #64748b;">
      <span>Showing 1 to 3 of 3 results</span>
      <div style="display: flex; gap: 4px;">
        <button style="padding: 4px 8px; border: 1px solid #e2e8f0; border-radius: 4px; background: white;">Previous</button>
        <button style="padding: 4px 8px; border: 1px solid #6366f1; border-radius: 4px; background: #6366f1; color: white;">1</button>
        <button style="padding: 4px 8px; border: 1px solid #e2e8f0; border-radius: 4px; background: white;">Next</button>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <DataTable
    :resource="users"
    :columns="columns"
    searchable
    hoverable
    striped
    @row-click="handleRowClick"
  >
    <template #cell-status="{ value }">
      <Badge :variant="value === 'active' ? 'success' : 'warning'">
        {{ value }}
      </Badge>
    </template>
  </DataTable>
</template>

<script setup>
import { DataTable } from '@cambosoftware/cambo-admin'

const columns = [
  { key: 'id', label: 'ID', sortable: true },
  { key: 'name', label: 'Name', sortable: true },
  { key: 'email', label: 'Email', sortable: true },
  { key: 'status', label: 'Status' }
]
</script>
```

  </template>
</LiveDemo>
