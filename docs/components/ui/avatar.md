# Avatar

A user profile image component with automatic fallback to initials. Supports multiple sizes, shapes, status indicators, and automatic color generation.

## Import

```vue
<script setup>
import Avatar from '@/Components/UI/Avatar.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `src` | `String` | `null` | URL of the avatar image |
| `alt` | `String` | `''` | Alt text for the image |
| `name` | `String` | `''` | User's name (used for initials fallback and color generation) |
| `size` | `String` | `'md'` | Avatar size. Options: `'xs'`, `'sm'`, `'md'`, `'lg'`, `'xl'`, `'2xl'` |
| `rounded` | `String` | `'full'` | Border radius style. Options: `'sm'`, `'md'`, `'lg'`, `'full'` |
| `status` | `String` | `null` | Status indicator. Options: `'online'`, `'offline'`, `'busy'`, `'away'`, `null` |
| `color` | `String` | `null` | Custom background color class for initials fallback |

## Basic Usage

```vue
<template>
    <!-- With image -->
    <Avatar src="/images/user.jpg" alt="John Doe" />

    <!-- With initials fallback -->
    <Avatar name="John Doe" />

    <!-- Unknown user -->
    <Avatar />
</template>
```

## With Image

```vue
<template>
    <Avatar
        src="https://example.com/avatar.jpg"
        alt="User profile picture"
        name="John Doe"
    />
</template>
```

When the image fails to load, the component automatically falls back to displaying initials.

## Initials Fallback

When no `src` is provided or the image fails to load, the avatar displays initials derived from the `name` prop:

```vue
<template>
    <div class="flex gap-2">
        <Avatar name="John Doe" />        <!-- JD -->
        <Avatar name="Alice" />            <!-- A -->
        <Avatar name="Bob Smith Jr" />     <!-- BS (first two words) -->
        <Avatar />                         <!-- ? -->
    </div>
</template>
```

## Sizes

```vue
<template>
    <div class="flex items-center gap-2">
        <Avatar name="John Doe" size="xs" />   <!-- 24px -->
        <Avatar name="John Doe" size="sm" />   <!-- 32px -->
        <Avatar name="John Doe" size="md" />   <!-- 40px -->
        <Avatar name="John Doe" size="lg" />   <!-- 48px -->
        <Avatar name="John Doe" size="xl" />   <!-- 64px -->
        <Avatar name="John Doe" size="2xl" />  <!-- 80px -->
    </div>
</template>
```

Size specifications:

| Size | Dimensions | Font Size |
|------|------------|-----------|
| `xs` | 24px (h-6 w-6) | `text-xs` |
| `sm` | 32px (h-8 w-8) | `text-xs` |
| `md` | 40px (h-10 w-10) | `text-sm` |
| `lg` | 48px (h-12 w-12) | `text-base` |
| `xl` | 64px (h-16 w-16) | `text-lg` |
| `2xl` | 80px (h-20 w-20) | `text-xl` |

## Border Radius

```vue
<template>
    <div class="flex gap-2">
        <Avatar name="John Doe" rounded="sm" />
        <Avatar name="John Doe" rounded="md" />
        <Avatar name="John Doe" rounded="lg" />
        <Avatar name="John Doe" rounded="full" />
    </div>
</template>
```

## Status Indicator

```vue
<template>
    <div class="flex gap-4">
        <Avatar name="John Doe" status="online" />
        <Avatar name="Jane Smith" status="offline" />
        <Avatar name="Bob Wilson" status="busy" />
        <Avatar name="Alice Brown" status="away" />
    </div>
</template>
```

Status colors:
- `online` - Green (emerald-500)
- `offline` - Gray (gray-400)
- `busy` - Red (red-500)
- `away` - Amber (amber-500)

## Custom Colors

By default, the background color is generated deterministically from the name. You can override this:

```vue
<template>
    <div class="flex gap-2">
        <Avatar name="Admin" color="bg-purple-500" />
        <Avatar name="Guest" color="bg-gray-500" />
        <Avatar name="VIP" color="bg-amber-500" />
    </div>
</template>
```

## Automatic Color Generation

When no custom color is specified, the component generates a consistent color based on the user's name:

```vue
<template>
    <div class="flex gap-2">
        <!-- Each name gets a consistent, deterministic color -->
        <Avatar name="Alice" />
        <Avatar name="Bob" />
        <Avatar name="Charlie" />
        <Avatar name="Diana" />
    </div>
