# Switch

A toggle switch component for boolean on/off states. Provides a more visual alternative to checkboxes for settings and preferences.

## Import

```vue
<script setup>
import { Switch } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Boolean` | `false` | The switch state (v-model) |
| `label` | `String` | `null` | Text label for the switch |
| `description` | `String` | `null` | Description text below the label |
| `size` | `String` | `'md'` | Switch size. Allowed: `'sm'`, `'md'`, `'lg'` |
| `disabled` | `Boolean` | `false` | Disables the switch |
| `error` | `String \| Boolean` | `null` | Error state (not visually styled, for form integration) |
| `activeColor` | `String` | `'primary'` | Color when active. Allowed: `'primary'`, `'success'`, `'danger'`, `'warning'`, `'info'` |
| `labelPosition` | `String` | `'right'` | Position of label. Allowed: `'left'`, `'right'` |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Boolean` | Emitted when state changes |
| `change` | `Boolean` | Emitted when state changes |

## Slots

### Default Slot

Use the default slot for custom label content instead of the `label` prop.

```vue
<Switch v-model="enabled">
    <span class="font-bold">Enable notifications</span>
</Switch>
```

## Basic Example

```vue
<script setup>
import { Switch } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const notifications = ref(false)
</script>

<template>
    <Switch v-model="notifications" label="Enable notifications" />
</template>
```

## With Description

```vue
<Switch
    v-model="darkMode"
    label="Dark Mode"
    description="Use dark theme throughout the application"
/>
```

## Sizes

```vue
<!-- Small -->
<Switch v-model="value" size="sm" label="Small switch" />

<!-- Medium (default) -->
<Switch v-model="value" size="md" label="Medium switch" />

<!-- Large -->
<Switch v-model="value" size="lg" label="Large switch" />
```

### Size Specifications

| Size | Track Size | Dot Size | Translation |
|------|------------|----------|-------------|
| `sm` | `h-4 w-7` | `h-3 w-3` | `translate-x-3` |
| `md` | `h-5 w-9` | `h-4 w-4` | `translate-x-4` |
| `lg` | `h-6 w-11` | `h-5 w-5` | `translate-x-5` |

## Color Variants

```vue
<!-- Primary (default) -->
<Switch v-model="value" active-color="primary" label="Primary" />

<!-- Success -->
<Switch v-model="value" active-color="success" label="Success" />

<!-- Danger -->
<Switch v-model="value" active-color="danger" label="Danger" />

<!-- Warning -->
<Switch v-model="value" active-color="warning" label="Warning" />

<!-- Info -->
<Switch v-model="value" active-color="info" label="Info" />
```

### Color Classes

| Color | Active Class |
|-------|--------------|
| `primary` | `bg-primary-600` |
| `success` | `bg-emerald-500` |
| `danger` | `bg-red-500` |
| `warning` | `bg-amber-500` |
| `info` | `bg-sky-500` |

## Label Position

```vue
<!-- Label on right (default) -->
<Switch v-model="value" label="Label on right" label-position="right" />

<!-- Label on left -->
<Switch v-model="value" label="Label on left" label-position="left" />
```

## Disabled State

```vue
<!-- Disabled off -->
<Switch v-model="value1" disabled label="Disabled (off)" />

<!-- Disabled on -->
<Switch :model-value="true" disabled label="Disabled (on)" />
```

## Custom Label with Slot

```vue
<Switch v-model="twoFactor">
    <span class="flex items-center gap-2">
        <ShieldCheckIcon class="h-5 w-5 text-green-600" />
        <span>Two-factor authentication</span>
    </span>
</Switch>
```

## Settings Panel Example

```vue
<script setup>
import { Switch } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const settings = ref({
    notifications: true,
    emailDigest: false,
    darkMode: false,
    autoSave: true,
    publicProfile: false
})
</script>

<template>
    <div class="divide-y divide-gray-200">
        <div class="py-4">
            <Switch
                v-model="settings.notifications"
                label="Push Notifications"
                description="Receive push notifications on your device"
            />
        </div>

        <div class="py-4">
            <Switch
                v-model="settings.emailDigest"
                label="Email Digest"
                description="Receive a weekly summary of activity"
            />
        </div>

        <div class="py-4">
            <Switch
                v-model="settings.darkMode"
                label="Dark Mode"
                description="Use dark theme across the application"
            />
        </div>

        <div class="py-4">
            <Switch
                v-model="settings.autoSave"
                label="Auto-save"
                description="Automatically save changes as you work"
                active-color="success"
            />
        </div>

        <div class="py-4">
            <Switch
                v-model="settings.publicProfile"
                label="Public Profile"
                description="Make your profile visible to everyone"
                active-color="warning"
            />
        </div>
    </div>
