# Highlight

A component for highlighting text matches in search results.

## Import

```vue
<script setup>
import Highlight from '@/Components/Utilities/Highlight.vue'
</script>
```

## Basic Usage

```vue
<template>
  <Highlight :text="text" :query="searchQuery" />
</template>

<script setup>
const text = 'The quick brown fox jumps over the lazy dog'
const searchQuery = 'fox'
</script>
```

Output: The quick brown **fox** jumps over the lazy dog

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `text` | `string` | `''` | Full text to display |
| `query` | `string` | `''` | Search term to highlight |
| `caseSensitive` | `boolean` | `false` | Case-sensitive matching |
| `highlightClass` | `string` | `'bg-yellow-200'` | CSS class for highlights |

## Examples

### Search Results

```vue
<template>
  <ul>
    <li v-for="result in results" :key="result.id" class="p-3 border-b">
      <Highlight :text="result.title" :query="searchTerm" />
      <p class="text-sm text-gray-500">
        <Highlight :text="result.description" :query="searchTerm" />
      </p>
    </li>
  </ul>
</template>
```

### Custom Highlight Style

```vue
<Highlight
  :text="text"
  :query="query"
  highlight-class="bg-indigo-100 text-indigo-800 font-semibold"
/>
```

### Case-Sensitive

```vue
<Highlight
  :text="text"
  :query="query"
  case-sensitive
/>
```

### Multiple Matches

```vue
<Highlight
  text="The cat sat on the mat with another cat"
  query="cat"
/>
```

Output: The **cat** sat on the mat with another **cat**

## Real-World Example

```vue
<template>
  <div class="search-results">
    <Input
      v-model="search"
      placeholder="Search users..."
      icon-left
    >
      <template #icon>
        <MagnifyingGlassIcon class="w-5 h-5" />
      </template>
    </Input>

    <div class="mt-4 space-y-2">
      <div
        v-for="user in filteredUsers"
        :key="user.id"
        class="p-3 bg-white rounded-lg border hover:border-indigo-500"
      >
        <p class="font-medium">
          <Highlight :text="user.name" :query="search" />
        </p>
        <p class="text-sm text-gray-500">
          <Highlight :text="user.email" :query="search" />
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const search = ref('')
const users = [
  { id: 1, name: 'John Doe', email: 'john@example.com' },
  { id: 2, name: 'Jane Smith', email: 'jane@example.com' },
  { id: 3, name: 'Bob Johnson', email: 'bob@example.com' }
]

const filteredUsers = computed(() => {
  if (!search.value) return users
  const q = search.value.toLowerCase()
  return users.filter(u =>
    u.name.toLowerCase().includes(q) ||
    u.email.toLowerCase().includes(q)
  )
})
</script>
```

## Playground

Try the Highlight component:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 8px;">
    <p>The quick brown <mark style="background-color: #fef08a; padding: 1px 2px; border-radius: 2px;">fox</mark> jumps over the lazy dog</p>
    <p>The <mark style="background-color: #fef08a; padding: 1px 2px; border-radius: 2px;">cat</mark> sat on the mat with another <mark style="background-color: #fef08a; padding: 1px 2px; border-radius: 2px;">cat</mark></p>
    <p style="color: #6b7280; font-size: 14px;">Search term: "fox" and "cat"</p>
  </div>

  <template #code>

```vue
<template>
  <div class="space-y-2">
    <p>
      <Highlight
        text="The quick brown fox jumps over the lazy dog"
        query="fox"
      />
    </p>
    <p>
      <Highlight
        text="The cat sat on the mat with another cat"
        query="cat"
      />
    </p>
  </div>
</template>

<script setup>
import Highlight from '@/Components/Utilities/Highlight.vue'
</script>
```

  </template>
</LiveDemo>
