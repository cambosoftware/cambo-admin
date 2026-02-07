# Button

A versatile button component with multiple variants, sizes, and states. Supports icons, loading states, and can render as either a button or an Inertia Link.

## Import

```vue
<script setup>
import Button from '@/Components/UI/Button.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `String` | `'primary'` | Visual style variant. Options: `'primary'`, `'secondary'`, `'success'`, `'danger'`, `'warning'`, `'info'`, `'ghost'`, `'link'` |
| `size` | `String` | `'md'` | Button size. Options: `'xs'`, `'sm'`, `'md'`, `'lg'` |
| `outline` | `Boolean` | `false` | Renders button with outline style instead of solid |
| `block` | `Boolean` | `false` | Makes button full width |
| `pill` | `Boolean` | `false` | Renders button with fully rounded corners |
| `loading` | `Boolean` | `false` | Shows loading spinner and disables button |
| `disabled` | `Boolean` | `false` | Disables the button |
| `icon` | `Object \| Function` | `null` | Icon component to display on the left side |
| `iconRight` | `Object \| Function` | `null` | Icon component to display on the right side |
| `href` | `String` | `null` | When provided, renders as an Inertia Link instead of button |
| `type` | `String` | `'button'` | HTML button type attribute |

## Events

| Name | Payload | Description |
|------|---------|-------------|
| `click` | `MouseEvent` | Emitted when the button is clicked |

## Slots

| Name | Description |
|------|-------------|
| `default` | Button content/label |

## Basic Usage

```vue
<template>
    <Button>Default Button</Button>
    <Button variant="primary">Primary</Button>
    <Button variant="secondary">Secondary</Button>
</template>
```

## Variants

### Solid Variants

```vue
<template>
    <div class="flex flex-wrap gap-2">
        <Button variant="primary">Primary</Button>
        <Button variant="secondary">Secondary</Button>
        <Button variant="success">Success</Button>
        <Button variant="danger">Danger</Button>
        <Button variant="warning">Warning</Button>
        <Button variant="info">Info</Button>
        <Button variant="ghost">Ghost</Button>
        <Button variant="link">Link</Button>
    </div>
</template>
```

### Outline Variants

```vue
<template>
    <div class="flex flex-wrap gap-2">
        <Button variant="primary" outline>Primary</Button>
        <Button variant="secondary" outline>Secondary</Button>
        <Button variant="success" outline>Success</Button>
        <Button variant="danger" outline>Danger</Button>
        <Button variant="warning" outline>Warning</Button>
        <Button variant="info" outline>Info</Button>
    </div>
</template>
```

## Sizes

```vue
<template>
    <div class="flex items-center gap-2">
        <Button size="xs">Extra Small</Button>
        <Button size="sm">Small</Button>
        <Button size="md">Medium</Button>
        <Button size="lg">Large</Button>
    </div>