</template>
```

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, Switch, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    is_active: true,
    is_admin: false,
    notifications: true
})

const submit = (form) => {
    form.post('/users')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Name" required :error="form.errors.name">
            <Input v-model="form.name" :error="form.errors.name" />
        </FormGroup>

        <FormGroup label="Email" required :error="form.errors.email">
            <Input v-model="form.email" type="email" :error="form.errors.email" />
        </FormGroup>

        <div class="space-y-4 py-4 border-t border-b">
            <Switch
                v-model="form.is_active"
                label="Active"
                description="User can log in and access the system"
                active-color="success"
            />

            <Switch
                v-model="form.is_admin"
                label="Administrator"
                description="Grant full administrative privileges"
                active-color="danger"
            />

            <Switch
                v-model="form.notifications"
                label="Email Notifications"
                description="Send email notifications to this user"
            />
        </div>

        <Button type="submit" :loading="form.processing">
            Create User
        </Button>
    </Form>
</template>
```

## Feature Flags Pattern

```vue
<script setup>
const features = ref({
    beta_features: false,
    experimental: false,
    developer_mode: false
})
</script>

<template>
    <div class="space-y-4">
        <Switch
            v-model="features.beta_features"
            label="Beta Features"
            description="Enable access to features still in testing"
            active-color="warning"
        />

        <Switch
            v-model="features.experimental"
            label="Experimental Features"
            description="Enable highly experimental features (may be unstable)"
            active-color="danger"
        />

        <Switch
            v-model="features.developer_mode"
            label="Developer Mode"
            description="Show additional debugging information"
            active-color="info"
        />
    </div>
</template>
```

## When to Use Switch vs Checkbox

| Use Switch | Use Checkbox |
|------------|--------------|
| Instant effect (no save button) | Part of a form to submit |
| Settings/preferences | Multi-select scenarios |
| Feature toggles | Terms agreement |
| On/Off states | Yes/No answers |
| Mobile-friendly UI | Traditional forms |

## Styling

The Switch component includes:
- Pill-shaped track
- Circular sliding dot
- Smooth transition animations
- Focus ring for accessibility
- Color variants for semantic meaning

### Visual States

| State | Track Color |
|-------|-------------|
| Off | `bg-gray-200` |
| On (primary) | `bg-primary-600` |
| On (success) | `bg-emerald-500` |
| On (danger) | `bg-red-500` |
| On (warning) | `bg-amber-500` |
| On (info) | `bg-sky-500` |
| Disabled | 50% opacity |

## Accessibility

- Uses `role="switch"` for ARIA
- Includes `aria-checked` attribute
- Keyboard accessible (click to toggle)
- Focus ring visible on focus
- Label and description provide context
- Works with screen readers

## Playground

Try the switch component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoSwitch label="Default switch" />
    <DemoSwitch label="Enabled switch" :modelValue="true" />
    <DemoSwitch label="Disabled switch" disabled />
  </div>

  <template #code>

```vue
<Switch label="Default switch" />
<Switch v-model="enabled" label="Enabled switch" />
<Switch label="Disabled switch" disabled />
```

  </template>
</LiveDemo>

### Switch Colors

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoSwitch label="Primary" color="primary" :modelValue="true" />
    <DemoSwitch label="Success" color="success" :modelValue="true" />
    <DemoSwitch label="Danger" color="danger" :modelValue="true" />
  </div>

  <template #code>

```vue
<Switch v-model="value" label="Primary" active-color="primary" />
<Switch v-model="value" label="Success" active-color="success" />
<Switch v-model="value" label="Danger" active-color="danger" />
```

  </template>
</LiveDemo>

### Label Position

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoSwitch label="Label on right" labelPosition="right" />
    <DemoSwitch label="Label on left" labelPosition="left" />
  </div>

  <template #code>

```vue
<Switch label="Label on right" label-position="right" />
<Switch label="Label on left" label-position="left" />
```

  </template>
</LiveDemo>
