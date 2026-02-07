# AppLink

A styled link component for navigation that supports both internal (Inertia) and external links. Automatically handles external link attributes and displays an icon for external URLs.

## Import

```vue
<script setup>
import AppLink from '@/Components/UI/AppLink.vue'
</script>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `href` | `String` | **required** | The URL to link to |
| `external` | `Boolean` | `false` | When true, renders as a regular anchor tag with external link attributes |
| `variant` | `String` | `'default'` | Visual style variant. Options: `'default'`, `'muted'`, `'primary'` |
| `underline` | `String` | `'hover'` | Underline behavior. Options: `'always'`, `'hover'`, `'none'` |

## Slots

| Name | Description |
|------|-------------|
| `default` | Link text content |

## Basic Usage

```vue
<template>
    <!-- Internal link (uses Inertia) -->
    <AppLink href="/dashboard">Dashboard</AppLink>

    <!-- External link -->
    <AppLink href="https://google.com" external>Google</AppLink>
</template>
```

## Variants

### Default Variant

Primary colored link for important navigation:

```vue
<template>
    <AppLink href="/profile" variant="default">View Profile</AppLink>
</template>
```

### Muted Variant

Subtle gray link for secondary navigation:

```vue
<template>
    <AppLink href="/help" variant="muted">Need help?</AppLink>
</template>
```

### Primary Variant

Bold, emphasized link with semibold font:

```vue
<template>
    <AppLink href="/signup" variant="primary">Sign up now</AppLink>
</template>
```

## Variant Comparison

```vue
<template>
    <div class="space-y-2">
        <p>
            <AppLink href="/link" variant="default">Default link style</AppLink>
        </p>
        <p>
            <AppLink href="/link" variant="muted">Muted link style</AppLink>
        </p>
        <p>
            <AppLink href="/link" variant="primary">Primary link style</AppLink>
        </p>
    </div>
</template>
```

## Underline Options

### Hover Underline (Default)

```vue
<template>
    <AppLink href="/page" underline="hover">
        Shows underline on hover
    </AppLink>
</template>
```

### Always Underlined

```vue
<template>
    <AppLink href="/page" underline="always">
        Always underlined
    </AppLink>
</template>
```

### No Underline

```vue
<template>
    <AppLink href="/page" underline="none">
        Never underlined
    </AppLink>
</template>
```

## Internal Links

Internal links use Inertia's `Link` component for SPA navigation:

```vue
<template>
    <nav class="space-x-4">
        <AppLink href="/">Home</AppLink>
        <AppLink href="/about">About</AppLink>
        <AppLink href="/contact">Contact</AppLink>
    </nav>
</template>
```

## External Links

External links automatically:
- Open in a new tab (`target="_blank"`)
- Include security attributes (`rel="noopener noreferrer"`)
- Display an external link icon

```vue
<template>
    <div class="space-y-2">
        <p>
            <AppLink href="https://vuejs.org" external>Vue.js Documentation</AppLink>
        </p>
        <p>
            <AppLink href="https://laravel.com" external>Laravel Website</AppLink>
        </p>
        <p>
            <AppLink href="https://github.com" external>GitHub</AppLink>
        </p>
    </div>
</template>
```

## Navigation Menu

```vue
<template>
    <nav class="flex items-center gap-6">
        <AppLink href="/features" variant="muted" underline="none">
            Features
        </AppLink>
        <AppLink href="/pricing" variant="muted" underline="none">
            Pricing
        </AppLink>
        <AppLink href="/docs" variant="muted" underline="none">
            Documentation
        </AppLink>
        <AppLink href="https://github.com/example" external variant="muted" underline="none">
            GitHub
        </AppLink>
    </nav>
</template>
```

## Inline Text Links

```vue
<template>
    <p class="text-gray-600">
        For more information, please visit our
        <AppLink href="/help">Help Center</AppLink>
        or check the
        <AppLink href="https://docs.example.com" external>external documentation</AppLink>.
    </p>
</template>
```

## Breadcrumb Navigation

```vue
<template>
    <nav class="flex items-center gap-2 text-sm">
        <AppLink href="/" variant="muted">Home</AppLink>
        <span class="text-gray-400">/</span>
        <AppLink href="/products" variant="muted">Products</AppLink>
        <span class="text-gray-400">/</span>
        <span class="text-gray-900">Current Page</span>
    </nav>
</template>
```

## Footer Links

```vue
<template>
    <footer class="border-t py-8">
        <div class="flex justify-center gap-8">
            <AppLink href="/privacy" variant="muted" underline="hover">
                Privacy Policy
            </AppLink>
            <AppLink href="/terms" variant="muted" underline="hover">
                Terms of Service
            </AppLink>
            <AppLink href="mailto:support@example.com" external variant="muted" underline="hover">
                Contact Us
            </AppLink>
        </div>
    </footer>
