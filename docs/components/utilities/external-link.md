# ExternalLink

A link component that safely opens external URLs in a new tab.

## Import

```vue
<script setup>
import ExternalLink from '@/Components/Utilities/ExternalLink.vue'
</script>
```

## Basic Usage

```vue
<template>
  <ExternalLink href="https://example.com">
    Visit Example
  </ExternalLink>
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `href` | `string` | `''` | URL to open |
| `showIcon` | `boolean` | `true` | Show external link icon |
| `iconPosition` | `string` | `'right'` | Icon position: `left`, `right` |
| `underline` | `boolean` | `true` | Underline on hover |

## Security

The component automatically adds:

- `target="_blank"` - Opens in new tab
- `rel="noopener noreferrer"` - Security protection

## Examples

### Basic Link

```vue
<ExternalLink href="https://github.com/cambosoftware/cambo-admin">
  View on GitHub
</ExternalLink>
```

### Without Icon

```vue
<ExternalLink href="https://example.com" :show-icon="false">
  Simple Link
</ExternalLink>
```

### Icon on Left

```vue
<ExternalLink href="https://docs.example.com" icon-position="left">
  Documentation
</ExternalLink>
```

### In a Sentence

```vue
<p>
  For more information, visit the
  <ExternalLink href="https://docs.example.com">
    official documentation
  </ExternalLink>.
</p>
```

### Styled Link

```vue
<ExternalLink
  href="https://example.com"
  class="text-indigo-600 font-medium"
>
  Learn More
</ExternalLink>
```

## Accessibility

- Uses proper `<a>` element
- Indicates external link to screen readers
- Visible icon provides visual cue

## Playground

Try the ExternalLink component:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 8px;">
    <a href="https://github.com" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 4px; color: #4f46e5; text-decoration: none; transition: text-decoration 0.15s;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
      View on GitHub
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 14px; height: 14px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
      </svg>
    </a>
    <a href="https://vuejs.org" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 4px; color: #4f46e5; text-decoration: none; transition: text-decoration 0.15s;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
      Vue.js Documentation
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 14px; height: 14px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
      </svg>
    </a>
  </div>

  <template #code>

```vue
<template>
  <div class="space-y-2">
    <ExternalLink href="https://github.com/cambosoftware/cambo-admin">
      View on GitHub
    </ExternalLink>

    <ExternalLink href="https://vuejs.org/guide">
      Vue.js Documentation
    </ExternalLink>
  </div>
</template>

<script setup>
import ExternalLink from '@/Components/Utilities/ExternalLink.vue'
</script>
```

  </template>
</LiveDemo>
