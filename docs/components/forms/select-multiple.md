# SelectMultiple

A multi-select dropdown component that allows users to select multiple options with search functionality and tag display.

## Import

```vue
<script setup>
import { SelectMultiple } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Array` | `[]` | Array of selected values (v-model) |
| `options` | `Array` | `[]` | Array of options (strings, numbers, or objects) |
| `placeholder` | `String` | `'Select...'` | Placeholder text when no options are selected |
| `size` | `String` | `'md'` | Select size. Allowed: `'sm'`, `'md'`, `'lg'` |
| `disabled` | `Boolean` | `false` | Disables the select |
| `error` | `String \| Boolean` | `null` | Error state or message. When truthy, shows error styling |
| `optionLabel` | `String` | `'label'` | Property name to use for option label (when options are objects) |
| `optionValue` | `String` | `'value'` | Property name to use for option value (when options are objects) |
| `noResultsText` | `String` | `'No results'` | Text shown when search yields no results |
| `max` | `Number` | `null` | Maximum number of selections allowed |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Array` | Emitted when selection changes |
| `change` | `Array` | Emitted when selection changes |

## Slots

### option

Customize how individual options are rendered in the dropdown.

| Slot Prop | Type | Description |
|-----------|------|-------------|
| `option` | `Object` | The normalized option object with `label`, `value`, `disabled` |

## Basic Example

```vue
<script setup>
import { SelectMultiple } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const selectedTags = ref([])
const tags = [
    { value: 'vue', label: 'Vue.js' },
    { value: 'react', label: 'React' },
    { value: 'angular', label: 'Angular' },
    { value: 'svelte', label: 'Svelte' },
    { value: 'node', label: 'Node.js' },
    { value: 'php', label: 'PHP' },
    { value: 'python', label: 'Python' }
]
</script>

<template>
    <SelectMultiple
        v-model="selectedTags"
        :options="tags"
        placeholder="Select technologies..."
    />
</template>
```

## Sizes

```vue
<!-- Small -->
<SelectMultiple v-model="values" :options="options" size="sm" />

<!-- Medium (default) -->
<SelectMultiple v-model="values" :options="options" size="md" />

<!-- Large -->
<SelectMultiple v-model="values" :options="options" size="lg" />
```

## Maximum Selections

Limit the number of selections:

```vue
<SelectMultiple
    v-model="selectedColors"
    :options="colors"
    :max="3"
    placeholder="Select up to 3 colors..."
/>
```

When the max is reached, additional options cannot be selected until one is removed.

## Error State

```vue
<FormGroup label="Skills" :error="form.errors.skills">
    <SelectMultiple
        v-model="form.skills"
        :options="skillOptions"
        :error="form.errors.skills"
        placeholder="Select skills..."
    />
</FormGroup>
```

## Disabled State

```vue
<SelectMultiple
    v-model="values"
    :options="options"
    disabled
/>
```

## Simple String/Number Options

```vue
<!-- String options -->
<SelectMultiple
    v-model="selectedFruits"
    :options="['Apple', 'Banana', 'Orange', 'Mango', 'Grape']"
    placeholder="Select fruits..."
/>

<!-- Number options -->
<SelectMultiple
    v-model="selectedYears"
    :options="[2020, 2021, 2022, 2023, 2024, 2025]"
    placeholder="Select years..."
/>
```

## Custom Property Names

```vue
<script setup>
const users = [
    { id: 1, name: 'John Doe' },
    { id: 2, name: 'Jane Smith' },
    { id: 3, name: 'Bob Wilson' }
]
</script>

<template>
    <SelectMultiple
        v-model="selectedUsers"
        :options="users"
        option-label="name"
        option-value="id"
        placeholder="Select team members..."
    />
</template>
```

## Custom Option Rendering

```vue
<script setup>
const permissions = [
    { value: 'read', label: 'Read', icon: EyeIcon, description: 'View content' },
    { value: 'write', label: 'Write', icon: PencilIcon, description: 'Edit content' },
    { value: 'delete', label: 'Delete', icon: TrashIcon, description: 'Remove content' },
    { value: 'admin', label: 'Admin', icon: KeyIcon, description: 'Full access' }
]
</script>

