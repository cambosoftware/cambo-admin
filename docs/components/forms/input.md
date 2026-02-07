# Input

A versatile text input component with support for various input types, icons, prefixes/suffixes, and validation states.

## Import

```vue
<script setup>
import { Input } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String \| Number` | `''` | The input value (v-model) |
| `type` | `String` | `'text'` | Input type. Allowed: `'text'`, `'email'`, `'password'`, `'number'`, `'tel'`, `'url'`, `'search'`, `'date'`, `'time'`, `'datetime-local'` |
| `placeholder` | `String` | `null` | Placeholder text |
| `size` | `String` | `'md'` | Input size. Allowed: `'sm'`, `'md'`, `'lg'` |
| `disabled` | `Boolean` | `false` | Disables the input |
| `readonly` | `Boolean` | `false` | Makes the input read-only |
| `error` | `String \| Boolean` | `null` | Error state or message. When truthy, shows error styling |
| `icon` | `Object \| Function` | `null` | Icon component to display on the left |
| `iconRight` | `Object \| Function` | `null` | Icon component to display on the right |
| `prefix` | `String` | `null` | Text prefix (e.g., "$", "https://") |
| `suffix` | `String` | `null` | Text suffix (e.g., ".com", "kg") |
| `clearable` | `Boolean` | `false` | Shows a clear button when input has value |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String \| Number` | Emitted when value changes |
| `clear` | - | Emitted when clear button is clicked |
| `focus` | `FocusEvent` | Emitted when input gains focus |
| `blur` | `FocusEvent` | Emitted when input loses focus |

## Exposed Methods

| Method | Description |
|--------|-------------|
| `focus()` | Programmatically focus the input |

## Basic Example

```vue
<script setup>
import { Input } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const name = ref('')
</script>

<template>
    <Input v-model="name" placeholder="Enter your name" />
</template>
```

## Input Types

```vue
<!-- Text (default) -->
<Input v-model="text" type="text" placeholder="Enter text" />

<!-- Email -->
<Input v-model="email" type="email" placeholder="email@example.com" />

<!-- Password -->
<Input v-model="password" type="password" placeholder="Enter password" />

<!-- Number -->
<Input v-model="age" type="number" placeholder="Enter age" />

<!-- Telephone -->
<Input v-model="phone" type="tel" placeholder="+1 (555) 000-0000" />

<!-- URL -->
<Input v-model="website" type="url" placeholder="https://example.com" />

<!-- Search -->
<Input v-model="query" type="search" placeholder="Search..." />

<!-- Date -->
<Input v-model="date" type="date" />

<!-- Time -->
<Input v-model="time" type="time" />

<!-- DateTime Local -->
<Input v-model="datetime" type="datetime-local" />
```

## Sizes

```vue
<!-- Small -->
<Input v-model="value" size="sm" placeholder="Small input" />

<!-- Medium (default) -->
<Input v-model="value" size="md" placeholder="Medium input" />

<!-- Large -->
<Input v-model="value" size="lg" placeholder="Large input" />
```

## With Icons

```vue
<script setup>
import { Input } from '@cambosoftware/cambo-admin'
import { MagnifyingGlassIcon, EnvelopeIcon, CheckIcon } from '@heroicons/vue/24/outline'
</script>

<template>
    <!-- Left icon -->
    <Input
        v-model="search"
        :icon="MagnifyingGlassIcon"
        placeholder="Search..."
    />

    <!-- Right icon -->
    <Input
        v-model="email"
        :icon-right="CheckIcon"
        placeholder="Verified email"
    />

    <!-- Both icons -->
    <Input
        v-model="email"
        :icon="EnvelopeIcon"
        :icon-right="CheckIcon"
        placeholder="Email"
    />
</template>
```

## With Prefix and Suffix

```vue
<!-- Currency prefix -->
<Input v-model="price" prefix="$" type="number" placeholder="0.00" />

<!-- URL prefix -->
<Input v-model="username" prefix="https://" suffix=".com" placeholder="yoursite" />

<!-- Unit suffix -->
<Input v-model="weight" suffix="kg" type="number" placeholder="0" />
```

## Clearable Input

```vue
<Input
    v-model="search"
    clearable
    placeholder="Search (click X to clear)"
