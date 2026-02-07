# TagInput

A tag input component for entering multiple values with suggestions, validation, and keyboard navigation.

## Import

```vue
<script setup>
import { TagInput } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Array` | `[]` | Array of tag strings (v-model) |
| `placeholder` | `String` | `'Add a tag...'` | Placeholder text |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the input |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `max` | `Number` | `null` | Maximum number of tags |
| `allowDuplicates` | `Boolean` | `false` | Allow duplicate tags |
| `separator` | `String \| Array` | `[',', 'Enter']` | Characters that trigger tag creation |
| `suggestions` | `Array` | `[]` | Array of suggested tags |
| `validateTag` | `Function` | `null` | Custom validation function |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Array` | Emitted when tags change (for v-model) |
| `add` | `String` | Emitted when a tag is added |
| `remove` | `String` | Emitted when a tag is removed |
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
  <TagInput v-model="tags" />
</template>

<script setup>
import { ref } from 'vue'
const tags = ref([])
</script>
```

### With Initial Tags

```vue
<template>
  <TagInput v-model="tags" />
</template>

<script setup>
import { ref } from 'vue'
const tags = ref(['Vue', 'React', 'Angular'])
</script>
```

### With Suggestions

```vue
<template>
  <TagInput
    v-model="tags"
    :suggestions="availableTags"
    placeholder="Type to search tags..."
  />
</template>

<script setup>
import { ref } from 'vue'

const tags = ref([])
const availableTags = [
  'JavaScript',
  'TypeScript',
  'Vue.js',
  'React',
  'Angular',
  'Node.js',
  'Python',
  'Go',
  'Rust'
]
</script>
```

### Maximum Tags

```vue
<template>
  <TagInput
    v-model="tags"
    :max="5"
    placeholder="Add up to 5 tags..."
  />
</template>
```

### Allow Duplicates

```vue
<template>
  <TagInput
    v-model="tags"
    allow-duplicates
  />
</template>
```

### Custom Separators

```vue
<template>
  <!-- Only Enter key creates tags -->
  <TagInput
    v-model="tags"
    :separator="['Enter']"
    placeholder="Press Enter to add..."
  />

  <!-- Space also creates tags -->
  <TagInput
    v-model="tags"
    :separator="[',', 'Enter', ' ']"
    placeholder="Separate with comma, space, or Enter..."
  />
</template>
```

### With Validation

```vue
<template>
  <TagInput
    v-model="emails"
    :validate-tag="validateEmail"
    placeholder="Enter email addresses..."
  />
</template>

<script setup>
import { ref } from 'vue'

const emails = ref([])

const validateEmail = (tag) => {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(tag)
}
</script>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <TagInput v-model="tags" size="sm" placeholder="Small" />
    <TagInput v-model="tags" size="md" placeholder="Medium" />
    <TagInput v-model="tags" size="lg" placeholder="Large" />
  </div>
</template>
```

### With Error State

```vue
<template>
  <TagInput
    v-model="tags"
    :error="tags.length === 0 ? 'At least one tag is required' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <TagInput
    v-model="tags"
    disabled
  />
</template>
```

### In a Form (Article Categories)

```vue
<template>
  <form @submit.prevent="saveArticle">
    <FormGroup label="Title" required>
      <Input v-model="form.title" />
    </FormGroup>

    <FormGroup label="Tags" required>
      <TagInput
        v-model="form.tags"
        :suggestions="availableTags"
        :max="10"
        :error="errors.tags"
      />
      <p class="mt-1 text-xs text-gray-500">
        Add up to 10 tags to categorize your article
      </p>
    </FormGroup>

    <button type="submit" class="btn btn-primary">
      Save Article
    </button>
  </form>
</template>
```

### Skills Input

```vue
<template>
  <FormGroup label="Skills">
    <TagInput
      v-model="skills"
      :suggestions="techSkills"
      :max="15"
      placeholder="Add your skills..."
    />
  </FormGroup>
</template>

<script setup>
import { ref } from 'vue'

const skills = ref([])
const techSkills = [
  'JavaScript', 'TypeScript', 'Python', 'Java', 'C++',
  'React', 'Vue', 'Angular', 'Node.js', 'Django',
  'PostgreSQL', 'MongoDB', 'Redis', 'Docker', 'Kubernetes'
]
</script>
```

### Email Recipients

```vue
<template>
  <FormGroup label="Recipients">
    <TagInput
      v-model="recipients"
      :validate-tag="validateEmail"
      :separator="[',', 'Enter', ' ']"
      placeholder="Enter email addresses..."
    />
  </FormGroup>
</template>

<script setup>
import { ref } from 'vue'

const recipients = ref([])

const validateEmail = (email) => {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return regex.test(email)
}
</script>
```

## Keyboard Navigation

| Key | Action |
|-----|--------|
| `Enter` | Add tag (if separator includes Enter) |
| `,` | Add tag (if separator includes comma) |
| `Backspace` | Remove last tag (when input is empty) |
| `Escape` | Close suggestions dropdown |

## Features

- Multiple tag input with array binding
- Autocomplete suggestions dropdown
- Configurable separators for tag creation
- Maximum tag limit
- Duplicate prevention (configurable)
- Custom validation function
- Add tags on blur
- Remove individual tags
- Keyboard navigation support
- Focus management
- Accessible design

## Playground

Try the TagInput component:

<LiveDemo>
  <DemoTagInput />

  <template #code>

```vue
<script setup>
import { TagInput } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const tags = ref(['Vue', 'React'])
const suggestions = [
    'JavaScript', 'TypeScript', 'Vue.js', 'React',
    'Angular', 'Node.js', 'Python', 'Go', 'Rust'
]
</script>

<template>
    <TagInput
        v-model="tags"
        :suggestions="suggestions"
        :max="10"
        placeholder="Add a tag..."
    />
</template>
```

  </template>
</LiveDemo>
