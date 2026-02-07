# CodeEditor

A code editor component with line numbers, syntax highlighting theme, and developer-friendly features.

## Import

```vue
<script setup>
import { CodeEditor } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String` | `''` | The code content (v-model) |
| `language` | `String` | `'javascript'` | Programming language label |
| `placeholder` | `String` | `'// Write your code here...'` | Placeholder text |
| `disabled` | `Boolean` | `false` | Disable the editor |
| `readonly` | `Boolean` | `false` | Make editor read-only |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `rows` | `Number` | `10` | Number of visible rows |
| `showLineNumbers` | `Boolean` | `true` | Show line numbers |
| `tabSize` | `Number` | `2` | Number of spaces for tab |

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
  <CodeEditor v-model="code" />
</template>

<script setup>
import { ref } from 'vue'
const code = ref('')
</script>
```

### With Initial Code

```vue
<template>
  <CodeEditor v-model="code" />
</template>

<script setup>
import { ref } from 'vue'
const code = ref(`function greet(name) {
  return \`Hello, \${name}!\`;
}

console.log(greet('World'));`)
</script>
```

### Different Languages

```vue
<template>
  <div class="space-y-4">
    <CodeEditor v-model="jsCode" language="javascript" />
    <CodeEditor v-model="pyCode" language="python" />
    <CodeEditor v-model="htmlCode" language="html" />
    <CodeEditor v-model="cssCode" language="css" />
    <CodeEditor v-model="sqlCode" language="sql" />
    <CodeEditor v-model="jsonCode" language="json" />
  </div>
</template>
```

### Custom Tab Size

```vue
<template>
  <div class="space-y-4">
    <!-- 2 spaces (default) -->
    <CodeEditor v-model="code" :tab-size="2" />

    <!-- 4 spaces -->
    <CodeEditor v-model="code" :tab-size="4" />

    <!-- Tab character -->
    <CodeEditor v-model="code" :tab-size="1" />
  </div>
</template>
```

### Without Line Numbers

```vue
<template>
  <CodeEditor
    v-model="code"
    :show-line-numbers="false"
  />
</template>
```

### Custom Rows

```vue
<template>
  <CodeEditor
    v-model="code"
    :rows="20"
    placeholder="// Large code area..."
  />
</template>
```

### Read-only Mode

```vue
<template>
  <CodeEditor
    v-model="code"
    readonly
    language="json"
  />
</template>

<script setup>
import { ref } from 'vue'
const code = ref(JSON.stringify({ name: 'Example', value: 42 }, null, 2))
</script>
```

### With Error State

```vue
<template>
  <CodeEditor
    v-model="code"
    :error="syntaxError"
  />
</template>

<script setup>
import { ref, computed } from 'vue'

const code = ref('')

const syntaxError = computed(() => {
  try {
    if (code.value) JSON.parse(code.value)
    return false
  } catch (e) {
    return e.message
  }
})
</script>
```

### Disabled State

```vue
<template>
  <CodeEditor
    v-model="code"
    disabled
  />
</template>
```

### In a Form (Configuration)

```vue
<template>
  <form @submit.prevent="saveConfig">
    <FormGroup label="Configuration (JSON)" required>
      <CodeEditor
        v-model="form.config"
        language="json"
        :rows="15"
        :error="errors.config"
      />
    </FormGroup>

    <button type="submit" class="btn btn-primary">
      Save Configuration
    </button>
  </form>
</template>
```

### API Response Viewer

```vue
<template>
  <div class="space-y-4">
    <div class="flex gap-2">
      <Input v-model="endpoint" placeholder="API endpoint" class="flex-1" />
      <button @click="fetchData" class="btn btn-primary">Fetch</button>
    </div>

    <CodeEditor
      v-model="response"
      language="json"
      readonly
      :rows="20"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'

const endpoint = ref('')
const response = ref('')

const fetchData = async () => {
  const res = await fetch(endpoint.value)
  const data = await res.json()
  response.value = JSON.stringify(data, null, 2)
}
</script>
```

### Code Snippet Editor

```vue
<template>
  <div class="space-y-4">
    <Select v-model="language" :options="languages" />

    <CodeEditor
      v-model="snippet"
      :language="language"
      :rows="15"
    />

    <div class="flex gap-2">
      <button @click="copy" class="btn btn-secondary">Copy</button>
      <button @click="run" class="btn btn-primary">Run</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const language = ref('javascript')
const languages = ['javascript', 'typescript', 'python', 'html', 'css', 'json']
const snippet = ref('')

const copy = () => navigator.clipboard.writeText(snippet.value)
const run = () => {
  // Execute code...
}
</script>
```

## Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| `Tab` | Insert spaces (based on tabSize) |
| `Shift+Tab` | Remove indentation |
| `Enter` | New line with auto-indent |
| `(`, `[`, `{`, `"`, `'`, `` ` `` | Auto-close brackets/quotes |

## Features

- Dark theme styling (code editor aesthetic)
- Line numbers column
- Language label badge
- Line count display
- Tab key support with configurable size
- Shift+Tab to unindent
- Auto-close brackets and quotes
- Auto-indent on Enter
- Monospace font
- Scrollable content area
- No spellcheck interference
- Focus ring styling

## Playground

Try the CodeEditor component:

<LiveDemo>
  <DemoCodeEditor />

  <template #code>

```vue
<script setup>
import { CodeEditor } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const code = ref(`function greet(name) {
  return \`Hello, \${name}!\`;
}

console.log(greet('World'));`)
</script>

<template>
    <CodeEditor
        v-model="code"
        language="javascript"
        :rows="10"
    />
</template>
```

  </template>
</LiveDemo>
