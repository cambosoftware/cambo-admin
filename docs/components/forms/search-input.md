# SearchInput

A search input component with debouncing, loading state, and clear functionality.

## Import

```vue
<script setup>
import { SearchInput } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String` | `''` | The search query (v-model) |
| `placeholder` | `String` | `'Search...'` | Placeholder text |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the input |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `loading` | `Boolean` | `false` | Show loading spinner |
| `debounce` | `Number` | `0` | Debounce delay in milliseconds |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String` | Emitted when query changes (for v-model) |
| `search` | `String` | Emitted when search should be performed (after debounce) |
| `clear` | - | Emitted when clear button is clicked |
| `focus` | `FocusEvent` | Emitted when input gains focus |
| `blur` | `FocusEvent` | Emitted when input loses focus |

## Exposed Methods

| Method | Description |
|--------|-------------|
| `focus()` | Focus the input |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <SearchInput v-model="query" @search="handleSearch" />
</template>

<script setup>
import { ref } from 'vue'

const query = ref('')

const handleSearch = (value) => {
  console.log('Searching for:', value)
}
</script>
```

### With Debounce

```vue
<template>
  <SearchInput
    v-model="query"
    :debounce="300"
    @search="handleSearch"
  />
</template>

<script setup>
import { ref } from 'vue'

const query = ref('')

const handleSearch = async (value) => {
  // This will only fire 300ms after the user stops typing
  const results = await fetchResults(value)
  // ...
}
</script>
```

### With Loading State

```vue
<template>
  <SearchInput
    v-model="query"
    :loading="isSearching"
    :debounce="300"
    @search="handleSearch"
  />
</template>

<script setup>
import { ref } from 'vue'

const query = ref('')
const isSearching = ref(false)

const handleSearch = async (value) => {
  isSearching.value = true
  try {
    const results = await fetchResults(value)
    // Handle results...
  } finally {
    isSearching.value = false
  }
}
</script>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <SearchInput v-model="query" size="sm" placeholder="Small search" />
    <SearchInput v-model="query" size="md" placeholder="Medium search" />
    <SearchInput v-model="query" size="lg" placeholder="Large search" />
  </div>
</template>
```

### With Error State

```vue
<template>
  <SearchInput
    v-model="query"
    :error="error"
    @search="handleSearch"
  />
</template>

<script setup>
import { ref } from 'vue'

const query = ref('')
const error = ref(null)

const handleSearch = async (value) => {
  try {
    await fetchResults(value)
    error.value = null
  } catch (e) {
    error.value = 'Search failed. Please try again.'
  }
}
</script>
```

### Disabled State

```vue
<template>
  <SearchInput
    v-model="query"
    disabled
    placeholder="Search disabled"
  />
</template>
```

### In a Navigation Bar

```vue
<template>
  <nav class="flex items-center justify-between px-4 py-2 bg-white shadow">
    <Logo />

    <div class="w-96">
      <SearchInput
        v-model="query"
        :debounce="300"
        :loading="loading"
        placeholder="Search products..."
        @search="searchProducts"
      />
    </div>

    <UserMenu />
  </nav>
</template>
```

### Table Search Filter

```vue
<template>
  <div>
    <div class="mb-4">
      <SearchInput
        v-model="search"
        :debounce="200"
        placeholder="Search users..."
        @search="filterUsers"
        @clear="resetFilter"
      />
    </div>

    <DataTable :data="filteredUsers" :columns="columns" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const search = ref('')
const users = ref([/* ... */])

const filteredUsers = computed(() => {
  if (!search.value) return users.value
  return users.value.filter(user =>
    user.name.toLowerCase().includes(search.value.toLowerCase())
  )
})

const filterUsers = (query) => {
  // Filter logic is handled by computed
}

const resetFilter = () => {
  search.value = ''
}
</script>
```

### Command Palette Search

```vue
<template>
  <div class="command-palette">
    <SearchInput
      ref="searchRef"
      v-model="query"
      :debounce="100"
      placeholder="Type a command or search..."
      @search="updateResults"
      @keydown.down.prevent="navigateDown"
      @keydown.up.prevent="navigateUp"
      @keydown.enter.prevent="executeSelected"
    />

    <ul v-if="results.length" class="mt-2 divide-y">
      <li
        v-for="(result, index) in results"
        :key="result.id"
        :class="{ 'bg-gray-100': index === selectedIndex }"
        @click="executeResult(result)"
      >
        {{ result.title }}
      </li>
    </ul>
  </div>
</template>
```

### API Search with Results

```vue
<template>
  <div class="relative">
    <SearchInput
      v-model="query"
      :loading="loading"
      :debounce="400"
      placeholder="Search for users..."
      @search="searchUsers"
    />

    <!-- Results dropdown -->
    <div
      v-if="results.length && query"
      class="absolute z-50 mt-1 w-full rounded-lg border bg-white shadow-lg"
    >
      <ul class="divide-y">
        <li
          v-for="user in results"
          :key="user.id"
          class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 cursor-pointer"
          @click="selectUser(user)"
        >
          <img :src="user.avatar" class="h-8 w-8 rounded-full" />
          <div>
            <p class="font-medium">{{ user.name }}</p>
            <p class="text-sm text-gray-500">{{ user.email }}</p>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const query = ref('')
const loading = ref(false)
const results = ref([])

const searchUsers = async (value) => {
  if (!value) {
    results.value = []
    return
  }

  loading.value = true
  try {
    const response = await fetch(`/api/users?q=${value}`)
    results.value = await response.json()
  } finally {
    loading.value = false
  }
}

const selectUser = (user) => {
  // Handle selection
  query.value = ''
  results.value = []
}
</script>
```

## Keyboard Shortcuts

| Key | Action |
|-----|--------|
| `Enter` | Trigger search immediately (bypasses debounce) |
| `Escape` | Clear the search input |

## Features

- Built-in search icon
- Debounced search emission
- Loading spinner indicator
- Clear button when has value
- Instant search on Enter key
- Clear on Escape key
- Three size variants
- Focus management
- Accessible design with proper input type
- Browser native search input behavior

## Playground

Try the SearchInput component:

<LiveDemo>
  <DemoSearchInput />

  <template #code>

```vue
<script setup>
import { SearchInput } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const query = ref('')
const loading = ref(false)

const handleSearch = async (value) => {
    loading.value = true
    // Simulate API call
    await new Promise(r => setTimeout(r, 500))
    loading.value = false
}
</script>

<template>
    <SearchInput
        v-model="query"
        :loading="loading"
        :debounce="300"
        placeholder="Search..."
        @search="handleSearch"
    />
</template>
```

  </template>
</LiveDemo>
