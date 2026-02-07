# EmptyState

A component for displaying placeholder content when no data is available, with customizable icon, title, description, and action.

## Import

```js
import { EmptyState } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `String` | `'No results'` | Main title text |
| `description` | `String` | `null` | Optional description text |
| `icon` | `Object\|Function` | `null` | Custom icon component |

## Slots

| Slot | Description |
|------|-------------|
| `action` | Action buttons or links |

## Basic Example

```vue
<template>
  <EmptyState
    title="No users found"
    description="Try adjusting your search or filters."
  />
</template>

<script setup>
import { EmptyState } from '@cambosoftware/cambo-admin'
</script>
```

## With Action Button

```vue
<template>
  <EmptyState
    title="No projects yet"
    description="Get started by creating your first project."
  >
    <template #action>
      <Button @click="createProject">
        <PlusIcon class="h-4 w-4 mr-2" />
        Create Project
      </Button>
    </template>
  </EmptyState>
</template>
```

## With Custom Icon

```vue
<template>
  <EmptyState
    :icon="UserGroupIcon"
    title="No team members"
    description="Invite people to collaborate on this project."
  >
    <template #action>
      <Button variant="primary">Invite Members</Button>
    </template>
  </EmptyState>
</template>

<script setup>
import { UserGroupIcon } from '@heroicons/vue/24/outline'
</script>
```

## Different Icons for Context

```vue
<template>
  <div class="grid grid-cols-3 gap-6">
    <Card>
      <EmptyState
        :icon="InboxIcon"
        title="No messages"
        description="Your inbox is empty."
      />
    </Card>

    <Card>
      <EmptyState
        :icon="DocumentIcon"
        title="No documents"
        description="Upload your first document."
      />
    </Card>

    <Card>
      <EmptyState
        :icon="PhotoIcon"
        title="No images"
        description="Add some images to your gallery."
      />
    </Card>
  </div>
</template>

<script setup>
import {
  InboxIcon,
  DocumentIcon,
  PhotoIcon
} from '@heroicons/vue/24/outline'
</script>
```

## Search Results Empty

```vue
<template>
  <div>
    <Input
      v-model="searchQuery"
      placeholder="Search..."
      class="mb-4"
    />

    <div v-if="filteredItems.length > 0">
      <List bordered>
        <ListItem v-for="item in filteredItems" :key="item.id">
          {{ item.name }}
        </ListItem>
      </List>
    </div>

    <EmptyState
      v-else
      :icon="MagnifyingGlassIcon"
      title="No results found"
      :description="`No items match '${searchQuery}'`"
    >
      <template #action>
        <Button variant="secondary" @click="searchQuery = ''">
          Clear Search
        </Button>
      </template>
    </EmptyState>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline'

const searchQuery = ref('')
const items = [
  { id: 1, name: 'Apple' },
  { id: 2, name: 'Banana' },
  { id: 3, name: 'Orange' },
]

const filteredItems = computed(() => {
  if (!searchQuery.value) return items
  return items.filter(item =>
    item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})
</script>
```

## Table Empty State

```vue
<template>
  <DataTable :resource="products" :columns="columns">
    <template #empty>
      <EmptyState
        :icon="CubeIcon"
        title="No products"
        description="Start by adding your first product to the catalog."
      >
        <template #action>
          <Button @click="addProduct">Add Product</Button>
        </template>
      </EmptyState>
    </template>
  </DataTable>
</template>
```

## Filtered Empty State

```vue
<template>
  <EmptyState
    :icon="FunnelIcon"
    title="No matching items"
    description="Try removing some filters to see more results."
  >
    <template #action>
      <Button variant="secondary" @click="clearFilters">
        Clear All Filters
      </Button>
    </template>
  </EmptyState>
</template>

<script setup>
import { FunnelIcon } from '@heroicons/vue/24/outline'
</script>
```

## Multiple Actions

```vue
<template>
  <EmptyState
    :icon="CloudArrowUpIcon"
    title="No files uploaded"
    description="Upload files by dragging and dropping or clicking the button below."
  >
    <template #action>
      <div class="flex flex-col sm:flex-row gap-2">
        <Button @click="uploadFile">
          <ArrowUpTrayIcon class="h-4 w-4 mr-2" />
          Upload File
        </Button>
        <Button variant="secondary" @click="importFromCloud">
          <CloudIcon class="h-4 w-4 mr-2" />
          Import from Cloud
        </Button>
      </div>
    </template>
  </EmptyState>
</template>
```

## In a Card

```vue
<template>
  <Card title="Recent Activity">
    <EmptyState
      title="No activity yet"
      description="Your recent actions will appear here."
    />
  </Card>
</template>
```

## Conditional Rendering

```vue
<template>
  <div>
    <div v-if="isLoading" class="py-12 text-center">
      <Spinner />
      <p class="text-gray-500 mt-2">Loading...</p>
    </div>

    <template v-else-if="items.length > 0">
      <List bordered>
        <ListItem v-for="item in items" :key="item.id">
          {{ item.name }}
        </ListItem>
      </List>
    </template>

    <EmptyState
      v-else
      title="Nothing here yet"
      description="Create your first item to get started."
    >
      <template #action>
        <Button @click="create">Create Item</Button>
      </template>
    </EmptyState>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const isLoading = ref(false)
const items = ref([])
</script>
```

## Compact Empty State

```vue
<template>
  <EmptyState
    title="No comments"
    class="py-6"
  />
</template>
```

## Default Icon

When no icon is provided, the component displays a default inbox/archive icon:

```vue
<template>
  <!-- Uses default icon -->
  <EmptyState
    title="No data"
    description="No data is available at this time."
  />
</template>
```

## Playground

Try the empty state component with different props:

<LiveDemo>
  <DemoEmptyState
    title="No results found"
    description="Try adjusting your search or filters."
  />

  <template #code>

```vue
<EmptyState
  title="No results found"
  description="Try adjusting your search or filters."
/>
```

  </template>
</LiveDemo>

### Empty State with Action

<LiveDemo>
  <DemoEmptyState
    title="No projects yet"
    description="Get started by creating your first project."
    showAction
    actionLabel="Create Project"
  />

  <template #code>

```vue
<EmptyState
  title="No projects yet"
  description="Get started by creating your first project."
>
  <template #action>
    <Button>Create Project</Button>
  </template>
</EmptyState>
```

  </template>
</LiveDemo>
