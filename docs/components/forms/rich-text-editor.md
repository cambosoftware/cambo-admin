# RichTextEditor

A WYSIWYG rich text editor with customizable toolbar for formatting text content.

## Import

```vue
<script setup>
import { RichTextEditor } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String` | `''` | The HTML content (v-model) |
| `placeholder` | `String` | `'Enter your text...'` | Placeholder text |
| `disabled` | `Boolean` | `false` | Disable the editor |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `minHeight` | `String` | `'200px'` | Minimum height of the editor |
| `maxHeight` | `String` | `'500px'` | Maximum height of the editor |
| `toolbar` | `Array` | `[see below]` | Array of toolbar buttons |

## Default Toolbar

```javascript
['bold', 'italic', 'underline', 'strike', '|', 'h1', 'h2', 'h3', '|', 'ul', 'ol', '|', 'link', 'quote', '|', 'undo', 'redo']
```

### Available Toolbar Buttons

| Button | Description |
|--------|-------------|
| `bold` | Bold text |
| `italic` | Italic text |
| `underline` | Underlined text |
| `strike` | Strikethrough text |
| `h1` | Heading 1 |
| `h2` | Heading 2 |
| `h3` | Heading 3 |
| `ul` | Unordered list |
| `ol` | Ordered list |
| `link` | Insert link |
| `quote` | Block quote |
| `undo` | Undo last action |
| `redo` | Redo last action |
| `\|` | Separator |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String` | Emitted when content changes (for v-model) |
| `change` | `String` | Emitted when content changes |
| `focus` | `FocusEvent` | Emitted when editor gains focus |
| `blur` | `FocusEvent` | Emitted when editor loses focus |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <RichTextEditor v-model="content" />
</template>

<script setup>
import { ref } from 'vue'
const content = ref('')
</script>
```

### With Initial Content

```vue
<template>
  <RichTextEditor v-model="content" />
</template>

<script setup>
import { ref } from 'vue'
const content = ref('<p>Hello <strong>World</strong>!</p>')
</script>
```

### Custom Toolbar

```vue
<template>
  <RichTextEditor
    v-model="content"
    :toolbar="['bold', 'italic', '|', 'ul', 'ol', '|', 'link']"
  />
</template>
```

### Minimal Toolbar

```vue
<template>
  <RichTextEditor
    v-model="content"
    :toolbar="['bold', 'italic', 'underline']"
    placeholder="Simple formatting only..."
  />
</template>
```

### Full Featured Toolbar

```vue
<template>
  <RichTextEditor
    v-model="content"
    :toolbar="[
      'bold', 'italic', 'underline', 'strike', '|',
      'h1', 'h2', 'h3', '|',
      'ul', 'ol', '|',
      'link', 'quote', '|',
      'undo', 'redo'
    ]"
  />
</template>
```

### Custom Height

```vue
<template>
  <RichTextEditor
    v-model="content"
    min-height="300px"
    max-height="600px"
  />
</template>
```

### With Error State

```vue
<template>
  <RichTextEditor
    v-model="content"
    :error="!content ? 'Content is required' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <RichTextEditor
    v-model="content"
    disabled
  />
</template>
```

### In a Form (Blog Post)

```vue
<template>
  <form @submit.prevent="savePost">
    <FormGroup label="Title" required>
      <Input v-model="form.title" :error="errors.title" />
    </FormGroup>

    <FormGroup label="Content" required>
      <RichTextEditor
        v-model="form.content"
        min-height="400px"
        :error="errors.content"
        @focus="errors.content = null"
      />
    </FormGroup>

    <button type="submit" class="btn btn-primary">
      Publish
    </button>
  </form>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  title: '',
  content: ''
})

const errors = ref({})

const savePost = () => {
  if (!form.value.content) {
    errors.value.content = 'Content is required'
    return
  }
  // Save post...
}
</script>
```

### Email Composer

```vue
<template>
  <div class="space-y-4">
    <Input v-model="email.to" placeholder="To" />
    <Input v-model="email.subject" placeholder="Subject" />
    <RichTextEditor
      v-model="email.body"
      :toolbar="['bold', 'italic', 'underline', '|', 'ul', 'ol', '|', 'link']"
      min-height="250px"
      placeholder="Compose your email..."
    />
    <button class="btn btn-primary">Send</button>
  </div>
</template>
```

### Comments Editor

```vue
<template>
  <RichTextEditor
    v-model="comment"
    :toolbar="['bold', 'italic', '|', 'link']"
    min-height="100px"
    max-height="200px"
    placeholder="Write a comment..."
  />
</template>
```

## Features

- WYSIWYG content editing
- Customizable toolbar
- Text formatting (bold, italic, underline, strikethrough)
- Headings (H1, H2, H3)
- Lists (ordered and unordered)
- Links with URL prompt
- Block quotes
- Undo/redo support
- Paste as plain text (strips HTML)
- Placeholder support
- Min/max height constraints
- Focus/blur events
- Content-editable based implementation

## Playground

Try the RichTextEditor component:

<LiveDemo>
  <DemoRichTextEditor />

  <template #code>

```vue
<script setup>
import { RichTextEditor } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const content = ref('<p>Hello <strong>World</strong>!</p>')
</script>

<template>
    <RichTextEditor
        v-model="content"
        placeholder="Enter your text..."
        min-height="200px"
    />
</template>
```

  </template>
</LiveDemo>
