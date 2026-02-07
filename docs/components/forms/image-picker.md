# ImagePicker

An image picker component with preview, supporting various aspect ratios and image validation.

## Import

```vue
<script setup>
import { ImagePicker } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `File \| null` | `null` | The selected image file (v-model) |
| `accept` | `String` | `'image/*'` | Accepted file types |
| `maxSize` | `Number` | `5` | Maximum file size in MB |
| `size` | `String` | `'md'` | Component size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the image picker |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `previewUrl` | `String` | `null` | URL of existing image to display as preview |
| `ratio` | `String` | `'square'` | Aspect ratio: `'square'`, `'16/9'`, `'4/3'`, or `'auto'` |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `File \| null` | Emitted when image changes (for v-model) |
| `change` | `File \| null` | Emitted when image changes |
| `error` | `String` | Emitted when validation fails |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <ImagePicker v-model="image" />
</template>

<script setup>
import { ref } from 'vue'
const image = ref(null)
</script>
```

### With Existing Image Preview

```vue
<template>
  <ImagePicker
    v-model="image"
    :preview-url="user.avatarUrl"
  />
</template>

<script setup>
import { ref } from 'vue'

const image = ref(null)
const user = {
  avatarUrl: 'https://example.com/avatar.jpg'
}
</script>
```

### Different Aspect Ratios

```vue
<template>
  <div class="grid grid-cols-2 gap-4">
    <!-- Square (1:1) -->
    <div>
      <p class="text-sm mb-2">Square</p>
      <ImagePicker v-model="image1" ratio="square" />
    </div>

    <!-- 16:9 (Widescreen) -->
    <div>
      <p class="text-sm mb-2">16:9</p>
      <ImagePicker v-model="image2" ratio="16/9" />
    </div>

    <!-- 4:3 (Standard) -->
    <div>
      <p class="text-sm mb-2">4:3</p>
      <ImagePicker v-model="image3" ratio="4/3" />
    </div>

    <!-- Auto (adapts to content) -->
    <div>
      <p class="text-sm mb-2">Auto</p>
      <ImagePicker v-model="image4" ratio="auto" />
    </div>
  </div>
</template>
```

### Different Sizes

```vue
<template>
  <div class="flex gap-4 items-end">
    <ImagePicker v-model="image" size="sm" />
    <ImagePicker v-model="image" size="md" />
    <ImagePicker v-model="image" size="lg" />
  </div>
</template>
```

### With Size Limit

```vue
<template>
  <ImagePicker
    v-model="image"
    :max-size="2"
    @error="handleError"
  />
</template>

<script setup>
import { ref } from 'vue'

const image = ref(null)

const handleError = (message) => {
  alert(message)
}
</script>
```

### Specific Image Types

```vue
<template>
  <ImagePicker
    v-model="image"
    accept="image/png,image/jpeg"
  />
</template>
```

### With Error State

```vue
<template>
  <ImagePicker
    v-model="image"
    :error="!image ? 'Please select an image' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <ImagePicker
    v-model="image"
    disabled
    preview-url="https://example.com/image.jpg"
  />
</template>
```

### Profile Avatar Upload

```vue
<template>
  <form @submit.prevent="saveProfile">
    <FormGroup label="Profile Picture">
      <ImagePicker
        v-model="avatar"
        :preview-url="user.avatarUrl"
        ratio="square"
        size="lg"
        :max-size="2"
        :error="errors.avatar"
        @error="errors.avatar = $event"
      />
    </FormGroup>
  </form>
</template>

<script setup>
import { ref } from 'vue'

const avatar = ref(null)
const errors = ref({})
const user = {
  avatarUrl: '/images/default-avatar.png'
}
</script>
```

### Cover Image Upload

```vue
<template>
  <FormGroup label="Cover Image" required>
    <ImagePicker
      v-model="cover"
      :preview-url="article.coverUrl"
      ratio="16/9"
      :max-size="5"
      :error="errors.cover"
      @error="errors.cover = $event"
    />
    <p class="mt-1 text-xs text-gray-500">
      Recommended size: 1920x1080 pixels
    </p>
  </FormGroup>
</template>
```

### Gallery Image Upload

```vue
<template>
  <div class="grid grid-cols-4 gap-4">
    <ImagePicker
      v-for="(_, index) in 4"
      :key="index"
      v-model="gallery[index]"
      ratio="square"
      size="sm"
      :max-size="3"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
const gallery = ref([null, null, null, null])
</script>
```

## Features

- Live image preview after selection
- Support for existing image URL preview
- Multiple aspect ratio options
- Image type validation (only images allowed)
- Maximum file size validation
- Hover overlay for changing image
- Remove button to clear selection
- Responsive sizing based on aspect ratio
- FileReader-based preview generation
- Click to open native file picker

## Playground

Try the ImagePicker component:

<LiveDemo>
  <DemoImagePicker />

  <template #code>

```vue
<script setup>
import { ImagePicker } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const image = ref(null)
</script>

<template>
    <ImagePicker
        v-model="image"
        ratio="square"
        :max-size="5"
    />
</template>
```

  </template>
</LiveDemo>
