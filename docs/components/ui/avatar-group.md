# AvatarGroup

Displays multiple avatars in a stacked, overlapping layout. Ideal for showing team members, participants, or assignees with automatic overflow handling.

## Import

```vue
<script setup>
import AvatarGroup from '@/Components/UI/AvatarGroup.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `items` | `Array` | `[]` | Array of avatar data objects |
| `max` | `Number` | `5` | Maximum number of avatars to display before showing count |
| `size` | `String` | `'md'` | Avatar size. Options: `'xs'`, `'sm'`, `'md'`, `'lg'`, `'xl'` |

## Item Object Structure

Each item in the `items` array should have the following properties:

```typescript
interface AvatarItem {
    src?: string      // Image URL (also accepts 'image' or 'avatar' aliases)
    name?: string     // User name (for initials fallback)
    alt?: string      // Alt text (falls back to name)
}
```

## Basic Usage

```vue
<script setup>
const users = [
    { name: 'John Doe', src: '/avatars/john.jpg' },
    { name: 'Jane Smith', src: '/avatars/jane.jpg' },
    { name: 'Bob Wilson', src: '/avatars/bob.jpg' },
]
</script>

<template>
    <AvatarGroup :items="users" />
</template>
```

## With Overflow

When there are more items than the `max` prop allows, a counter is displayed:

```vue
<script setup>
const teamMembers = [
    { name: 'Alice', src: '/avatars/alice.jpg' },
    { name: 'Bob', src: '/avatars/bob.jpg' },
    { name: 'Charlie', src: '/avatars/charlie.jpg' },
    { name: 'Diana', src: '/avatars/diana.jpg' },
    { name: 'Edward', src: '/avatars/edward.jpg' },
    { name: 'Fiona', src: '/avatars/fiona.jpg' },
    { name: 'George', src: '/avatars/george.jpg' },
]
</script>

<template>
    <!-- Shows 5 avatars + "+2" counter -->
    <AvatarGroup :items="teamMembers" :max="5" />
</template>
```

## Sizes

```vue
<template>
    <div class="space-y-4">
        <AvatarGroup :items="users" size="xs" />
        <AvatarGroup :items="users" size="sm" />
        <AvatarGroup :items="users" size="md" />
        <AvatarGroup :items="users" size="lg" />
        <AvatarGroup :items="users" size="xl" />
    </div>
</template>
```

Size specifications (same as Avatar component):

| Size | Dimensions | Overlap |
|------|------------|---------|
| `xs` | 24px | -6px |
| `sm` | 32px | -8px |
| `md` | 40px | -10px |
| `lg` | 48px | -12px |
| `xl` | 64px | -16px |

## Custom Max Display

```vue
<template>
    <!-- Show only 3 avatars -->
    <AvatarGroup :items="users" :max="3" />

    <!-- Show up to 10 avatars -->
    <AvatarGroup :items="users" :max="10" />

    <!-- Show all avatars (high max) -->
    <AvatarGroup :items="users" :max="999" />
</template>
```

## With Initials Fallback

When images are not available, initials are displayed:

```vue
<script setup>
const participants = [
    { name: 'Alice Anderson' },
    { name: 'Bob Brown' },
    { name: 'Charlie Chen' },
    { name: 'Diana Davis' },
]
</script>

<template>
    <AvatarGroup :items="participants" />
</template>
```

## Alternative Property Names

The component accepts different property names for flexibility:

```vue
<script setup>
// Using 'image' instead of 'src'
const usersWithImage = [
    { name: 'John', image: '/avatars/john.jpg' },
    { name: 'Jane', image: '/avatars/jane.jpg' },
]

// Using 'avatar' instead of 'src'
const usersWithAvatar = [
    { name: 'John', avatar: '/avatars/john.jpg' },
    { name: 'Jane', avatar: '/avatars/jane.jpg' },
]
</script>

<template>
    <AvatarGroup :items="usersWithImage" />
    <AvatarGroup :items="usersWithAvatar" />
</template>
```

## Project Team Example

```vue
<script setup>
const projectTeam = [
    { name: 'Project Lead', src: '/avatars/lead.jpg' },
    { name: 'Designer', src: '/avatars/designer.jpg' },
    { name: 'Developer 1', src: '/avatars/dev1.jpg' },
    { name: 'Developer 2', src: '/avatars/dev2.jpg' },
    { name: 'QA Engineer', src: '/avatars/qa.jpg' },
]
</script>

<template>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold">Project Alpha</h3>
                <p class="text-sm text-gray-500">5 team members</p>
            </div>
            <AvatarGroup :items="projectTeam" size="sm" />
        </div>
    </div>
</template>
```

## Task Assignees Example

```vue
<script setup>
const tasks = [
    {
        title: 'Design homepage',
        assignees: [
            { name: 'Alice', src: '/avatars/alice.jpg' },
            { name: 'Bob', src: '/avatars/bob.jpg' },
        ]
    },
    {
        title: 'Implement API',
        assignees: [
            { name: 'Charlie', src: '/avatars/charlie.jpg' },
            { name: 'Diana', src: '/avatars/diana.jpg' },
            { name: 'Edward', src: '/avatars/edward.jpg' },
        ]
    },
]
</script>

