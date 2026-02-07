# FormGroup

The FormGroup component provides a consistent layout for form fields, combining labels, input areas, error messages, and hint text.

## Import

```vue
<script setup>
import { FormGroup } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `String` | `null` | Text label for the form field |
| `for` | `String` | `null` | ID of the associated input element (for accessibility) |
| `required` | `Boolean` | `false` | Shows a red asterisk (*) next to the label |
| `error` | `String` | `null` | Error message to display below the input |
| `hint` | `String` | `null` | Hint text displayed below the input (hidden when error is shown) |
| `inline` | `Boolean` | `false` | Displays label and input side-by-side |

## Slots

### Default Slot

The default slot is used for the form input component.

```vue
<FormGroup label="Email">
    <Input v-model="email" type="email" />
</FormGroup>
```

## Basic Example

```vue
<script setup>
import { FormGroup, Input } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const name = ref('')
</script>

<template>
    <FormGroup label="Full Name" for="name-input">
        <Input id="name-input" v-model="name" placeholder="Enter your name" />
    </FormGroup>
</template>
```

## With Required Indicator

```vue
<FormGroup label="Email" required>
    <Input v-model="email" type="email" />
</FormGroup>
```

Renders the label with a red asterisk: **Email** <span style="color: red">*</span>

## With Error Message

```vue
<FormGroup
    label="Email"
    :error="form.errors.email"
>
    <Input
        v-model="form.email"
        type="email"
        :error="form.errors.email"
    />
</FormGroup>
```

## With Hint Text

```vue
<FormGroup
    label="Password"
    hint="Must be at least 8 characters with one uppercase letter"
>
    <Input v-model="password" type="password" />
</FormGroup>
```

Note: When both `error` and `hint` are provided, only the error message is shown.

## Inline Layout

Use the `inline` prop for horizontal form layouts where the label appears to the left of the input:

```vue
<FormGroup label="Company Name" inline>
    <Input v-model="company" />
</FormGroup>

<FormGroup label="Website" inline>
    <Input v-model="website" type="url" />
</FormGroup>
```

In inline mode:
- Label takes 1/3 width and aligns right
- Input takes remaining 2/3 width
- On mobile, falls back to stacked layout

## Complete Form Example

```vue
<script setup>
import { Form, FormGroup, Input, Textarea, Select, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    role: '',
    bio: ''
})

const roles = [
    { value: 'admin', label: 'Administrator' },
    { value: 'editor', label: 'Editor' },
    { value: 'viewer', label: 'Viewer' }
]
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup
            label="Name"
            required
            :error="form.errors.name"
        >
            <Input
                v-model="form.name"
                :error="form.errors.name"
                placeholder="Full name"
            />
        </FormGroup>

        <FormGroup
            label="Email"
            required
            :error="form.errors.email"
            hint="We'll never share your email"
        >
            <Input
                v-model="form.email"
                type="email"
                :error="form.errors.email"
                placeholder="email@example.com"
            />
        </FormGroup>

        <FormGroup
            label="Role"
            :error="form.errors.role"
        >
            <Select
                v-model="form.role"
                :options="roles"
                :error="form.errors.role"
                placeholder="Select a role"
            />
        </FormGroup>

        <FormGroup
            label="Bio"
            :error="form.errors.bio"
            hint="Tell us about yourself"
        >
            <Textarea
                v-model="form.bio"
                :error="form.errors.bio"
                :rows="4"
            />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Save Profile
        </Button>
    </Form>
</template>
```

## Inline Form Example

```vue
<template>
    <Form :form="form" @submit="submit" class="space-y-4">
        <FormGroup label="First Name" inline required :error="form.errors.first_name">
            <Input v-model="form.first_name" :error="form.errors.first_name" />
        </FormGroup>

        <FormGroup label="Last Name" inline required :error="form.errors.last_name">
            <Input v-model="form.last_name" :error="form.errors.last_name" />
        </FormGroup>

        <FormGroup label="Email" inline required :error="form.errors.email">
            <Input v-model="form.email" type="email" :error="form.errors.email" />
        </FormGroup>

        <FormGroup label="" inline>
            <Button type="submit" :loading="form.processing">
                Save
            </Button>
        </FormGroup>
    </Form>
</template>
```

## Styling

The FormGroup applies the following styles:
- Label: `text-sm font-medium text-gray-700`
- Error text: `text-sm text-red-600`
- Hint text: `text-sm text-gray-500`
- Required asterisk: `text-red-500`

## Accessibility

- Uses `<label>` element with proper `for` attribute linking
- Error messages are visually distinct with red color
- Required indicator provides visual cue for mandatory fields

## Playground

Try the FormGroup component:

<LiveDemo>
  <DemoFormGroup />

  <template #code>

```vue
<script setup>
import { FormGroup, Input } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const name = ref('')
const email = ref('')
</script>

<template>
    <div class="space-y-4">
        <FormGroup label="Full Name" required for="name-input">
            <Input id="name-input" v-model="name" placeholder="Enter your name" />
        </FormGroup>

        <FormGroup label="Email" hint="We'll never share your email">
            <Input v-model="email" type="email" placeholder="email@example.com" />
        </FormGroup>

        <FormGroup label="Company" inline>
            <Input placeholder="Company name" />
        </FormGroup>
    </div>
</template>
```

  </template>
</LiveDemo>
