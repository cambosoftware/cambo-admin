# ProgressBar

A component for displaying progress indicators with various styles, sizes, and animation options.

## Import

```js
import { ProgressBar } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `value` | `Number` | `0` | Current progress value |
| `max` | `Number` | `100` | Maximum progress value |
| `variant` | `String` | `'primary'` | Color variant: `'primary'`, `'success'`, `'warning'`, `'danger'`, `'info'` |
| `size` | `String` | `'md'` | Size: `'xs'`, `'sm'`, `'md'`, `'lg'` |
| `showLabel` | `Boolean` | `false` | Show percentage label |
| `striped` | `Boolean` | `false` | Add striped pattern |
| `animated` | `Boolean` | `false` | Animate stripes (requires striped) |
| `indeterminate` | `Boolean` | `false` | Show indeterminate loading animation |

## Slots

| Slot | Description |
|------|-------------|
| `label` | Custom label content (when showLabel is true) |

## Basic Example

```vue
<template>
  <ProgressBar :value="60" />
</template>

<script setup>
import { ProgressBar } from '@cambosoftware/cambo-admin'
</script>
```

## With Label

```vue
<template>
  <ProgressBar :value="75" show-label />
</template>
```

## Custom Label

```vue
<template>
  <ProgressBar :value="downloadProgress" show-label>
    <template #label>
      Downloading ({{ downloadedMB }}MB / {{ totalMB }}MB)
    </template>
  </ProgressBar>
</template>

<script setup>
const downloadProgress = 45
const downloadedMB = 45
const totalMB = 100
</script>
```

## Variants

```vue
<template>
  <div class="space-y-4">
    <ProgressBar :value="60" variant="primary" show-label />
    <ProgressBar :value="60" variant="success" show-label />
    <ProgressBar :value="60" variant="warning" show-label />
    <ProgressBar :value="60" variant="danger" show-label />
    <ProgressBar :value="60" variant="info" show-label />
  </div>
</template>
```

## Sizes

```vue
<template>
  <div class="space-y-4">
    <div>
      <p class="text-sm text-gray-500 mb-1">Extra Small</p>
      <ProgressBar :value="60" size="xs" />
    </div>

    <div>
      <p class="text-sm text-gray-500 mb-1">Small</p>
      <ProgressBar :value="60" size="sm" />
    </div>

    <div>
      <p class="text-sm text-gray-500 mb-1">Medium (default)</p>
      <ProgressBar :value="60" size="md" />
    </div>

    <div>
      <p class="text-sm text-gray-500 mb-1">Large</p>
      <ProgressBar :value="60" size="lg" />
    </div>
  </div>
</template>
```

## Striped

```vue
<template>
  <ProgressBar :value="70" striped />
</template>
```

## Animated Stripes

```vue
<template>
  <ProgressBar :value="70" striped animated />
</template>
```

## Indeterminate (Loading)

```vue
<template>
  <ProgressBar indeterminate />
</template>
```

## Dynamic Progress

```vue
<template>
  <div class="space-y-4">
    <ProgressBar :value="progress" show-label variant="primary" />

    <div class="flex gap-2">
      <Button size="sm" @click="progress = Math.max(0, progress - 10)">
        -10%
      </Button>
      <Button size="sm" @click="progress = Math.min(100, progress + 10)">
        +10%
      </Button>
      <Button size="sm" variant="secondary" @click="progress = 0">
        Reset
      </Button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const progress = ref(30)
</script>
```

## File Upload Progress

```vue
<template>
  <Card>
    <div class="flex items-center gap-4">
      <DocumentIcon class="h-10 w-10 text-gray-400" />
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-gray-900 truncate">
          {{ fileName }}
        </p>
        <ProgressBar
          :value="uploadProgress"
          size="sm"
          :variant="uploadComplete ? 'success' : 'primary'"
          class="mt-2"
        />
        <p class="text-xs text-gray-500 mt-1">
          {{ uploadComplete ? 'Complete' : `${uploadProgress}% uploaded` }}
        </p>
      </div>
      <Button
        v-if="!uploadComplete"
        size="sm"
        variant="ghost"
        @click="cancelUpload"
      >
        Cancel
      </Button>
      <CheckCircleIcon
        v-else
        class="h-5 w-5 text-green-500"
      />
    </div>
  </Card>
</template>

