# MarkdownEditor

A Markdown editor with live preview, toolbar assistance, and syntax highlighting.

## Import

```vue
<script setup>
import { MarkdownEditor } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String` | `''` | The Markdown content (v-model) |
| `placeholder` | `String` | `'Write in Markdown...'` | Placeholder text |
| `disabled` | `Boolean` | `false` | Disable the editor |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `rows` | `Number` | `10` | Number of visible rows |
| `preview` | `Boolean` | `true` | Show write/preview tabs |
| `toolbar` | `Array` | `[see below]` | Array of toolbar buttons |

## Default Toolbar

```javascript
['bold', 'italic', 'strike', '|', 'h1', 'h2', 'h3', '|', 'ul', 'ol', 'task', '|', 'link', 'image', 'code', 'quote', '|', 'hr']
```

### Available Toolbar Buttons

| Button | Description | Markdown Syntax |
|--------|-------------|-----------------|
| `bold` | Bold text | `**text**` |
| `italic` | Italic text | `_text_` |
| `strike` | Strikethrough | `~~text~~` |
| `h1` | Heading 1 | `# text` |
| `h2` | Heading 2 | `## text` |
| `h3` | Heading 3 | `### text` |
| `ul` | Unordered list | `- item` |
| `ol` | Ordered list | `1. item` |
| `task` | Task list | `- [ ] task` |
| `link` | Link | `[text](url)` |
| `image` | Image | `![alt](url)` |
| `code` | Code block | ``` `code` ``` |
| `quote` | Block quote | `> quote` |
| `hr` | Horizontal rule | `---` |
| `\|` | Separator | - |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String` | Emitted when content changes (for v-model) |
| `change` | `String` | Emitted when content changes |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <MarkdownEditor v-model="content" />
</template>

<script setup>
import { ref } from 'vue'
const content = ref('')
</script>
```

### With Initial Content

```vue
<template>
  <MarkdownEditor v-model="content" />
</template>

<script setup>
import { ref } from 'vue'
const content = ref(`# Hello World

This is a **markdown** document.

## Features

- Easy to write
- Live preview
- Toolbar assistance
`)
</script>
```

### Custom Toolbar

```vue
<template>
  <MarkdownEditor
    v-model="content"
    :toolbar="['bold', 'italic', '|', 'link', 'image', 'code']"
  />
</template>
```

### Minimal Toolbar

```vue
<template>
  <MarkdownEditor
    v-model="content"
    :toolbar="['bold', 'italic', 'link']"
    placeholder="Simple markdown..."
  />
</template>
```

### Without Preview Tab

```vue
<template>
  <MarkdownEditor
    v-model="content"
    :preview="false"
  />
</template>
```

### Custom Rows

```vue
<template>
  <MarkdownEditor
    v-model="content"
    :rows="20"
    placeholder="Long form content..."
  />
</template>
```

### With Error State

```vue
<template>
  <MarkdownEditor
    v-model="content"
    :error="!content ? 'Content is required' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <MarkdownEditor
    v-model="content"
    disabled
  />
</template>
```

### In a Form (Documentation)

```vue
<template>
  <form @submit.prevent="saveDocs">
    <FormGroup label="Title" required>
      <Input v-model="form.title" :error="errors.title" />
    </FormGroup>

    <FormGroup label="Content" required>
      <MarkdownEditor
        v-model="form.content"
        :rows="15"
        :error="errors.content"
      />
    </FormGroup>

    <button type="submit" class="btn btn-primary">
      Save Documentation
    </button>
  </form>
</template>
```

### README Editor

```vue
<template>
  <MarkdownEditor
    v-model="readme"
    :toolbar="[
      'bold', 'italic', 'strike', '|',
      'h1', 'h2', 'h3', '|',
      'ul', 'ol', 'task', '|',
      'link', 'image', 'code', 'quote', '|',
      'hr'
    ]"
    :rows="25"
    placeholder="# Project Name

Short description of the project..."
  />
</template>
```

### Comments with Markdown

```vue
<template>
  <MarkdownEditor
    v-model="comment"
    :toolbar="['bold', 'italic', '|', 'code', 'link']"
    :rows="5"
    :preview="true"
    placeholder="Write a comment... Markdown supported"
  />
</template>
```

### Task List Editor

```vue
<template>
  <MarkdownEditor
    v-model="tasks"
    :toolbar="['task', '|', 'bold', 'italic']"
    :rows="8"
    placeholder="- [ ] Task 1
- [ ] Task 2
- [x] Completed task"
  />
</template>
```

## Supported Markdown Syntax

The preview supports the following Markdown elements:

| Element | Syntax |
|---------|--------|
| Bold | `**text**` |
| Italic | `_text_` |
| Strikethrough | `~~text~~` |
| Headings | `# H1`, `## H2`, `### H3` |
| Links | `[text](url)` |
| Images | `![alt](url)` |
| Inline code | `` `code` `` |
| Code blocks | ``` ```code``` ``` |
| Unordered lists | `- item` |
| Ordered lists | `1. item` |
| Task lists | `- [ ] task`, `- [x] done` |
| Block quotes | `> quote` |
| Horizontal rule | `---` |

## Features

- Write/Preview tab switching
- Monospace font for editing
- Toolbar for quick formatting
- Smart text insertion at cursor
- Line-level formatting for headings/lists
- Live Markdown to HTML preview
- Checkbox rendering in task lists
- Syntax highlighting in preview
- Configurable row height
- Keyboard-friendly editing

## Playground

Try the MarkdownEditor component:

<LiveDemo>
  <DemoMarkdownEditor />

  <template #code>

```vue
<script setup>
import { MarkdownEditor } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const content = ref(`# Hello World

This is a **markdown** document.

## Features

- Easy to write
- Live preview
- Toolbar assistance
`)
</script>

<template>
    <MarkdownEditor
        v-model="content"
        :rows="10"
        placeholder="Write in Markdown..."
    />
</template>
```

  </template>
</LiveDemo>
