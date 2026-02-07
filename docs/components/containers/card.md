# Card

A versatile container component for grouping related content with optional header, footer, and customizable styling.

## Import

```vue
<script setup>
import { Card } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `String` | `null` | Card header title text |
| `subtitle` | `String` | `null` | Card header subtitle text |
| `padding` | `Boolean` | `true` | Whether to apply padding to the card body |
| `hoverable` | `Boolean` | `false` | Add hover effect with shadow transition |
| `bordered` | `Boolean` | `true` | Show border ring. If false, shows shadow instead |
| `overflow` | `String` | `'hidden'` | Control overflow behavior. Values: `'hidden'`, `'visible'`, `'auto'`. Use `'visible'` when card contains dropdowns or tooltips |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Main content of the card |
| `header` | Custom header content (replaces title/subtitle) |
| `header-actions` | Actions displayed in the header (right side) |
| `footer` | Footer content with gray background |

## Events

This component does not emit any events.

## Examples

### Basic Card

```vue
<template>
    <Card title="User Profile">
        <p>This is the card content.</p>
    </Card>
</template>
```

### Card with Subtitle

```vue
<template>
    <Card
        title="Dashboard"
        subtitle="Overview of your account"
    >
        <p>Dashboard content goes here.</p>
    </Card>
</template>
```

### Card with Header Actions

```vue
<template>
    <Card title="Recent Orders">
        <template #header-actions>
            <Button size="sm" variant="outline">View All</Button>
        </template>

        <ul>
            <li>Order #1234</li>
            <li>Order #1235</li>
        </ul>
    </Card>
</template>
```

### Card with Footer

```vue
<template>
    <Card title="Settings">
        <form>
            <Input label="Name" v-model="name" />
        </form>

        <template #footer>
            <div class="flex justify-end gap-2">
                <Button variant="outline">Cancel</Button>
                <Button>Save</Button>
            </div>
        </template>
    </Card>
</template>
```

### Hoverable Card

```vue
<template>
    <Card
        title="Feature Card"
        hoverable
        @click="navigateToFeature"
    >
        <p>Click this card to learn more about this feature.</p>
    </Card>
</template>
```

### Card with Shadow (No Border)

```vue
<template>
    <Card title="Elevated Card" :bordered="false">
        <p>This card has a shadow instead of a border.</p>
    </Card>
</template>
```

### Card with Dropdown (Visible Overflow)

```vue
<template>
    <Card title="Select Options" overflow="visible">
        <Select
            :options="options"
            v-model="selected"
        />
    </Card>
</template>
```

### Card Without Padding

```vue
<template>
    <Card title="Full Width Content" :padding="false">
        <img src="/hero.jpg" class="w-full" />
    </Card>
</template>
```

### Custom Header

```vue
<template>
    <Card>
        <template #header>
            <div class="flex items-center gap-3">
                <Avatar :src="user.avatar" />
                <div>
                    <h3 class="font-semibold">{{ user.name }}</h3>
                    <p class="text-sm text-gray-500">{{ user.email }}</p>
                </div>
            </div>
        </template>

        <p>User profile content...</p>
    </Card>
</template>
```

## Styling

The Card component uses the following Tailwind CSS classes:

- Background: `bg-white dark:bg-gray-800`
- Border radius: `rounded-xl`
- Border (when bordered): `ring-1 ring-gray-200 dark:ring-gray-700`
- Shadow (when not bordered): `shadow-lg dark:shadow-gray-900/50`
- Header/footer border: `border-gray-200 dark:border-gray-700`
- Footer background: `bg-gray-50 dark:bg-gray-800/50`

## Accessibility

- The card structure is semantic with proper heading hierarchy
- When using `hoverable`, consider adding appropriate `role` and keyboard interaction if the card is meant to be interactive

## Playground

Try the card component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <DemoCard title="Basic Card">
      This is a simple card with a title.
    </DemoCard>
    <DemoCard title="With Subtitle" subtitle="Additional information">
      Card with both title and subtitle.
    </DemoCard>
  </div>

  <template #code>

```vue
<Card title="Basic Card">
  This is a simple card with a title.
</Card>

<Card title="With Subtitle" subtitle="Additional information">
  Card with both title and subtitle.
</Card>
```

  </template>
</LiveDemo>

### Card Styles

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <DemoCard title="Bordered Card" bordered>
      Card with a border.
    </DemoCard>
    <DemoCard title="Hoverable Card" hoverable>
      Hover over this card to see the effect.
    </DemoCard>
  </div>

  <template #code>

```vue
<Card title="Bordered Card" bordered>
  Card with a border.
</Card>

<Card title="Hoverable Card" hoverable>
  Hover over this card to see the effect.
</Card>
```

  </template>
</LiveDemo>
