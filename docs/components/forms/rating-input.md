# RatingInput

A star rating input component with customizable icons, colors, and half-star support.

## Import

```vue
<script setup>
import { RatingInput } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Number` | `0` | The selected rating (v-model) |
| `max` | `Number` | `5` | Maximum rating value |
| `size` | `String` | `'md'` | Icon size: `'sm'`, `'md'`, `'lg'`, or `'xl'` |
| `disabled` | `Boolean` | `false` | Disable interaction |
| `readonly` | `Boolean` | `false` | Make read-only (display only) |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `color` | `String` | `'yellow'` | Color: `'yellow'`, `'orange'`, `'red'`, or `'primary'` |
| `allowHalf` | `Boolean` | `false` | Allow half-star ratings |
| `showValue` | `Boolean` | `false` | Show numeric value next to stars |
| `clearable` | `Boolean` | `false` | Allow clearing by clicking same rating |
| `icon` | `Object \| Function` | `null` | Custom filled icon component |
| `iconOutline` | `Object \| Function` | `null` | Custom outline icon component |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Number` | Emitted when rating changes (for v-model) |
| `change` | `Number` | Emitted when rating changes |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <RatingInput v-model="rating" />
</template>

<script setup>
import { ref } from 'vue'
const rating = ref(0)
</script>
```

### With Initial Value

```vue
<template>
  <RatingInput v-model="rating" />
</template>

<script setup>
import { ref } from 'vue'
const rating = ref(4)
</script>
```

### With Value Display

```vue
<template>
  <RatingInput v-model="rating" show-value />
</template>
```

### Half Star Ratings

```vue
<template>
  <RatingInput v-model="rating" allow-half show-value />
</template>

<script setup>
import { ref } from 'vue'
const rating = ref(3.5)
</script>
```

### Clearable

```vue
<template>
  <RatingInput v-model="rating" clearable />
</template>
```

### Custom Max Rating

```vue
<template>
  <div class="space-y-4">
    <!-- 3 stars -->
    <RatingInput v-model="rating1" :max="3" />

    <!-- 10 stars -->
    <RatingInput v-model="rating2" :max="10" />
  </div>
</template>
```

### Different Colors

```vue
<template>
  <div class="space-y-4">
    <RatingInput v-model="rating" color="yellow" />
    <RatingInput v-model="rating" color="orange" />
    <RatingInput v-model="rating" color="red" />
    <RatingInput v-model="rating" color="primary" />
  </div>
</template>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <RatingInput v-model="rating" size="sm" />
    <RatingInput v-model="rating" size="md" />
    <RatingInput v-model="rating" size="lg" />
    <RatingInput v-model="rating" size="xl" />
  </div>
</template>
```

### Read-only (Display Only)

```vue
<template>
  <RatingInput v-model="rating" readonly show-value />
</template>

<script setup>
import { ref } from 'vue'
const rating = ref(4.5)
</script>
```

### With Error State

```vue
<template>
  <RatingInput
    v-model="rating"
    :error="rating === 0 ? 'Please provide a rating' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <RatingInput v-model="rating" disabled />
</template>
```

### Custom Icons

```vue
<template>
  <RatingInput
    v-model="rating"
    :icon="HeartIcon"
    :icon-outline="HeartOutlineIcon"
    color="red"
  />
</template>

<script setup>
import { ref } from 'vue'
import { HeartIcon } from '@heroicons/vue/24/solid'
import { HeartIcon as HeartOutlineIcon } from '@heroicons/vue/24/outline'

const rating = ref(3)
</script>
```

### In a Form (Product Review)

```vue
<template>
  <form @submit.prevent="submitReview">
    <FormGroup label="Rating" required>
      <RatingInput
        v-model="review.rating"
        allow-half
        show-value
        :error="errors.rating"
      />
    </FormGroup>

    <FormGroup label="Review">
      <Textarea v-model="review.comment" :rows="4" />
    </FormGroup>

    <button type="submit" class="btn btn-primary">
      Submit Review
    </button>
  </form>
</template>

<script setup>
import { ref } from 'vue'

const review = ref({
  rating: 0,
  comment: ''
})

const errors = ref({})

const submitReview = () => {
  if (review.value.rating === 0) {
    errors.value.rating = 'Please select a rating'
    return
  }
  // Submit review...
}
</script>
```

### Satisfaction Survey

```vue
<template>
  <div class="space-y-6">
    <FormGroup label="How would you rate our service?">
      <RatingInput v-model="survey.service" show-value />
    </FormGroup>

    <FormGroup label="How easy was it to use?">
      <RatingInput v-model="survey.usability" show-value />
    </FormGroup>

    <FormGroup label="Would you recommend us?">
      <RatingInput
        v-model="survey.recommend"
        :max="10"
        size="sm"
        show-value
      />
    </FormGroup>
  </div>
</template>
```

### Product Rating Display

```vue
<template>
  <div class="flex items-center gap-4">
    <RatingInput
      :model-value="product.averageRating"
      readonly
      allow-half
      size="sm"
    />
    <span class="text-sm text-gray-600">
      {{ product.averageRating.toFixed(1) }} ({{ product.reviewCount }} reviews)
    </span>
  </div>
</template>
```

### Interactive Feedback

```vue
<template>
  <div class="text-center space-y-4">
    <p class="text-lg">How was your experience?</p>

    <RatingInput
      v-model="feedback"
      size="xl"
      color="yellow"
    />

    <p class="text-sm text-gray-600">
      {{ feedbackText }}
    </p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const feedback = ref(0)

const feedbackText = computed(() => {
  const texts = [
    'Select a rating',
    'Poor - Very unsatisfied',
    'Fair - Could be better',
    'Good - Satisfied',
    'Very Good - Happy',
    'Excellent - Loved it!'
  ]
  return texts[feedback.value]
})
</script>
```

## Keyboard Navigation

| Key | Action |
|-----|--------|
| `ArrowRight` / `ArrowUp` | Increase rating |
| `ArrowLeft` / `ArrowDown` | Decrease rating |

## Features

- Star-based rating display
- Full and half-star support
- Hover preview effect
- Custom icon support
- Multiple color themes
- Four size variants
- Clearable option (click same value to clear)
- Read-only mode for display
- Value display option
- Keyboard navigation with arrow keys
- Mouse position detection for half-stars
- ARIA slider role for accessibility

## Playground

Try the RatingInput component:

<LiveDemo>
  <DemoRatingInput />

  <template #code>

```vue
<script setup>
import { RatingInput } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const rating = ref(3)
</script>

<template>
    <RatingInput
        v-model="rating"
        :max="5"
        show-value
        allow-half
    />
</template>
```

  </template>
</LiveDemo>
