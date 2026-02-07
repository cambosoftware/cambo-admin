# Feedback

Components for user feedback and status communication.

## Components

| Component | Description |
|-----------|-------------|
| [Alert](./alert.md) | Inline alert messages |
| [Toast](./toast.md) | Temporary notifications |
| [ProgressBar](./progress-bar.md) | Progress indicator |
| [EmptyState](./empty-state.md) | Empty content placeholder |
| [ErrorState](./error-state.md) | Error display |

## Usage

### Alerts

```vue
<Alert variant="success" title="Success!">
  Your changes have been saved.
</Alert>

<Alert variant="warning" dismissible>
  Please review your settings.
</Alert>
```

### Toasts

```vue
<script setup>
import { useToast } from '@/Composables/useToast'

const toast = useToast()

const save = () => {
  // ... save logic
  toast.success('Changes saved successfully!')
}
</script>
```

### Empty States

```vue
<EmptyState
  title="No results found"
  description="Try adjusting your search or filters."
  :icon="SearchIcon"
>
  <Button @click="clearFilters">Clear filters</Button>
</EmptyState>
```
