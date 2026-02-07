# Tree

A hierarchical tree component for displaying nested data with expandable nodes, selection, and checkbox support.

## Import

```js
import { Tree } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `items` | `Array` | `[]` | Tree data: `[{ id, label, icon?, children?: [], expanded?: boolean }]` |
| `expandedKeys` | `Array` | `[]` | Array of expanded node IDs (v-model supported) |
| `selectedKey` | `String\|Number` | `null` | Currently selected node ID (v-model supported) |
| `selectable` | `Boolean` | `false` | Enable node selection on click |
| `checkable` | `Boolean` | `false` | Show checkboxes for nodes |
| `checkedKeys` | `Array` | `[]` | Array of checked node IDs (v-model supported) |
| `defaultExpandAll` | `Boolean` | `false` | Expand all nodes by default |
| `indent` | `Number` | `24` | Indentation in pixels per level |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:expandedKeys` | `array` | Emitted when expanded nodes change |
| `update:selectedKey` | `string\|number` | Emitted when selection changes |
| `update:checkedKeys` | `array` | Emitted when checked nodes change |
| `select` | `key` | Emitted when a node is selected |
| `check` | `key, checked` | Emitted when a node is checked/unchecked |
| `expand` | `key, expanded` | Emitted when a node is expanded/collapsed |

## Slots

| Slot | Props | Description |
|------|-------|-------------|
| `default` | `{ items }` | Custom tree rendering |

## Basic Example

```vue
<template>
  <Tree :items="treeData" />
</template>

<script setup>
import { Tree } from '@cambosoftware/cambo-admin'

const treeData = [
  {
    id: 1,
    label: 'Documents',
    children: [
      { id: 2, label: 'Work' },
      { id: 3, label: 'Personal' },
    ]
  },
  {
    id: 4,
    label: 'Pictures',
    children: [
      { id: 5, label: 'Vacation 2024' },
      { id: 6, label: 'Family' },
    ]
  }
]
</script>
```

## Selectable Tree

```vue
<template>
  <Tree
    :items="treeData"
    selectable
    v-model:selected-key="selectedNode"
    @select="handleSelect"
  />
</template>

<script setup>
import { ref } from 'vue'

const selectedNode = ref(null)

const handleSelect = (key) => {
  console.log('Selected:', key)
}
</script>
```

## Checkable Tree

```vue
<template>
  <Tree
    :items="treeData"
    checkable
    v-model:checked-keys="checkedNodes"
    @check="handleCheck"
  />

  <p class="mt-4">Checked: {{ checkedNodes }}</p>
</template>

<script setup>
import { ref } from 'vue'

const checkedNodes = ref([])

const handleCheck = (key, checked) => {
  console.log(`Node ${key} is now ${checked ? 'checked' : 'unchecked'}`)
}
</script>
```

## Controlled Expansion

```vue
<template>
  <div class="mb-4">
    <Button size="sm" @click="expandAll">Expand All</Button>
    <Button size="sm" @click="collapseAll">Collapse All</Button>
  </div>

  <Tree
    :items="treeData"
    v-model:expanded-keys="expandedNodes"
  />
</template>

<script setup>
import { ref } from 'vue'

const expandedNodes = ref([1])

const getAllNodeIds = (items) => {
  const ids = []
  const traverse = (nodes) => {
    nodes.forEach(node => {
      ids.push(node.id)
      if (node.children) traverse(node.children)
    })
  }
  traverse(items)
  return ids
}

const expandAll = () => {
  expandedNodes.value = getAllNodeIds(treeData)
}

const collapseAll = () => {
  expandedNodes.value = []
}
</script>
```

## Default Expand All

```vue
<template>
  <Tree :items="treeData" default-expand-all />
</template>
```

## With Icons

```vue
<template>
  <Tree :items="treeData" selectable />
</template>

<script setup>
import { FolderIcon, DocumentIcon, PhotoIcon } from '@heroicons/vue/24/outline'

const treeData = [
  {
    id: 1,
    label: 'Documents',
    icon: FolderIcon,
    children: [
      { id: 2, label: 'Report.pdf', icon: DocumentIcon },
      { id: 3, label: 'Budget.xlsx', icon: DocumentIcon },
    ]
  },
  {
    id: 4,
    label: 'Images',
    icon: FolderIcon,
    children: [
      { id: 5, label: 'Photo.jpg', icon: PhotoIcon },
    ]
  }
]
</script>
```

## File Explorer Example