<script setup>
import { ref, computed } from 'vue'

const fileName = 'document.pdf'
const uploadProgress = ref(0)
const uploadComplete = computed(() => uploadProgress.value >= 100)

// Simulate upload
const interval = setInterval(() => {
  if (uploadProgress.value < 100) {
    uploadProgress.value += Math.random() * 15
    if (uploadProgress.value > 100) uploadProgress.value = 100
  } else {
    clearInterval(interval)
  }
}, 500)
</script>
```

## Step Progress

```vue
<template>
  <div class="space-y-2">
    <div class="flex justify-between text-sm">
      <span>Step {{ currentStep }} of {{ totalSteps }}</span>
      <span>{{ Math.round(stepProgress) }}%</span>
    </div>
    <ProgressBar :value="stepProgress" variant="primary" />
    <div class="flex justify-between">
      <Button
        size="sm"
        variant="secondary"
        :disabled="currentStep <= 1"
        @click="currentStep--"
      >
        Previous
      </Button>
      <Button
        size="sm"
        :disabled="currentStep >= totalSteps"
        @click="currentStep++"
      >
        Next
      </Button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const currentStep = ref(2)
const totalSteps = 5
const stepProgress = computed(() => (currentStep.value / totalSteps) * 100)
</script>
```

## Multiple Progress Bars

```vue
<template>
  <Card title="Storage Usage">
    <div class="space-y-4">
      <div>
        <div class="flex justify-between text-sm mb-1">
          <span>Documents</span>
          <span>2.5 GB / 5 GB</span>
        </div>
        <ProgressBar :value="50" variant="primary" size="sm" />
      </div>

      <div>
        <div class="flex justify-between text-sm mb-1">
          <span>Photos</span>
          <span>8 GB / 10 GB</span>
        </div>
        <ProgressBar :value="80" variant="warning" size="sm" />
      </div>

      <div>
        <div class="flex justify-between text-sm mb-1">
          <span>Videos</span>
          <span>4.5 GB / 5 GB</span>
        </div>
        <ProgressBar :value="90" variant="danger" size="sm" />
      </div>
    </div>
  </Card>
</template>
```

## Loading State with Indeterminate

```vue
<template>
  <div v-if="isLoading" class="text-center py-8">
    <ProgressBar indeterminate variant="primary" />
    <p class="text-sm text-gray-500 mt-2">Loading data...</p>
  </div>
  <div v-else>
    <!-- Content -->
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const isLoading = ref(true)

onMounted(() => {
  setTimeout(() => {
    isLoading.value = false
  }, 3000)
})
</script>
```

## Size Reference

| Size | Height | Use Case |
|------|--------|----------|
| `xs` | 4px (h-1) | Minimal indicators |
| `sm` | 6px (h-1.5) | Compact UI, lists |
| `md` | 10px (h-2.5) | Default, general use |
| `lg` | 16px (h-4) | Prominent displays |

## Playground

Try the progress bar component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <DemoProgressBar :value="25" />
    <DemoProgressBar :value="50" />
    <DemoProgressBar :value="75" />
    <DemoProgressBar :value="100" />
  </div>

  <template #code>

```vue
<ProgressBar :value="25" />
<ProgressBar :value="50" />
<ProgressBar :value="75" />
<ProgressBar :value="100" />
```

  </template>
</LiveDemo>

### Progress Bar Variants

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoProgressBar :value="60" variant="primary" />
    <DemoProgressBar :value="60" variant="success" />
    <DemoProgressBar :value="60" variant="warning" />
    <DemoProgressBar :value="60" variant="danger" />
  </div>

  <template #code>

```vue
<ProgressBar :value="60" variant="primary" />
<ProgressBar :value="60" variant="success" />
<ProgressBar :value="60" variant="warning" />
<ProgressBar :value="60" variant="danger" />
```

  </template>
</LiveDemo>

### Progress Bar Sizes

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoProgressBar :value="60" size="xs" />
    <DemoProgressBar :value="60" size="sm" />
    <DemoProgressBar :value="60" size="md" />
    <DemoProgressBar :value="60" size="lg" />
  </div>

  <template #code>

```vue
<ProgressBar :value="60" size="xs" />
<ProgressBar :value="60" size="sm" />
<ProgressBar :value="60" size="md" />
<ProgressBar :value="60" size="lg" />
```

  </template>
</LiveDemo>