</template>
```

## Card with Links

```vue
<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-bold text-lg">Resources</h3>
        <ul class="mt-4 space-y-2">
            <li>
                <AppLink href="/guides/getting-started">Getting Started Guide</AppLink>
            </li>
            <li>
                <AppLink href="/api/reference">API Reference</AppLink>
            </li>
            <li>
                <AppLink href="https://github.com/example/repo" external>
                    Source Code
                </AppLink>
            </li>
        </ul>
    </div>
</template>
```

## Article References

```vue
<template>
    <article>
        <p>
            As mentioned in the
            <AppLink href="/blog/previous-post" variant="primary">previous article</AppLink>,
            we'll continue exploring Vue.js patterns.
        </p>

        <h3>External Resources</h3>
        <ul class="mt-2 space-y-1">
            <li>
                <AppLink href="https://vuejs.org/guide" external>
                    Official Vue.js Guide
                </AppLink>
            </li>
            <li>
                <AppLink href="https://tailwindcss.com/docs" external>
                    Tailwind CSS Documentation
                </AppLink>
            </li>
        </ul>
    </article>
</template>
```

## Call to Action

```vue
<template>
    <div class="text-center py-8 bg-gray-50 rounded-lg">
        <h2 class="text-xl font-bold">Ready to get started?</h2>
        <p class="mt-2 text-gray-600">
            <AppLink href="/signup" variant="primary" underline="always">
                Create your free account
            </AppLink>
            or
            <AppLink href="/demo" variant="default">
                request a demo
            </AppLink>
        </p>
    </div>
</template>
```

## Sidebar Navigation

```vue
<script setup>
const currentPath = '/dashboard'
</script>

<template>
    <aside class="w-64 border-r p-4">
        <nav class="space-y-1">
            <AppLink
                href="/dashboard"
                :variant="currentPath === '/dashboard' ? 'primary' : 'muted'"
                underline="none"
                class="block px-3 py-2 rounded-lg hover:bg-gray-100"
            >
                Dashboard
            </AppLink>
            <AppLink
                href="/projects"
                :variant="currentPath === '/projects' ? 'primary' : 'muted'"
                underline="none"
                class="block px-3 py-2 rounded-lg hover:bg-gray-100"
            >
                Projects
            </AppLink>
            <AppLink
                href="/settings"
                :variant="currentPath === '/settings' ? 'primary' : 'muted'"
                underline="none"
                class="block px-3 py-2 rounded-lg hover:bg-gray-100"
            >
                Settings
            </AppLink>
        </nav>
    </aside>
</template>
```

## Styling Details

### Variant Colors

| Variant | Default State | Hover State |
|---------|---------------|-------------|
| `default` | `text-primary-600` | `text-primary-700` |
| `muted` | `text-gray-500` | `text-gray-700` |
| `primary` | `text-primary-600 font-semibold` | `text-primary-800` |

### External Link Icon

External links display a small arrow icon (14px) to indicate they open in a new tab.

### Transitions

All links include smooth color transitions with `duration-150`.

## Accessibility

- Internal links use Inertia's `Link` component for proper SPA navigation
- External links include `target="_blank"` and `rel="noopener noreferrer"` for security
- External link icon provides visual indication of new tab behavior
- Color contrast meets WCAG accessibility standards
- Underline options provide flexibility while maintaining usability

## Playground

Try the AppLink component:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 8px;">
    <a href="#" style="color: #4f46e5; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#4338ca'; this.style.textDecoration='underline'" onmouseout="this.style.color='#4f46e5'; this.style.textDecoration='none'">Default Link</a>
    <a href="#" style="color: #6b7280; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#374151'; this.style.textDecoration='underline'" onmouseout="this.style.color='#6b7280'; this.style.textDecoration='none'">Muted Link</a>
    <a href="#" style="color: #4f46e5; font-weight: 600; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#3730a3'; this.style.textDecoration='underline'" onmouseout="this.style.color='#4f46e5'; this.style.textDecoration='none'">Primary Link</a>
  </div>

  <template #code>

```vue
<template>
    <div class="space-y-2">
        <AppLink href="/dashboard" variant="default">Default Link</AppLink>
        <AppLink href="/help" variant="muted">Muted Link</AppLink>
        <AppLink href="/signup" variant="primary">Primary Link</AppLink>
    </div>
</template>

<script setup>
import AppLink from '@/Components/UI/AppLink.vue'
</script>
```

  </template>
</LiveDemo>