```vue
<template>
  <Card title="File Explorer">
    <Tree
      :items="fileSystem"
      selectable
      v-model:selected-key="selectedFile"
      v-model:expanded-keys="expandedFolders"
    />

    <div v-if="selectedFile" class="mt-4 p-4 bg-gray-50 rounded-lg">
      <p class="text-sm text-gray-600">Selected: {{ getNodeLabel(selectedFile) }}</p>
    </div>
  </Card>
</template>

<script setup>
import { ref } from 'vue'

const selectedFile = ref(null)
const expandedFolders = ref([1])

const fileSystem = [
  {
    id: 1,
    label: 'src',
    children: [
      {
        id: 2,
        label: 'components',
        children: [
          { id: 3, label: 'Button.vue' },
          { id: 4, label: 'Card.vue' },
        ]
      },
      {
        id: 5,
        label: 'pages',
        children: [
          { id: 6, label: 'Home.vue' },
          { id: 7, label: 'About.vue' },
        ]
      },
      { id: 8, label: 'App.vue' },
      { id: 9, label: 'main.js' },
    ]
  },
  { id: 10, label: 'package.json' },
  { id: 11, label: 'README.md' },
]

const getNodeLabel = (id) => {
  const findNode = (items, targetId) => {
    for (const item of items) {
      if (item.id === targetId) return item.label
      if (item.children) {
        const found = findNode(item.children, targetId)
        if (found) return found
      }
    }
    return null
  }
  return findNode(fileSystem, id)
}
</script>
```

## Category Selector

```vue
<template>
  <Tree
    :items="categories"
    checkable
    v-model:checked-keys="selectedCategories"
    default-expand-all
  />

  <div class="mt-4">
    <strong>Selected Categories:</strong>
    <ul class="mt-2">
      <li v-for="id in selectedCategories" :key="id">
        {{ getCategoryName(id) }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const selectedCategories = ref([])

const categories = [
  {
    id: 'electronics',
    label: 'Electronics',
    children: [
      { id: 'phones', label: 'Phones' },
      { id: 'laptops', label: 'Laptops' },
      { id: 'tablets', label: 'Tablets' },
    ]
  },
  {
    id: 'clothing',
    label: 'Clothing',
    children: [
      { id: 'mens', label: "Men's" },
      { id: 'womens', label: "Women's" },
      { id: 'kids', label: "Kids" },
    ]
  },
]
</script>
```

## Custom Indentation

```vue
<template>
  <!-- Larger indentation -->
  <Tree :items="treeData" :indent="32" />

  <!-- Smaller indentation -->
  <Tree :items="treeData" :indent="16" />
</template>
```

## Playground

Try the Tree component:

<LiveDemo>
  <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; font-size: 14px;">
    <div style="cursor: pointer;">
      <div style="display: flex; align-items: center; gap: 8px; padding: 4px 0;">
        <span style="color: #64748b;">&#9660;</span>
        <span style="color: #f59e0b;">&#128193;</span>
        <span style="font-weight: 500;">src</span>
      </div>
      <div style="margin-left: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; padding: 4px 0;">
          <span style="color: #64748b;">&#9660;</span>
          <span style="color: #f59e0b;">&#128193;</span>
          <span>components</span>
        </div>
        <div style="margin-left: 24px;">
          <div style="display: flex; align-items: center; gap: 8px; padding: 4px 0;">
            <span style="width: 12px;"></span>
            <span style="color: #22c55e;">&#128196;</span>
            <span>Button.vue</span>
          </div>
          <div style="display: flex; align-items: center; gap: 8px; padding: 4px 0; background: #eff6ff; margin: -4px -8px; padding: 4px 8px; border-radius: 4px;">
            <span style="width: 12px;"></span>
            <span style="color: #22c55e;">&#128196;</span>
            <span style="color: #3b82f6;">Card.vue</span>
          </div>
        </div>
        <div style="display: flex; align-items: center; gap: 8px; padding: 4px 0;">
          <span style="color: #64748b;">&#9654;</span>
          <span style="color: #f59e0b;">&#128193;</span>
          <span>pages</span>
        </div>
      </div>
    </div>
    <div style="display: flex; align-items: center; gap: 8px; padding: 4px 0; margin-top: 4px;">
      <span style="width: 12px;"></span>
      <span style="color: #64748b;">&#128196;</span>
      <span>package.json</span>
    </div>
  </div>

  <template #code>

```vue
<template>
  <Tree
    :items="fileSystem"
    selectable
    v-model:selected-key="selectedFile"
    v-model:expanded-keys="expandedFolders"
  />
</template>

<script setup>
import { ref } from 'vue'
import { Tree } from '@cambosoftware/cambo-admin'

const selectedFile = ref(null)
const expandedFolders = ref([1, 2])

const fileSystem = [
  {
    id: 1,
    label: 'src',
    children: [
      {
        id: 2,
        label: 'components',
        children: [
          { id: 3, label: 'Button.vue' },
          { id: 4, label: 'Card.vue' }
        ]
      },
      { id: 5, label: 'pages', children: [] }
    ]
  },
  { id: 6, label: 'package.json' }
]
</script>
```

  </template>
</LiveDemo>