</template>
```

Available auto-generated colors:
- `bg-primary-500`
- `bg-emerald-500`
- `bg-amber-500`
- `bg-rose-500`
- `bg-purple-500`
- `bg-sky-500`
- `bg-teal-500`
- `bg-orange-500`

## User List Example

```vue
<template>
    <ul class="divide-y">
        <li v-for="user in users" :key="user.id" class="flex items-center gap-3 py-3">
            <Avatar
                :src="user.avatar"
                :name="user.name"
                :status="user.status"
                size="md"
            />
            <div>
                <p class="font-medium">{{ user.name }}</p>
                <p class="text-sm text-gray-500">{{ user.email }}</p>
            </div>
        </li>
    </ul>
</template>
```

## Profile Header Example

```vue
<template>
    <div class="flex items-center gap-4">
        <Avatar
            :src="user.avatar"
            :name="user.name"
            size="xl"
            :status="user.isOnline ? 'online' : 'offline'"
        />
        <div>
            <h2 class="text-xl font-bold">{{ user.name }}</h2>
            <p class="text-gray-500">{{ user.role }}</p>
        </div>
    </div>
</template>
```

## Comment Author Example

```vue
<template>
    <div class="flex gap-3">
        <Avatar
            :src="comment.author.avatar"
            :name="comment.author.name"
            size="sm"
        />
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <span class="font-medium">{{ comment.author.name }}</span>
                <span class="text-sm text-gray-400">{{ comment.createdAt }}</span>
            </div>
            <p class="mt-1">{{ comment.text }}</p>
        </div>
    </div>
</template>
```

## Navigation User Menu

```vue
<template>
    <div class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 p-2 rounded-lg">
        <Avatar
            :src="currentUser.avatar"
            :name="currentUser.name"
            size="sm"
            status="online"
        />
        <div class="hidden md:block">
            <p class="text-sm font-medium">{{ currentUser.name }}</p>
            <p class="text-xs text-gray-500">{{ currentUser.email }}</p>
        </div>
    </div>
</template>
```

## Card Author Example

```vue
<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-bold text-lg">{{ post.title }}</h3>
        <p class="mt-2 text-gray-600">{{ post.excerpt }}</p>

        <div class="flex items-center gap-3 mt-4 pt-4 border-t">
            <Avatar
                :src="post.author.avatar"
                :name="post.author.name"
                size="sm"
            />
            <div>
                <p class="text-sm font-medium">{{ post.author.name }}</p>
                <p class="text-xs text-gray-500">{{ post.publishedAt }}</p>
            </div>
        </div>
    </div>
</template>
```

## With Rounded Corners

For non-circular avatars (like in card or thumbnail contexts):

```vue
<template>
    <div class="grid grid-cols-4 gap-4">
        <Avatar
            :src="user.avatar"
            :name="user.name"
            size="xl"
            rounded="lg"
        />
    </div>
</template>
```

## Accessibility

- Images include proper `alt` text (falls back to name if not provided)
- Status indicators are visually distinct with clear color coding
- Component maintains proper contrast for initials text
- Flexible sizing supports various interface contexts

## Playground

Try the avatar component with different props:

<LiveDemo>
  <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
    <DemoAvatar name="John Doe" size="xs" />
    <DemoAvatar name="Jane Smith" size="sm" />
    <DemoAvatar name="Bob Wilson" size="md" />
    <DemoAvatar name="Alice Brown" size="lg" />
    <DemoAvatar name="Charlie Davis" size="xl" />
    <DemoAvatar name="Diana Evans" size="2xl" />
  </div>

  <template #code>

```vue
<Avatar name="John Doe" size="xs" />
<Avatar name="Jane Smith" size="sm" />
<Avatar name="Bob Wilson" size="md" />
<Avatar name="Alice Brown" size="lg" />
<Avatar name="Charlie Davis" size="xl" />
<Avatar name="Diana Evans" size="2xl" />
```

  </template>
</LiveDemo>

### With Status Indicator

<LiveDemo>
  <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
    <DemoAvatar name="Online User" status="online" />
    <DemoAvatar name="Offline User" status="offline" />
    <DemoAvatar name="Busy User" status="busy" />
    <DemoAvatar name="Away User" status="away" />
  </div>

  <template #code>

```vue
<Avatar name="Online User" status="online" />
<Avatar name="Offline User" status="offline" />
<Avatar name="Busy User" status="busy" />
<Avatar name="Away User" status="away" />
```

  </template>
</LiveDemo>

### Square Avatars

<LiveDemo>
  <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
    <DemoAvatar name="Square Avatar" :rounded="false" />
    <DemoAvatar name="Square Large" :rounded="false" size="lg" />
  </div>

  <template #code>

```vue
<Avatar name="Square Avatar" rounded="md" />
<Avatar name="Square Large" rounded="lg" size="lg" />
```

  </template>
</LiveDemo>
