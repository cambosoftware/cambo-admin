# KanbanBoard

A drag-and-drop Kanban board component for project management and task organization with columns, cards, labels, and priorities.

## Import

```js
import { KanbanBoard } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `columns` | `Array` | **required** | Array of columns with items (see structure below) |
| `allowAdd` | `Boolean` | `true` | Allow adding new cards to columns |
| `allowAddColumn` | `Boolean` | `false` | Allow adding new columns |
| `cardClickable` | `Boolean` | `true` | Enable card click events |

### Column Structure

```js
{
  id: string | number,
  title: string,
  color?: string,           // Optional column indicator color
  items: KanbanItem[]
}
```

### KanbanItem Structure

```js
{
  id: string | number,
  title: string,
  description?: string,
  labels?: string[] | { name: string, color: string }[],
  assignees?: { name: string, avatar?: string }[],
  dueDate?: string,
  priority?: 'low' | 'medium' | 'high' | 'urgent'
}
```

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `card-click` | `{ card, column }` | Emitted when a card is clicked |
| `card-move` | `{ card, fromColumn, toColumn, toIndex }` | Emitted when a card is dragged to a new position |
| `card-add` | `column` | Emitted when add card button is clicked |
| `column-add` | - | Emitted when add column button is clicked |
| `card-menu` | `{ card, column, event }` | Emitted when card menu is clicked |
| `column-menu` | `{ column, event }` | Emitted when column menu is clicked |

## Basic Example

```vue
<template>
  <KanbanBoard
    :columns="columns"
    @card-click="handleCardClick"
    @card-move="handleCardMove"
  />
</template>

<script setup>
import { ref } from 'vue'
import { KanbanBoard } from '@cambosoftware/cambo-admin'

const columns = ref([
  {
    id: 'todo',
    title: 'To Do',
    color: '#6366f1',
    items: [
      { id: 1, title: 'Design homepage', priority: 'high' },
      { id: 2, title: 'Write documentation' },
    ]
  },
  {
    id: 'in-progress',
    title: 'In Progress',
    color: '#f59e0b',
    items: [
      { id: 3, title: 'Implement login', priority: 'medium' },
    ]
  },
  {
    id: 'done',
    title: 'Done',
    color: '#10b981',
    items: [
      { id: 4, title: 'Setup project' },
    ]
  }
])

const handleCardClick = ({ card, column }) => {
  console.log('Clicked card:', card, 'in column:', column)
}

const handleCardMove = ({ card, fromColumn, toColumn, toIndex }) => {
  // Update your data structure
  console.log(`Moved ${card.title} from ${fromColumn} to ${toColumn}`)
}
</script>
```

## With Full Card Features

```vue
<template>
  <KanbanBoard
    :columns="columns"
    @card-move="handleMove"
  />
</template>

<script setup>
const columns = ref([
  {
    id: 'backlog',
    title: 'Backlog',
    items: [
      {
        id: 1,
        title: 'Implement dark mode',
        description: 'Add dark mode support to the entire application',
        labels: ['feature', 'ui'],
        assignees: [
          { name: 'John Doe', avatar: '/avatars/john.jpg' },
          { name: 'Jane Smith', avatar: '/avatars/jane.jpg' }
        ],
        dueDate: 'Feb 15',
        priority: 'medium'
      },
      {
        id: 2,
        title: 'Fix login bug',
        description: 'Users cannot login with special characters in password',
        labels: [{ name: 'bug', color: '#ef4444' }],
        assignees: [{ name: 'Bob Wilson' }],
        priority: 'high'
      }
    ]
  },
  {
    id: 'in-progress',
    title: 'In Progress',
    items: []
  },
  {
    id: 'review',
    title: 'Review',
    items: []
  },
  {
    id: 'done',
    title: 'Done',
    items: []
  }
])
</script>
```

## Handle Card Movement

```vue
<template>
  <KanbanBoard
    :columns="columns"
    @card-move="moveCard"
  />
</template>

<script setup>
import { ref } from 'vue'

const columns = ref([...])

