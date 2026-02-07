# SelectSearch

A searchable dropdown select component that allows users to filter options by typing. Ideal for long lists of options.

## Import

```vue
<script setup>
import { SelectSearch } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String \| Number \| null` | `null` | The selected value (v-model) |
| `options` | `Array` | `[]` | Array of options (strings, numbers, or objects) |
| `placeholder` | `String` | `'Search...'` | Placeholder text for the search input |
| `size` | `String` | `'md'` | Select size. Allowed: `'sm'`, `'md'`, `'lg'` |
| `disabled` | `Boolean` | `false` | Disables the select |
| `error` | `String \| Boolean` | `null` | Error state or message. When truthy, shows error styling |
| `clearable` | `Boolean` | `false` | Shows a clear button when an option is selected |
| `optionLabel` | `String` | `'label'` | Property name to use for option label (when options are objects) |
| `optionValue` | `String` | `'value'` | Property name to use for option value (when options are objects) |
| `noResultsText` | `String` | `'No results'` | Text shown when search yields no results |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| Number \| null` | Emitted when selection changes |
| `change` | `String \| Number \| null` | Emitted when selection changes |
| `search` | `String` | Emitted when search input changes (useful for async search) |

## Slots

### option

Customize how individual options are rendered in the dropdown.

| Slot Prop | Type | Description |
|-----------|------|-------------|
| `option` | `Object` | The normalized option object with `label`, `value`, `disabled` |

## Basic Example

```vue
<script setup>
import { SelectSearch } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const country = ref(null)
const countries = [
    { value: 'us', label: 'United States' },
    { value: 'uk', label: 'United Kingdom' },
    { value: 'ca', label: 'Canada' },
    { value: 'au', label: 'Australia' },
    { value: 'de', label: 'Germany' },
    { value: 'fr', label: 'France' },
    { value: 'jp', label: 'Japan' }
]
</script>

<template>
    <SelectSearch
        v-model="country"
        :options="countries"
        placeholder="Search countries..."
    />
</template>
```

## Sizes

```vue
<!-- Small -->
<SelectSearch v-model="value" :options="options" size="sm" />

<!-- Medium (default) -->
<SelectSearch v-model="value" :options="options" size="md" />

<!-- Large -->
<SelectSearch v-model="value" :options="options" size="lg" />
```

## Clearable Select

```vue
<SelectSearch
    v-model="user"
    :options="users"
    clearable
    placeholder="Search users..."
/>
```

## Error State

```vue
<FormGroup label="Country" :error="form.errors.country">
    <SelectSearch
        v-model="form.country"
        :options="countries"
        :error="form.errors.country"
        placeholder="Search countries..."
    />
</FormGroup>
```

## Custom No Results Text

```vue
<SelectSearch
    v-model="value"
    :options="options"
    no-results-text="No matching items found"
    placeholder="Search..."
/>
```

## Async Search (Remote Data)

Use the `search` event to load options from an API:

```vue
<script setup>
import { SelectSearch } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'
import { debounce } from 'lodash-es'

const selectedUser = ref(null)
const users = ref([])
const loading = ref(false)

const searchUsers = debounce(async (query) => {
    if (!query) {
        users.value = []
        return
    }

    loading.value = true
    try {
        const response = await fetch(`/api/users?search=${query}`)
        const data = await response.json()
        users.value = data.map(user => ({
            value: user.id,
            label: user.name
        }))
    } finally {
        loading.value = false
    }
}, 300)
</script>

<template>
    <SelectSearch
        v-model="selectedUser"
        :options="users"
        @search="searchUsers"
        placeholder="Search users..."
        no-results-text="Type to search users..."
    />
</template>
```

## Custom Option Rendering

```vue
<script setup>
const users = [
    { value: 1, label: 'John Doe', email: 'john@example.com', avatar: '/avatars/john.jpg' },
    { value: 2, label: 'Jane Smith', email: 'jane@example.com', avatar: '/avatars/jane.jpg' }
]
</script>