/>
```

## Error State

```vue
<script setup>
const errors = ref({ email: 'Invalid email address' })
</script>

<template>
    <FormGroup label="Email" :error="errors.email">
        <Input
            v-model="email"
            type="email"
            :error="errors.email"
            placeholder="Enter email"
        />
    </FormGroup>
</template>
```

## Disabled and Readonly

```vue
<!-- Disabled -->
<Input v-model="value" disabled placeholder="Disabled input" />

<!-- Readonly -->
<Input v-model="value" readonly placeholder="Read-only input" />
```

## Programmatic Focus

```vue
<script setup>
import { ref } from 'vue'
import { Input, Button } from '@cambosoftware/cambo-admin'

const inputRef = ref(null)

const focusInput = () => {
    inputRef.value?.focus()
}
</script>

<template>
    <Input ref="inputRef" v-model="value" placeholder="Click button to focus" />
    <Button @click="focusInput">Focus Input</Button>
</template>
```

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'
import { UserIcon, EnvelopeIcon, PhoneIcon, GlobeAltIcon } from '@heroicons/vue/24/outline'

const form = useForm({
    name: '',
    email: '',
    phone: '',
    website: ''
})
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Full Name" required :error="form.errors.name">
            <Input
                v-model="form.name"
                :icon="UserIcon"
                :error="form.errors.name"
                placeholder="John Doe"
            />
        </FormGroup>

        <FormGroup label="Email" required :error="form.errors.email">
            <Input
                v-model="form.email"
                type="email"
                :icon="EnvelopeIcon"
                :error="form.errors.email"
                placeholder="john@example.com"
            />
        </FormGroup>

        <FormGroup label="Phone" :error="form.errors.phone">
            <Input
                v-model="form.phone"
                type="tel"
                :icon="PhoneIcon"
                :error="form.errors.phone"
                placeholder="+1 (555) 000-0000"
            />
        </FormGroup>

        <FormGroup label="Website" :error="form.errors.website">
            <Input
                v-model="form.website"
                prefix="https://"
                :error="form.errors.website"
                placeholder="yoursite.com"
            />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Save Contact
        </Button>
    </Form>
</template>
```

## Styling

The Input component includes:
- Rounded corners (`rounded-lg`)
- Border with focus ring
- Smooth color transitions
- Dark mode support
- Responsive sizing

### Size Classes

| Size | Padding | Font Size |
|------|---------|-----------|
| `sm` | `px-2.5 py-1.5` | `text-xs` |
| `md` | `px-3 py-2` | `text-sm` |
| `lg` | `px-4 py-2.5` | `text-base` |

## Accessibility

- Uses native `<input>` element for full browser support
- Supports all standard input attributes
- Focus states are clearly visible
- Error states use semantic red coloring
- Works with screen readers

## Playground

Try the input component with different props:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 20rem;">
    <DemoInput placeholder="Default input" />
    <DemoInput placeholder="With prefix" prefix="$" />
    <DemoInput placeholder="With suffix" suffix=".com" />
    <DemoInput placeholder="Clearable input" clearable />
  </div>

  <template #code>

```vue
<Input placeholder="Default input" />
<Input placeholder="With prefix" prefix="$" />
<Input placeholder="With suffix" suffix=".com" />
<Input placeholder="Clearable input" clearable />
```

  </template>
</LiveDemo>

### Input Sizes

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 20rem;">
    <DemoInput size="sm" placeholder="Small input" />
    <DemoInput size="md" placeholder="Medium input" />
    <DemoInput size="lg" placeholder="Large input" />
  </div>

  <template #code>

```vue
<Input size="sm" placeholder="Small input" />
<Input size="md" placeholder="Medium input" />
<Input size="lg" placeholder="Large input" />
```

  </template>
</LiveDemo>

### Input States

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 20rem;">
    <DemoInput placeholder="Normal input" />
    <DemoInput placeholder="Disabled input" disabled />
    <DemoInput placeholder="Error state" error />
  </div>

  <template #code>

```vue
<Input placeholder="Normal input" />
<Input placeholder="Disabled input" disabled />
<Input placeholder="Error state" error />
```

  </template>
</LiveDemo>