<template>
    <SelectMultiple v-model="selectedPermissions" :options="permissions">
        <template #option="{ option }">
            <div class="flex items-center gap-2">
                <component :is="option.icon" class="h-4 w-4 text-gray-500" />
                <div>
                    <div class="font-medium">{{ option.label }}</div>
                    <div class="text-xs text-gray-500">{{ option.description }}</div>
                </div>
            </div>
        </template>
    </SelectMultiple>
</template>
```

## With Disabled Options

```vue
<script setup>
const plans = [
    { value: 'free', label: 'Free' },
    { value: 'basic', label: 'Basic' },
    { value: 'pro', label: 'Pro', disabled: true },  // Not available
    { value: 'enterprise', label: 'Enterprise' }
]
</script>

<template>
    <SelectMultiple
        v-model="selectedPlans"
        :options="plans"
        placeholder="Select plans..."
    />
</template>
```

## Keyboard Shortcuts

| Key | Action |
|-----|--------|
| Type any character | Filter options |
| `Escape` | Close dropdown |
| `Backspace` | Remove last selected tag (when search is empty) |
| Click on tag X | Remove that specific selection |

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, SelectMultiple, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    title: '',
    categories: [],
    tags: []
})

const categories = [
    { value: 'tech', label: 'Technology' },
    { value: 'design', label: 'Design' },
    { value: 'business', label: 'Business' },
    { value: 'lifestyle', label: 'Lifestyle' }
]

const tags = [
    { value: 'featured', label: 'Featured' },
    { value: 'trending', label: 'Trending' },
    { value: 'new', label: 'New' },
    { value: 'popular', label: 'Popular' },
    { value: 'archived', label: 'Archived' }
]

const submit = (form) => {
    form.post('/articles')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Title" required :error="form.errors.title">
            <Input v-model="form.title" :error="form.errors.title" />
        </FormGroup>

        <FormGroup
            label="Categories"
            required
            :error="form.errors.categories"
            hint="Select at least one category"
        >
            <SelectMultiple
                v-model="form.categories"
                :options="categories"
                :error="form.errors.categories"
                placeholder="Select categories..."
            />
        </FormGroup>

        <FormGroup
            label="Tags"
            :error="form.errors.tags"
            hint="Add up to 5 tags"
        >
            <SelectMultiple
                v-model="form.tags"
                :options="tags"
                :max="5"
                :error="form.errors.tags"
                placeholder="Add tags..."
            />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Publish Article
        </Button>
    </Form>
</template>
```

## Use Cases

### Team Member Selection

```vue
<SelectMultiple
    v-model="teamMembers"
    :options="users"
    option-label="name"
    option-value="id"
    placeholder="Add team members..."
/>
```

### Permission Assignment

```vue
<SelectMultiple
    v-model="permissions"
    :options="availablePermissions"
    placeholder="Assign permissions..."
/>
```

### Tag/Category Selection

```vue
<SelectMultiple
    v-model="tags"
    :options="availableTags"
    :max="10"
    placeholder="Add tags..."
/>
```

## Styling

The SelectMultiple component includes:
- Selected items displayed as removable tags
- Inline search input
- Checkbox indicators in dropdown
- Smooth animations
- Focus and error states

### Size Classes

| Size | Min Height | Tag Padding | Font Size |
|------|------------|-------------|-----------|
| `sm` | `30px` | `px-1.5 py-0.5` | `text-xs` |
| `md` | `38px` | `px-2 py-0.5` | `text-xs` |
| `lg` | `46px` | `px-2.5 py-1` | `text-sm` |

## Accessibility

- Uses `role="listbox"` for dropdown
- Uses `role="option"` for each option
- Includes `aria-selected` attribute
- Checkbox visual indicators for selection state
- Keyboard navigation support
- Tags are removable via keyboard (backspace)
- Click outside closes dropdown

## Playground

Try the SelectMultiple component:

<LiveDemo>
  <DemoSelectMultiple />

  <template #code>

```vue
<script setup>
import { SelectMultiple } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const selectedTags = ref([])
const tags = [
    { value: 'vue', label: 'Vue.js' },
    { value: 'react', label: 'React' },
    { value: 'angular', label: 'Angular' },
    { value: 'svelte', label: 'Svelte' },
    { value: 'node', label: 'Node.js' },
    { value: 'php', label: 'PHP' },
    { value: 'python', label: 'Python' }
]
</script>

<template>
    <SelectMultiple
        v-model="selectedTags"
        :options="tags"
        placeholder="Select technologies..."
    />
</template>
```

  </template>
</LiveDemo>
