# ButtonGroup

Groups multiple buttons together horizontally or vertically with connected borders and proper spacing.

## Import

```vue
<script setup>
import ButtonGroup from '@/Components/UI/ButtonGroup.vue'
import Button from '@/Components/UI/Button.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `vertical` | `Boolean` | `false` | When true, arranges buttons vertically instead of horizontally |

## Slots

| Name | Description |
|------|-------------|
| `default` | Button components to be grouped |

## Basic Usage

```vue
<template>
    <ButtonGroup>
        <Button variant="secondary">Left</Button>
        <Button variant="secondary">Middle</Button>
        <Button variant="secondary">Right</Button>
    </ButtonGroup>
</template>
```

## Horizontal Group (Default)

Buttons are arranged side by side with connected borders:

```vue
<template>
    <ButtonGroup>
        <Button variant="primary">Save</Button>
        <Button variant="primary">Save & Close</Button>
        <Button variant="primary">Cancel</Button>
    </ButtonGroup>
</template>
```

## Vertical Group

Use `vertical` prop to stack buttons vertically:

```vue
<template>
    <ButtonGroup vertical>
        <Button variant="secondary">Profile</Button>
        <Button variant="secondary">Settings</Button>
        <Button variant="secondary">Logout</Button>
    </ButtonGroup>
</template>
```

## With Different Variants

```vue
<template>
    <!-- Outline buttons -->
    <ButtonGroup>
        <Button variant="secondary" outline>Day</Button>
        <Button variant="secondary" outline>Week</Button>
        <Button variant="secondary" outline>Month</Button>
        <Button variant="secondary" outline>Year</Button>
    </ButtonGroup>

    <!-- Primary buttons -->
    <ButtonGroup>
        <Button variant="primary">Bold</Button>
        <Button variant="primary">Italic</Button>
        <Button variant="primary">Underline</Button>
    </ButtonGroup>
</template>
```

## Toolbar Example

```vue
<script setup>
import {
    BoldIcon,
    ItalicIcon,
    UnderlineIcon,
    ListBulletIcon,
    ListNumberIcon
} from '@heroicons/vue/24/outline'
import IconButton from '@/Components/UI/IconButton.vue'
</script>

<template>
    <div class="flex gap-4">
        <!-- Text formatting -->
        <ButtonGroup>
            <IconButton :icon="BoldIcon" variant="secondary" label="Bold" />
            <IconButton :icon="ItalicIcon" variant="secondary" label="Italic" />
            <IconButton :icon="UnderlineIcon" variant="secondary" label="Underline" />
        </ButtonGroup>

        <!-- List formatting -->
        <ButtonGroup>
            <IconButton :icon="ListBulletIcon" variant="secondary" label="Bullet list" />
            <IconButton :icon="ListNumberIcon" variant="secondary" label="Numbered list" />
        </ButtonGroup>
    </div>
</template>
```

## Pagination Example

```vue
<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <ButtonGroup>
        <Button variant="secondary" :icon="ChevronLeftIcon">Previous</Button>
        <Button variant="secondary">1</Button>
        <Button variant="primary">2</Button>
        <Button variant="secondary">3</Button>
        <Button variant="secondary" :icon-right="ChevronRightIcon">Next</Button>
    </ButtonGroup>
</template>
```

## Segmented Control

```vue
<script setup>
const view = ref('grid')
</script>

<template>
    <ButtonGroup>
        <Button
            :variant="view === 'grid' ? 'primary' : 'secondary'"
            @click="view = 'grid'"
        >
            Grid View
        </Button>
        <Button
            :variant="view === 'list' ? 'primary' : 'secondary'"
            @click="view = 'list'"
        >
            List View
        </Button>
        <Button
            :variant="view === 'table' ? 'primary' : 'secondary'"
            @click="view = 'table'"
        >
            Table View
        </Button>
    </ButtonGroup>
</template>
```

## Vertical Navigation

```vue
<template>
    <ButtonGroup vertical>
        <Button variant="ghost" block>Dashboard</Button>
        <Button variant="ghost" block>Analytics</Button>
        <Button variant="ghost" block>Reports</Button>
        <Button variant="ghost" block>Settings</Button>
    </ButtonGroup>
</template>
```

## With Different Sizes

```vue
<template>
    <!-- Small buttons -->
    <ButtonGroup>
        <Button variant="secondary" size="sm">Small</Button>
        <Button variant="secondary" size="sm">Group</Button>
    </ButtonGroup>

    <!-- Large buttons -->
    <ButtonGroup>
        <Button variant="secondary" size="lg">Large</Button>
        <Button variant="secondary" size="lg">Group</Button>
    </ButtonGroup>
</template>
```

## Split Button

```vue
<script setup>
import { ChevronDownIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <ButtonGroup>
        <Button variant="primary">Save</Button>
        <Button variant="primary" :icon="ChevronDownIcon" />
    </ButtonGroup>
</template>
```

## Styling Details

The ButtonGroup component applies the following styles to child buttons:

- **Horizontal mode**:
  - Removes border radius from middle buttons
  - Applies left radius to first button
  - Applies right radius to last button
  - Overlaps borders by 1px to prevent double borders

- **Vertical mode**:
  - Removes border radius from middle buttons
  - Applies top radius to first button
  - Applies bottom radius to last button
  - Makes all buttons full width
  - Overlaps borders by 1px vertically

## Accessibility

- The group has `role="group"` for semantic grouping
- Individual buttons maintain their accessibility features
- Focus management works naturally between grouped buttons

## Playground

Try the button group component:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 1rem; align-items: flex-start;">
    <div style="display: flex; gap: 0;">
      <DemoButton variant="secondary" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">Left</DemoButton>
      <DemoButton variant="secondary" style="border-radius: 0; border-left: 0;">Middle</DemoButton>
      <DemoButton variant="secondary" style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-left: 0;">Right</DemoButton>
    </div>
  </div>

  <template #code>

```vue
<ButtonGroup>
  <Button variant="secondary">Left</Button>
  <Button variant="secondary">Middle</Button>
  <Button variant="secondary">Right</Button>
</ButtonGroup>
```

  </template>
</LiveDemo>

### Primary Button Group

<LiveDemo>
  <div style="display: flex; gap: 0;">
    <DemoButton variant="primary" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">Save</DemoButton>
    <DemoButton variant="primary" style="border-radius: 0; border-left: 1px solid rgba(255,255,255,0.2);">Save & Close</DemoButton>
    <DemoButton variant="primary" style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-left: 1px solid rgba(255,255,255,0.2);">Cancel</DemoButton>
  </div>

  <template #code>

```vue
<ButtonGroup>
  <Button variant="primary">Save</Button>
  <Button variant="primary">Save & Close</Button>
  <Button variant="primary">Cancel</Button>
</ButtonGroup>
```

  </template>
</LiveDemo>
