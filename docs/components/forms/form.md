# Form

The Form component provides a wrapper for form elements with Inertia.js integration. It handles form submission, error propagation, and processing state management.

## Import

```vue
<script setup>
import { Form } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `method` | `String` | `'post'` | HTTP method for the form. Allowed values: `'get'`, `'post'`, `'put'`, `'patch'`, `'delete'` |
| `action` | `String` | `null` | The URL to submit the form to |
| `form` | `Object` | `null` | Inertia form object created with `useForm()` |
| `preventSubmit` | `Boolean` | `false` | When `true`, prevents form submission |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `submit` | `form` | Emitted when form is submitted (unless `preventSubmit` is true). Receives the form object as payload |

## Slots

### Default Slot

The default slot receives the following scoped properties:

| Property | Type | Description |
|----------|------|-------------|
| `form` | `Object` | The Inertia form object |
| `errors` | `Object` | Form validation errors |
| `processing` | `Boolean` | Whether the form is currently being submitted |

## Features

- **Error Propagation**: Provides form errors via Vue's provide/inject to child components
- **Processing State**: Tracks form submission state for loading indicators
- **Inertia Integration**: Works seamlessly with Inertia.js form handling

## Basic Example

```vue
<script setup>
import { Form, FormGroup, Input, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: ''
})

const submit = (form) => {
    form.post('/users')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Name" :error="form.errors.name">
            <Input v-model="form.name" placeholder="Enter name" />
        </FormGroup>

        <FormGroup label="Email" :error="form.errors.email">
            <Input v-model="form.email" type="email" placeholder="Enter email" />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Submit
        </Button>
    </Form>
</template>
```

## Using Scoped Slot Properties

```vue
<template>
    <Form :form="form" @submit="submit">
        <template #default="{ errors, processing }">
            <FormGroup label="Name" :error="errors.name">
                <Input v-model="form.name" />
            </FormGroup>

            <Button type="submit" :loading="processing">
                Save
            </Button>
        </template>
    </Form>
</template>
```

## Different HTTP Methods

```vue
<!-- Create (POST) -->
<Form :form="form" method="post" @submit="submit">
    <!-- form fields -->
</Form>

<!-- Update (PUT) -->
<Form :form="form" method="put" @submit="submit">
    <!-- form fields -->
</Form>

<!-- Update (PATCH) -->
<Form :form="form" method="patch" @submit="submit">
    <!-- form fields -->
</Form>

<!-- Delete (DELETE) -->
<Form :form="form" method="delete" @submit="submit">
    <!-- confirmation content -->
</Form>
```

## Preventing Submission

Use `preventSubmit` to disable form submission, useful for preview modes or when validation fails:

```vue
<script setup>
const isValid = ref(false)
</script>

<template>
    <Form :form="form" :prevent-submit="!isValid" @submit="submit">
        <!-- form fields -->
    </Form>
</template>
```

## Complete CRUD Example

```vue
<script setup>
import { Form, FormGroup, Input, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    user: Object // null for create, object for edit
})

const form = useForm({
    name: props.user?.name ?? '',
    email: props.user?.email ?? ''
})

const submit = (form) => {
    if (props.user) {
        form.put(`/users/${props.user.id}`)
    } else {
        form.post('/users')
    }
}
</script>

<template>
    <Form
        :form="form"
        :method="user ? 'put' : 'post'"
        @submit="submit"
    >
        <FormGroup label="Name" required :error="form.errors.name">
            <Input v-model="form.name" placeholder="Full name" />
        </FormGroup>

        <FormGroup label="Email" required :error="form.errors.email">
            <Input v-model="form.email" type="email" placeholder="email@example.com" />
        </FormGroup>

        <div class="flex gap-3">
            <Button type="submit" :loading="form.processing">
                {{ user ? 'Update' : 'Create' }} User
            </Button>
            <Button variant="secondary" @click="form.reset()">
                Reset
            </Button>
        </div>
    </Form>
</template>
```

## Accessibility

- Uses semantic `<form>` element
- Prevents default form submission for SPA handling
- Works with keyboard navigation (Enter to submit)

## Playground

Try the Form component:

<LiveDemo>
  <DemoForm />

  <template #code>

```vue
<script setup>
import { Form, FormGroup, Input, Button } from '@cambosoftware/cambo-admin'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: ''
})

const submit = (form) => {
    form.post('/users')
}
</script>

<template>
    <Form :form="form" @submit="submit">
        <FormGroup label="Name" :error="form.errors.name">
            <Input v-model="form.name" placeholder="Enter name" />
        </FormGroup>

        <FormGroup label="Email" :error="form.errors.email">
            <Input v-model="form.email" type="email" placeholder="Enter email" />
        </FormGroup>

        <Button type="submit" :loading="form.processing">
            Submit
        </Button>
    </Form>
</template>
```

  </template>
</LiveDemo>
