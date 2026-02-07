# Utilities

Helper components for common UI patterns.

## Components

| Component | Description |
|-----------|-------------|
| [CopyButton](./copy-button.md) | Copy to clipboard button |
| [ClickToCopy](./click-to-copy.md) | Click text to copy |
| [ExternalLink](./external-link.md) | Link with external icon |
| [Highlight](./highlight.md) | Text highlighting |
| [RelativeTime](./relative-time.md) | Relative time display |
| [CountUp](./count-up.md) | Animated number counter |
| [Kbd](./kbd.md) | Keyboard shortcut display |

## Usage

### Copy Button

```vue
<div class="flex items-center gap-2">
  <code>npm install cambo-admin</code>
  <CopyButton text="npm install cambo-admin" />
</div>
```

### Click to Copy

```vue
<ClickToCopy text="API_KEY_12345">
  API_KEY_12345
</ClickToCopy>
```

### Relative Time

```vue
<RelativeTime :date="post.created_at" />
<!-- Output: "2 hours ago" -->
```

### Count Up

```vue
<CountUp :value="12500" prefix="$" :duration="2000" />
<!-- Animates from 0 to $12,500 -->
```

### Keyboard Shortcuts

```vue
<p>Press <Kbd>Ctrl</Kbd> + <Kbd>K</Kbd> to open search</p>
```

### Highlight

```vue
<Highlight :text="result.title" :query="searchQuery" />
<!-- Highlights matching text -->
```