<template>
    <SelectSearch v-model="selectedUser" :options="users">
        <template #option="{ option }">
            <div class="flex items-center gap-3">
                <img :src="option.avatar" class="w-8 h-8 rounded-full" />
                <div>
                    <div class="font-medium">{{ option.label }}</div>
                    <div class="text-xs text-gray-500">{{ option.email }}</div>
                </div>
            </div>
        </template>
    </SelectSearch>
</template>
```

## Custom Property Names

```vue
<script setup>
const products = [
    { id: 1, name: 'Product A', sku: 'SKU-001' },
    { id: 2, name: 'Product B', sku: 'SKU-002' },
    { id: 3, name: 'Product C', sku: 'SKU-003' }
]
</script>

<template>
    <SelectSearch
        v-model="selectedProduct"
        :options="products"
        option-label="name"
        option-value="id"
        placeholder="Search products..."
    />
</template>
```

## Keyboard Navigation

The SelectSearch component supports full keyboard navigation:

| Key | Action |
|-----|--------|
| `Enter` / `Space` | Open dropdown (when closed) or select highlighted option |
| `ArrowDown` | Open dropdown or move to next option |
| `ArrowUp` | Move to previous option |
| `Escape` | Close dropdown |
| Type any character | Start filtering options |

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, SelectSearch, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    country: null,
    city: null
})

const countries = [
    { value: 'us', label: 'United States' },
    { value: 'uk', label: 'United Kingdom' },
    { value: 'ca', label: 'Canada' },
    { value: 'au', label: 'Australia' },
    // ... more countries
]

const cities = computed(() => {
    // Return cities based on selected country
    const citiesByCountry = {
        us: [
            { value: 'nyc', label: 'New York' },
            { value: 'la', label: 'Los Angeles' }
        ],
        uk: [
            { value: 'london', label: 'London' },
            { value: 'manchester', label: 'Manchester' }
        ]
    }
    return citiesByCountry[form.country] || []
})

const submit = (form) => {
    form.post('/addresses')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Name" required :error="form.errors.name">
            <Input v-model="form.name" :error="form.errors.name" />
        </FormGroup>

        <FormGroup label="Country" required :error="form.errors.country">
            <SelectSearch
                v-model="form.country"
                :options="countries"
                :error="form.errors.country"
                clearable
                placeholder="Search countries..."
            />
        </FormGroup>

        <FormGroup label="City" :error="form.errors.city">
            <SelectSearch
                v-model="form.city"
                :options="cities"
                :error="form.errors.city"
                :disabled="!form.country"
                placeholder="Search cities..."
            />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Save Address
        </Button>
    </Form>
</template>
```

## When to Use SelectSearch vs Select

| Use SelectSearch | Use Select |
|------------------|------------|
| More than 10-15 options | Fewer than 10 options |
| Users need to search/filter | All options visible at once |
| Remote/async data loading | Static options |
| Long option labels | Short option labels |

## Styling

The SelectSearch component includes:
- Search input within the trigger
- Custom dropdown with smooth animations
- Highlighted search text
- Selected state styling
- No results message

### Size Classes

| Size | Padding | Font Size |
|------|---------|-----------|
| `sm` | `px-2.5 py-1.5` | `text-xs` |
| `md` | `px-3 py-2` | `text-sm` |
| `lg` | `px-4 py-2.5` | `text-base` |

## Accessibility

- Uses `role="listbox"` for dropdown
- Uses `role="option"` for each option
- Includes `aria-selected` attribute
- Full keyboard navigation support
- Search input auto-focuses when opened
- Click outside closes dropdown

## Playground

Try the SelectSearch component:

<LiveDemo>
  <DemoSelectSearch />

  <template #code>

```vue
<script setup>
import { SelectSearch } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const country = ref(null)
const countries = [
    { value: 'us', label: 'United States' },
    { value: 'uk', label: 'United Kingdom' },
    { value: 'ca', label: 'Canada' },
    { value: 'au', label: 'Australia' },
    { value: 'de', label: 'Germany' },
    { value: 'fr', label: 'France' },
    { value: 'jp', label: 'Japan' }
]
</script>

<template>
    <SelectSearch
        v-model="country"
        :options="countries"
        placeholder="Search countries..."
    />
</template>
```

  </template>
</LiveDemo>
