# Textarea

A multi-line text input component with support for resizing, character counting, and validation states.

## Import

```vue
<script setup>
import { Textarea } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String` | `''` | The textarea value (v-model) |
| `placeholder` | `String` | `null` | Placeholder text |
| `rows` | `Number` | `3` | Number of visible text rows |
| `size` | `String` | `'md'` | Textarea size. Allowed: `'sm'`, `'md'`, `'lg'` |
| `disabled` | `Boolean` | `false` | Disables the textarea |
| `readonly` | `Boolean` | `false` | Makes the textarea read-only |
| `error` | `String \| Boolean` | `null` | Error state or message. When truthy, shows error styling |
| `resize` | `String` | `'vertical'` | Resize behavior. Allowed: `'none'`, `'vertical'`, `'horizontal'`, `'both'` |
| `maxlength` | `Number` | `null` | Maximum number of characters allowed |
| `showCount` | `Boolean` | `false` | Shows character count below the textarea |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String` | Emitted when value changes |
| `focus` | `FocusEvent` | Emitted when textarea gains focus |
| `blur` | `FocusEvent` | Emitted when textarea loses focus |

## Exposed Methods

| Method | Description |
|--------|-------------|
| `focus()` | Programmatically focus the textarea |

## Basic Example

```vue
<script setup>
import { Textarea } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const description = ref('')
</script>

<template>
    <Textarea v-model="description" placeholder="Enter description..." />
</template>
```

## Rows Configuration

```vue
<!-- Small textarea -->
<Textarea v-model="value" :rows="2" placeholder="Short input..." />

<!-- Medium textarea (default) -->
<Textarea v-model="value" :rows="3" placeholder="Standard input..." />

<!-- Large textarea -->
<Textarea v-model="value" :rows="6" placeholder="Long content..." />

<!-- Very large textarea -->
<Textarea v-model="value" :rows="10" placeholder="Extended content..." />
```

## Sizes

```vue
<!-- Small -->
<Textarea v-model="value" size="sm" placeholder="Small textarea" />

<!-- Medium (default) -->
<Textarea v-model="value" size="md" placeholder="Medium textarea" />

<!-- Large -->
<Textarea v-model="value" size="lg" placeholder="Large textarea" />
```

## Resize Options

```vue
<!-- Vertical resize only (default) -->
<Textarea v-model="value" resize="vertical" placeholder="Resize vertically" />

<!-- Horizontal resize only -->
<Textarea v-model="value" resize="horizontal" placeholder="Resize horizontally" />

<!-- Both directions -->
<Textarea v-model="value" resize="both" placeholder="Resize any direction" />

<!-- No resize -->
<Textarea v-model="value" resize="none" placeholder="Fixed size" />
```

## Character Limit with Counter

```vue
<!-- With character limit and counter -->
<Textarea
    v-model="bio"
    :maxlength="200"
    show-count
    placeholder="Enter your bio (max 200 characters)"
/>

<!-- Counter without limit -->
<Textarea
    v-model="content"
    show-count
    placeholder="Character count shown below"
/>
```

## Error State

```vue
<script setup>
const errors = ref({ description: 'Description is required' })
</script>

<template>
    <FormGroup label="Description" :error="errors.description">
        <Textarea
            v-model="description"
            :error="errors.description"
            placeholder="Enter description"
        />
    </FormGroup>
</template>
```

## Disabled and Readonly

```vue
<!-- Disabled -->
<Textarea v-model="value" disabled placeholder="Disabled textarea" />

<!-- Readonly -->
<Textarea v-model="value" readonly placeholder="Read-only textarea" />
```

## Programmatic Focus

```vue
<script setup>
import { ref } from 'vue'
import { Textarea, Button } from '@cambosoftware/cambo-admin'

const textareaRef = ref(null)

const focusTextarea = () => {
    textareaRef.value?.focus()
}
</script>

<template>
    <Textarea ref="textareaRef" v-model="value" placeholder="Click button to focus" />
    <Button @click="focusTextarea">Focus Textarea</Button>
</template>
```

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, Textarea, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    title: '',
    summary: '',
    content: ''
})

const submit = (form) => {
    form.post('/articles')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Title" required :error="form.errors.title">
            <Input
                v-model="form.title"
                :error="form.errors.title"
                placeholder="Article title"
            />
        </FormGroup>

        <FormGroup
            label="Summary"
            :error="form.errors.summary"
            hint="Brief description of the article"
        >
            <Textarea
                v-model="form.summary"
                :rows="2"
                :maxlength="200"
                show-count
                :error="form.errors.summary"
                placeholder="Enter a short summary..."
            />
        </FormGroup>

        <FormGroup label="Content" required :error="form.errors.content">
            <Textarea
                v-model="form.content"
                :rows="10"
                show-count
                :error="form.errors.content"
                placeholder="Write your article content here..."
            />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Publish Article
        </Button>
    </Form>
</template>
```

## Use Cases

### Comments Section

```vue
<Textarea
    v-model="comment"
    :rows="3"
    :maxlength="500"
    show-count
    placeholder="Write a comment..."
    resize="none"
/>
```

### Code or JSON Input

```vue
<Textarea
    v-model="jsonData"
    :rows="8"
    resize="vertical"
    class="font-mono"
    placeholder='{"key": "value"}'
/>
```

### Notes Field

```vue
<Textarea
    v-model="notes"
    :rows="5"
    show-count
    placeholder="Add notes (optional)..."
/>
```

## Styling

The Textarea component includes:
- Rounded corners (`rounded-lg`)
- Border with focus ring
- Smooth color transitions
- Dark mode support
- Responsive sizing

### Size Classes

| Size | Padding | Font Size |
|------|---------|-----------|
| `sm` | `px-2.5 py-1.5` | `text-xs` |
| `md` | `px-3 py-2` | `text-sm` |
| `lg` | `px-4 py-2.5` | `text-base` |

### Resize Classes

| Resize | CSS Class |
|--------|-----------|
| `none` | `resize-none` |
| `vertical` | `resize-y` |
| `horizontal` | `resize-x` |
| `both` | `resize` |

## Accessibility

- Uses native `<textarea>` element for full browser support
- Supports all standard textarea attributes
- Focus states are clearly visible
- Error states use semantic red coloring
- Character count provides visual feedback
- Works with screen readers

## Playground

Try the textarea component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 20rem;">
    <DemoTextarea placeholder="Enter your message..." />
  </div>

  <template #code>

```vue
<Textarea v-model="message" placeholder="Enter your message..." />
```

  </template>
</LiveDemo>

### Textarea Sizes

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 20rem;">
    <DemoTextarea size="sm" placeholder="Small textarea" :rows="2" />
    <DemoTextarea size="md" placeholder="Medium textarea" :rows="3" />
    <DemoTextarea size="lg" placeholder="Large textarea" :rows="4" />
  </div>

  <template #code>

```vue
<Textarea size="sm" placeholder="Small textarea" :rows="2" />
<Textarea size="md" placeholder="Medium textarea" :rows="3" />
<Textarea size="lg" placeholder="Large textarea" :rows="4" />
```

  </template>
</LiveDemo>

### Textarea States

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 20rem;">
    <DemoTextarea placeholder="Normal textarea" />
    <DemoTextarea placeholder="Disabled textarea" disabled />
    <DemoTextarea placeholder="Error state" error />
  </div>

  <template #code>

```vue
<Textarea placeholder="Normal textarea" />
<Textarea placeholder="Disabled textarea" disabled />
<Textarea placeholder="Error state" error />
```

  </template>
</LiveDemo>
