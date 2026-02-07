# FileDropzone

A drag-and-drop file upload zone with file validation, preview thumbnails, and multiple file support.

## Import

```vue
<script setup>
import { FileDropzone } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Array` | `[]` | Array of selected files (v-model) |
| `accept` | `String` | `null` | Accepted file types (e.g., '.pdf,.doc', 'image/*') |
| `multiple` | `Boolean` | `true` | Allow multiple file selection |
| `maxSize` | `Number` | `null` | Maximum file size in MB |
| `maxFiles` | `Number` | `null` | Maximum number of files |
| `disabled` | `Boolean` | `false` | Disable the dropzone |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `description` | `String` | `null` | Custom description text |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Array` | Emitted when files change (for v-model) |
| `change` | `Array` | Emitted when files change |
| `error` | `String` | Emitted when validation fails |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <FileDropzone v-model="files" />
</template>

<script setup>
import { ref } from 'vue'
const files = ref([])
</script>
```

### Image Upload

```vue
<template>
  <FileDropzone
    v-model="images"
    accept="image/*"
    :max-size="5"
    :max-files="10"
    description="Drop images here or click to upload (max 5MB each)"
  />
</template>

<script setup>
import { ref } from 'vue'
const images = ref([])
</script>
```

### Document Upload

```vue
<template>
  <FileDropzone
    v-model="documents"
    accept=".pdf,.doc,.docx,.xls,.xlsx"
    :max-size="10"
    :max-files="5"
    description="PDF, Word, or Excel files up to 10MB"
  />
</template>
```

### Single File Only

```vue
<template>
  <FileDropzone
    v-model="file"
    :multiple="false"
    accept=".pdf"
    :max-size="20"
    description="Upload a single PDF file"
  />
</template>

<script setup>
import { ref } from 'vue'
const file = ref([])
</script>
```

### With Validation Limits

```vue
<template>
  <div class="space-y-2">
    <FileDropzone
      v-model="files"
      :max-size="5"
      :max-files="3"
      @error="error = $event"
    />
    <p v-if="error" class="text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const files = ref([])
const error = ref(null)
</script>
```

### With Error State

```vue
<template>
  <FileDropzone
    v-model="files"
    :error="files.length === 0 ? 'Please upload at least one file' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <FileDropzone
    v-model="files"
    disabled
  />
</template>
```

### In a Form

```vue
<template>
  <form @submit.prevent="submit">
    <FormGroup label="Attachments" required>
      <FileDropzone
        v-model="form.attachments"
        accept=".pdf,.doc,.docx"
        :max-size="10"
        :max-files="5"
        :error="errors.attachments"
        @error="errors.attachments = $event"
      />
    </FormGroup>
  </form>
</template>
```

### Media Gallery Upload

```vue
<template>
  <div class="space-y-4">
    <FileDropzone
      v-model="media"
      accept="image/*,video/*"
      :max-size="50"
      :max-files="20"
      description="Drop images or videos (max 50MB each)"
    />

    <!-- Preview grid -->
    <div v-if="media.length" class="grid grid-cols-4 gap-4">
      <div
        v-for="(file, index) in media"
        :key="index"
        class="relative aspect-square rounded-lg overflow-hidden bg-gray-100"
      >
        <img
          v-if="file.type.startsWith('image/')"
          :src="createObjectURL(file)"
          class="w-full h-full object-cover"
        />
        <div v-else class="flex items-center justify-center h-full">
          <span class="text-gray-500">{{ file.name }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const media = ref([])

const createObjectURL = (file) => URL.createObjectURL(file)
</script>
```

### Upload Progress Example

```vue
<template>
  <div class="space-y-4">
    <FileDropzone
      v-model="files"
      :disabled="uploading"
      @change="handleFilesChange"
    />

    <!-- Upload progress -->
    <div v-if="uploading" class="space-y-2">
      <div
        v-for="(progress, index) in uploadProgress"
        :key="index"
        class="flex items-center gap-2"
      >
        <span class="text-sm truncate flex-1">{{ files[index]?.name }}</span>
        <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
          <div
            class="h-full bg-primary-500 transition-all"
            :style="{ width: progress + '%' }"
          />
        </div>
        <span class="text-xs text-gray-500 w-12 text-right">{{ progress }}%</span>
      </div>
    </div>

    <button
      v-if="files.length && !uploading"
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
const uploading = ref(false)
const uploadProgress = ref([])

const handleFilesChange = (newFiles) => {
  uploadProgress.value = newFiles.map(() => 0)
}

const upload = async () => {
  uploading.value = true
  // Simulate upload with progress
  for (let i = 0; i < files.value.length; i++) {
    for (let p = 0; p <= 100; p += 10) {
      uploadProgress.value[i] = p
      await new Promise(r => setTimeout(r, 100))
    }
  }
  uploading.value = false
}
</script>
```

## Features

- Drag-and-drop file upload
- Visual feedback during drag over
- Click to open native file picker
- Thumbnail previews for images
- File type validation (accept attribute)
- Maximum file size validation
- Maximum file count validation
- Individual file removal
- Human-readable file size display
- Auto-generated description with constraints
- Custom description support
- Error event for validation failures

## Playground

Try the FileDropzone component:

<LiveDemo>
  <DemoFileDropzone />

  <template #code>

```vue
<script setup>
import { FileDropzone } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const files = ref([])
</script>

<template>
    <FileDropzone
        v-model="files"
        accept="image/*"
        :max-size="5"
        :max-files="10"
        description="Drop images here or click to upload"
    />
</template>
```

  </template>
</LiveDemo>