<template>
    <div class="space-y-2">
        <div
            v-for="task in tasks"
            :key="task.title"
            class="flex items-center justify-between p-3 bg-gray-50 rounded"
        >
            <span>{{ task.title }}</span>
            <AvatarGroup :items="task.assignees" size="xs" :max="3" />
        </div>
    </div>
</template>
```

## Chat Participants Example

```vue
<script setup>
const chatParticipants = [
    { name: 'You', src: '/avatars/me.jpg' },
    { name: 'Alice', src: '/avatars/alice.jpg' },
    { name: 'Bob', src: '/avatars/bob.jpg' },
    { name: 'Charlie', src: '/avatars/charlie.jpg' },
    { name: 'Diana' },
    { name: 'Edward' },
]
</script>

<template>
    <div class="flex items-center gap-3">
        <AvatarGroup :items="chatParticipants" :max="4" size="sm" />
        <span class="text-sm text-gray-500">
            {{ chatParticipants.length }} participants
        </span>
    </div>
</template>
```

## Repository Contributors Example

```vue
<script setup>
const contributors = ref([])
const totalContributors = ref(0)

// Fetch from API
onMounted(async () => {
    const data = await fetchContributors()
    contributors.value = data.items
    totalContributors.value = data.total
})
</script>

<template>
    <div class="flex items-center gap-2">
        <AvatarGroup :items="contributors" :max="8" size="sm" />
        <span class="text-sm text-gray-500">
            {{ totalContributors }} contributors
        </span>
    </div>
</template>
```

## Card with Team Members

```vue
<template>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs text-gray-500 uppercase tracking-wide">Team</span>
                <h3 class="text-lg font-bold mt-1">Marketing</h3>
            </div>
            <Badge variant="success">Active</Badge>
        </div>

        <p class="text-gray-600 mt-3">
            Responsible for brand awareness and lead generation.
        </p>

        <div class="flex items-center justify-between mt-4 pt-4 border-t">
            <AvatarGroup :items="marketingTeam" :max="4" size="sm" />
            <Button variant="ghost" size="sm">View Team</Button>
        </div>
    </div>
</template>
```

## Styling Details

- Each avatar has a white ring (`ring-2 ring-white`) for visual separation
- The overflow counter has a gray background with matching ring
- Negative margins create the overlapping effect
- Avatars stack from left to right with proper z-indexing

## Accessibility

- Individual avatars maintain their accessibility features
- Alt text is preserved from the item data
- The overflow counter provides a clear indication of additional participants

## Playground

Try the avatar group component:

<LiveDemo>
  <div style="display: flex; align-items: center;">
    <DemoAvatar name="John Doe" size="md" style="margin-right: -0.5rem; z-index: 4;" />
    <DemoAvatar name="Jane Smith" size="md" style="margin-right: -0.5rem; z-index: 3;" />
    <DemoAvatar name="Bob Wilson" size="md" style="margin-right: -0.5rem; z-index: 2;" />
    <DemoAvatar name="Alice Brown" size="md" style="z-index: 1;" />
  </div>

  <template #code>

```vue
<AvatarGroup :items="[
  { name: 'John Doe' },
  { name: 'Jane Smith' },
  { name: 'Bob Wilson' },
  { name: 'Alice Brown' }
]" />
```

  </template>
</LiveDemo>

### Avatar Group Sizes

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <div style="display: flex; align-items: center;">
      <DemoAvatar name="JD" size="sm" style="margin-right: -0.375rem; z-index: 3;" />
      <DemoAvatar name="JS" size="sm" style="margin-right: -0.375rem; z-index: 2;" />
      <DemoAvatar name="BW" size="sm" style="z-index: 1;" />
      <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #666;">Small</span>
    </div>
    <div style="display: flex; align-items: center;">
      <DemoAvatar name="JD" size="md" style="margin-right: -0.5rem; z-index: 3;" />
      <DemoAvatar name="JS" size="md" style="margin-right: -0.5rem; z-index: 2;" />
      <DemoAvatar name="BW" size="md" style="z-index: 1;" />
      <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #666;">Medium</span>
    </div>
    <div style="display: flex; align-items: center;">
      <DemoAvatar name="JD" size="lg" style="margin-right: -0.625rem; z-index: 3;" />
      <DemoAvatar name="JS" size="lg" style="margin-right: -0.625rem; z-index: 2;" />
      <DemoAvatar name="BW" size="lg" style="z-index: 1;" />
      <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #666;">Large</span>
    </div>
  </div>

  <template #code>

```vue
<AvatarGroup :items="users" size="sm" />
<AvatarGroup :items="users" size="md" />
<AvatarGroup :items="users" size="lg" />
```

  </template>
</LiveDemo>