const moveCard = ({ card, fromColumn, toColumn, toIndex }) => {
  // Remove from source column
  const sourceCol = columns.value.find(c => c.id === fromColumn)
  const cardIndex = sourceCol.items.findIndex(i => i.id === card.id)
  sourceCol.items.splice(cardIndex, 1)

  // Add to target column
  const targetCol = columns.value.find(c => c.id === toColumn)
  if (toIndex !== null) {
    targetCol.items.splice(toIndex, 0, card)
  } else {
    targetCol.items.push(card)
  }

  // Optionally sync with server
  updateCardPosition(card.id, toColumn, toIndex)
}

const updateCardPosition = async (cardId, columnId, position) => {
  await fetch('/api/cards/move', {
    method: 'POST',
    body: JSON.stringify({ cardId, columnId, position })
  })
}
</script>
```

## With Add Card

```vue
<template>
  <KanbanBoard
    :columns="columns"
    allow-add
    @card-add="handleAddCard"
  />

  <Modal v-model:show="showAddModal" title="Add Card">
    <Form @submit="createCard">
      <FormGroup label="Title">
        <Input v-model="newCard.title" required />
      </FormGroup>
      <FormGroup label="Description">
        <Textarea v-model="newCard.description" />
      </FormGroup>
      <FormGroup label="Priority">
        <Select
          v-model="newCard.priority"
          :options="priorityOptions"
        />
      </FormGroup>
    </Form>
    <template #footer>
      <Button variant="secondary" @click="showAddModal = false">Cancel</Button>
      <Button @click="createCard">Create</Button>
    </template>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'

const showAddModal = ref(false)
const targetColumn = ref(null)
const newCard = ref({ title: '', description: '', priority: 'medium' })

const priorityOptions = [
  { value: 'low', label: 'Low' },
  { value: 'medium', label: 'Medium' },
  { value: 'high', label: 'High' },
  { value: 'urgent', label: 'Urgent' }
]

const handleAddCard = (column) => {
  targetColumn.value = column
  newCard.value = { title: '', description: '', priority: 'medium' }
  showAddModal.value = true
}

const createCard = () => {
  const card = {
    id: Date.now(),
    ...newCard.value
  }
  targetColumn.value.items.push(card)
  showAddModal.value = false
}
</script>
```

## With Column Management

```vue
<template>
  <KanbanBoard
    :columns="columns"
    allow-add
    allow-add-column
    @column-add="handleAddColumn"
    @column-menu="handleColumnMenu"
  />
</template>

<script setup>
const handleAddColumn = () => {
  const name = prompt('Enter column name:')
  if (name) {
    columns.value.push({
      id: Date.now(),
      title: name,
      items: []
    })
  }
}

const handleColumnMenu = ({ column, event }) => {
  // Show context menu or dropdown
  console.log('Column menu for:', column.title)
}
</script>
```

## Card Detail Modal

```vue
<template>
  <KanbanBoard
    :columns="columns"
    @card-click="openCardDetail"
  />

  <Modal
    v-model:show="showDetail"
    :title="selectedCard?.title"
    size="lg"
  >
    <div class="space-y-4">
      <div>
        <h4 class="text-sm font-medium text-gray-500">Description</h4>
        <p class="mt-1">{{ selectedCard?.description || 'No description' }}</p>
      </div>

      <div v-if="selectedCard?.labels?.length">
        <h4 class="text-sm font-medium text-gray-500">Labels</h4>
        <div class="mt-1 flex gap-1">
          <Badge v-for="label in selectedCard.labels" :key="label">
            {{ label }}
          </Badge>
        </div>
      </div>

      <div v-if="selectedCard?.assignees?.length">
        <h4 class="text-sm font-medium text-gray-500">Assignees</h4>
        <div class="mt-1 flex -space-x-2">
          <Avatar
            v-for="assignee in selectedCard.assignees"
            :key="assignee.name"
            :src="assignee.avatar"
            :name="assignee.name"
            size="sm"
          />
        </div>
      </div>

      <div>
        <h4 class="text-sm font-medium text-gray-500">Priority</h4>
        <Badge :variant="getPriorityVariant(selectedCard?.priority)">
          {{ selectedCard?.priority || 'None' }}
        </Badge>
      </div>
    </div>

    <template #footer>
      <Button variant="danger" @click="deleteCard">Delete</Button>
      <Button @click="editCard">Edit</Button>
    </template>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'

const showDetail = ref(false)
const selectedCard = ref(null)

const openCardDetail = ({ card, column }) => {
  selectedCard.value = { ...card, columnId: column.id }
  showDetail.value = true
}

