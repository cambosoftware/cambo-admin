# FilePicker

A file picker component for selecting single or multiple files with validation support.

## Import

```vue
<script setup>
import { FilePicker } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `File \| Array \| null` | `null` | The selected file(s) (v-model) |
| `accept` | `String` | `null` | Accepted file types (e.g., '.pdf,.doc', 'image/*') |
| `multiple` | `Boolean` | `false` | Allow multiple file selection |
| `maxSize` | `Number` | `null` | Maximum file size in MB |
| `maxFiles` | `Number` | `null` | Maximum number of files (for multiple mode) |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the file picker |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `placeholder` | `String` | `'Choose a file'` | Placeholder text |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `File \| Array \| null` | Emitted when file(s) change (for v-model) |
| `change` | `File \| Array \| null` | Emitted when file(s) change |
| `error` | `String` | Emitted when validation fails (e.g., file too large) |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <FilePicker v-model="file" />
</template>

<script setup>
import { ref } from 'vue'
const file = ref(null)
</script>
```

### Single File with Type Restriction

```vue
<template>
  <FilePicker
    v-model="document"
    accept=".pdf,.doc,.docx"
    placeholder="Select a document"
  />
</template>
```

### Multiple Files

```vue
<template>
  <FilePicker
    v-model="files"
    multiple
    :max-files="5"
    placeholder="Select files"
  />
</template>

<script setup>
import { ref } from 'vue'
const files = ref([])
</script>
```

### With Size Limit

```vue
<template>
  <FilePicker
    v-model="file"
    :max-size="10"
    placeholder="Max 10MB"
    @error="handleError"
  />
</template>

<script setup>
import { ref } from 'vue'

const file = ref(null)

const handleError = (message) => {
  alert(message)
}
</script>
```

### Image Files Only

```vue
<template>
  <FilePicker
    v-model="image"
    accept="image/*"
    :max-size="5"
    placeholder="Select an image"
  />
</template>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <FilePicker v-model="file" size="sm" placeholder="Small" />
    <FilePicker v-model="file" size="md" placeholder="Medium" />
    <FilePicker v-model="file" size="lg" placeholder="Large" />
  </div>
</template>
```

### With Error Handling

```vue
<template>
  <div class="space-y-2">
    <FilePicker
      v-model="file"
      :max-size="2"
      accept=".pdf"
      :error="error"
      @error="error = $event"
    />
    <p v-if="error" class="text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const file = ref(null)
const error = ref(null)
</script>
```

### Disabled State

```vue
<template>
  <FilePicker
    v-model="file"
    disabled
    placeholder="Disabled"
  />
</template>
```

### In a Form

```vue
<template>
  <form @submit.prevent="submit">
    <FormGroup label="Resume" required>
      <FilePicker
        v-model="form.resume"
        accept=".pdf,.doc,.docx"
        :max-size="5"
        :error="errors.resume"
        @error="errors.resume = $event"
      />
    </FormGroup>

    <FormGroup label="Supporting Documents">
      <FilePicker
        v-model="form.documents"
        multiple
        :max-files="3"
        :max-size="10"
        accept=".pdf"
        @error="errors.documents = $event"
      />
    </FormGroup>
  </form>
</template>
```

### Complete Example with Upload

```vue
<template>
  <div class="space-y-4">
    <FilePicker
      v-model="files"
      multiple
      :max-files="5"
      :max-size="10"
      accept=".pdf,.doc,.docx,.xls,.xlsx"
      @error="handleError"
    />

    <button
      v-if="files.length"
      type="button"
      class="btn btn-primary"
      @click="upload"
    >
      Upload {{ files.length }} file(s)
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const files = ref([])

const handleError = (message) => {
  console.error(message)
}

const upload = async () => {
  const formData = new FormData()
  files.value.forEach((file, index) => {
    formData.append(`files[${index}]`, file)
  })

  // await axios.post('/api/upload', formData)
}
</script>
```

## Features

- Single or multiple file selection
- File type restriction via accept attribute
- Maximum file size validation
- Maximum file count validation (multiple mode)
- Visual file list with names and sizes
- Remove individual files from selection
- Add more files button (multiple mode with limit)
- Human-readable file size formatting
- Error event for validation failures
- Click to open native file picker

## Playground

Try the FilePicker component:

<LiveDemo>
  <DemoFilePicker />

  <template #code>

```vue
<script setup>
import { FilePicker } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const file = ref(null)
</script>

<template>
    <FilePicker
        v-model="file"
        accept=".pdf,.doc,.docx"
        :max-size="10"
        placeholder="Select a document"
    />
</template>
```

  </template>
</LiveDemo>