</template>
```

## With Icons

```vue
<script setup>
import { PlusIcon, ArrowRightIcon, TrashIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <!-- Left icon -->
    <Button :icon="PlusIcon">
        Add Item
    </Button>

    <!-- Right icon -->
    <Button :icon-right="ArrowRightIcon">
        Continue
    </Button>

    <!-- Both icons -->
    <Button :icon="TrashIcon" :icon-right="ArrowRightIcon" variant="danger">
        Delete and Continue
    </Button>
</template>
```

## Loading State

```vue
<template>
    <Button loading>
        Saving...
    </Button>

    <Button loading variant="success">
        Processing
    </Button>
</template>
```

## Disabled State

```vue
<template>
    <Button disabled>Disabled Button</Button>
    <Button disabled variant="secondary">Disabled Secondary</Button>
</template>
```

## Pill Shape

```vue
<template>
    <Button pill>Rounded Button</Button>
    <Button pill variant="success">Success Pill</Button>
</template>
```

## Full Width

```vue
<template>
    <Button block>Full Width Button</Button>
</template>
```

## As Link

When `href` is provided, the button renders as an Inertia Link for SPA navigation:

```vue
<template>
    <Button href="/dashboard">Go to Dashboard</Button>
    <Button href="/users" variant="secondary">View Users</Button>
</template>
```

## Button Types

```vue
<template>
    <form @submit.prevent="handleSubmit">
        <Button type="submit" variant="primary">Submit Form</Button>
        <Button type="reset" variant="secondary">Reset</Button>
        <Button type="button">Regular Button</Button>
    </form>
</template>
```

## Combined Examples

```vue
<script setup>
import { PlusIcon, CheckIcon } from '@heroicons/vue/24/outline'

const isLoading = ref(false)

const handleSave = async () => {
    isLoading.value = true
    await saveData()
    isLoading.value = false
}
</script>

<template>
    <!-- Action button with loading state -->
    <Button
        variant="primary"
        :icon="CheckIcon"
        :loading="isLoading"
        @click="handleSave"
    >
        Save Changes
    </Button>

    <!-- Danger outline pill button -->
    <Button
        variant="danger"
        outline
        pill
        :icon="TrashIcon"
    >
        Delete Account
    </Button>

    <!-- Full width submit button -->
    <Button
        type="submit"
        variant="success"
        block
        size="lg"
    >
        Complete Registration
    </Button>
</template>
```

## Dark Mode

The Button component automatically adapts to dark mode with appropriate color adjustments:

```vue
<template>
    <!-- These buttons will have different colors in dark mode -->
    <div class="dark:bg-gray-800 p-4">
        <Button variant="primary">Primary in Dark</Button>
        <Button variant="secondary">Secondary in Dark</Button>
    </div>
</template>
```

## Accessibility

- Buttons have proper focus indicators with ring styles
- Loading state disables interaction and shows visual feedback
- Disabled buttons have reduced opacity and prevent pointer events
- When used as links, proper navigation behavior is maintained

## Playground

Try the button component with different props:

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <DemoButton variant="primary">Primary</DemoButton>
    <DemoButton variant="secondary">Secondary</DemoButton>
    <DemoButton variant="success">Success</DemoButton>
    <DemoButton variant="danger">Danger</DemoButton>
    <DemoButton variant="warning">Warning</DemoButton>
    <DemoButton variant="ghost">Ghost</DemoButton>
  </div>

  <template #code>

```vue
<Button variant="primary">Primary</Button>
<Button variant="secondary">Secondary</Button>
<Button variant="success">Success</Button>
<Button variant="danger">Danger</Button>
<Button variant="warning">Warning</Button>
<Button variant="ghost">Ghost</Button>
```

  </template>
</LiveDemo>

### Outline Buttons

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <DemoButton variant="primary" outline>Primary</DemoButton>
    <DemoButton variant="secondary" outline>Secondary</DemoButton>
    <DemoButton variant="success" outline>Success</DemoButton>
    <DemoButton variant="danger" outline>Danger</DemoButton>
  </div>

  <template #code>

```vue
<Button variant="primary" outline>Primary</Button>
<Button variant="secondary" outline>Secondary</Button>
<Button variant="success" outline>Success</Button>
<Button variant="danger" outline>Danger</Button>
```

  </template>
</LiveDemo>

### Button Sizes

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
    <DemoButton size="xs">Extra Small</DemoButton>
    <DemoButton size="sm">Small</DemoButton>
    <DemoButton size="md">Medium</DemoButton>
    <DemoButton size="lg">Large</DemoButton>
  </div>

  <template #code>

```vue
<Button size="xs">Extra Small</Button>
<Button size="sm">Small</Button>
<Button size="md">Medium</Button>
<Button size="lg">Large</Button>
```

  </template>
</LiveDemo>

### Loading and Disabled States

<LiveDemo>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <DemoButton loading>Loading...</DemoButton>
    <DemoButton disabled>Disabled</DemoButton>
    <DemoButton pill>Pill Button</DemoButton>
  </div>

  <template #code>

```vue
<Button loading>Loading...</Button>
<Button disabled>Disabled</Button>
<Button pill>Pill Button</Button>
```

  </template>
</LiveDemo>