const getPriorityVariant = (priority) => {
  const variants = {
    low: 'secondary',
    medium: 'warning',
    high: 'danger',
    urgent: 'danger'
  }
  return variants[priority] || 'secondary'
}
</script>
```

## Priority Colors

The component includes built-in priority styling:

| Priority | Background | Text |
|----------|------------|------|
| `low` | Gray | Gray |
| `medium` | Amber | Amber |
| `high` | Red | Red |
| `urgent` | Purple | Purple |

## Customizing Labels

Labels can be simple strings or objects with custom colors:

```js
// Simple labels (auto-colored)
labels: ['feature', 'bug', 'urgent']

// Custom colored labels
labels: [
  { name: 'Feature', color: '#3b82f6' },
  { name: 'Bug', color: '#ef4444' },
  { name: 'Enhancement', color: '#8b5cf6' }
]
```

## Playground

Try the KanbanBoard component:

<LiveDemo>
  <div style="display: flex; gap: 16px; overflow-x: auto; padding: 8px;">
    <div style="min-width: 280px; background: #f8fafc; border-radius: 8px; padding: 12px;">
      <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
        <div style="width: 4px; height: 16px; background: #6366f1; border-radius: 2px;"></div>
        <span style="font-weight: 600; font-size: 14px;">To Do</span>
        <span style="background: #e2e8f0; padding: 2px 8px; border-radius: 9999px; font-size: 12px;">2</span>
      </div>
      <div style="background: white; border-radius: 6px; padding: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 8px;">
        <div style="font-size: 14px; font-weight: 500; margin-bottom: 8px;">Design homepage</div>
        <div style="display: flex; gap: 4px; margin-bottom: 8px;">
          <span style="background: #dbeafe; color: #1d4ed8; padding: 2px 6px; border-radius: 4px; font-size: 10px;">UI</span>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <span style="background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 4px; font-size: 10px;">High</span>
          <div style="width: 24px; height: 24px; background: #6366f1; border-radius: 50%; font-size: 10px; color: white; display: flex; align-items: center; justify-content: center;">JD</div>
        </div>
      </div>
      <div style="background: white; border-radius: 6px; padding: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; font-weight: 500;">Write documentation</div>
      </div>
    </div>
    <div style="min-width: 280px; background: #f8fafc; border-radius: 8px; padding: 12px;">
      <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
        <div style="width: 4px; height: 16px; background: #f59e0b; border-radius: 2px;"></div>
        <span style="font-weight: 600; font-size: 14px;">In Progress</span>
        <span style="background: #e2e8f0; padding: 2px 8px; border-radius: 9999px; font-size: 12px;">1</span>
      </div>
      <div style="background: white; border-radius: 6px; padding: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; font-weight: 500; margin-bottom: 8px;">Implement login</div>
        <span style="background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 4px; font-size: 10px;">Medium</span>
      </div>
    </div>
    <div style="min-width: 280px; background: #f8fafc; border-radius: 8px; padding: 12px;">
      <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
        <div style="width: 4px; height: 16px; background: #22c55e; border-radius: 2px;"></div>
        <span style="font-weight: 600; font-size: 14px;">Done</span>
        <span style="background: #e2e8f0; padding: 2px 8px; border-radius: 9999px; font-size: 12px;">1</span>
      </div>
      <div style="background: white; border-radius: 6px; padding: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; font-weight: 500;">Setup project</div>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <KanbanBoard
    :columns="columns"
    @card-click="handleCardClick"
    @card-move="handleCardMove"
  />
</template>

<script setup>
import { ref } from 'vue'
import { KanbanBoard } from '@cambosoftware/cambo-admin'

const columns = ref([
  {
    id: 'todo',
    title: 'To Do',
    color: '#6366f1',
    items: [
      { id: 1, title: 'Design homepage', priority: 'high', labels: ['UI'] },
      { id: 2, title: 'Write documentation' }
    ]
  },
  {
    id: 'in-progress',
    title: 'In Progress',
    color: '#f59e0b',
    items: [
      { id: 3, title: 'Implement login', priority: 'medium' }
    ]
  },
  {
    id: 'done',
    title: 'Done',
    color: '#22c55e',
    items: [
      { id: 4, title: 'Setup project' }
    ]
  }
])
</script>
```

  </template>
</LiveDemo>
