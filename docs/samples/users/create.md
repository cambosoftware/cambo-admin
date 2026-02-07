# User Create Page

A comprehensive form for creating new users with validation, role assignment, and avatar upload.

## Full Code

```vue
<script setup>
import { useForm } from '@inertiajs/vue3'
import {
    AdminLayout,
    PageHeader,
    Card,
    Form,
    FormGroup,
    Input,
    Select,
    PasswordInput,
    Switch,
    ImagePicker,
    Button,
    BackButton
} from '@cambosoftware/cambo-admin'

defineProps({
    roles: Array
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_id: '',
    status: 'active',
    send_welcome_email: true,
    avatar: null
})

const statusOptions = [
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' },
    { value: 'pending', label: 'Pending' }
]

const submit = () => {
    form.post(route('users.store'))
}
</script>

<template>
    <AdminLayout title="Create User">
        <PageHeader
            title="Create User"
            :breadcrumb="[
                { label: 'Users', href: route('users.index') },
                { label: 'Create' }
            ]"
        >
            <template #actions>
                <BackButton :href="route('users.index')" />
            </template>
        </PageHeader>

        <div class="max-w-3xl">
            <Form :form="form" @submit="submit">
                <!-- Basic Information -->
                <Card title="Basic Information" class="mb-6">
                    <div class="p-6 space-y-6">
                        <!-- Avatar -->
                        <FormGroup label="Profile Photo">
                            <ImagePicker
                                v-model="form.avatar"
                                ratio="square"
                                :max-size="2"
                            />
                            <p class="text-xs text-gray-500 mt-1">
                                Recommended: Square image, at least 200x200px
                            </p>
                        </FormGroup>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <FormGroup label="Full Name" :error="form.errors.name" required>
                                <Input
                                    v-model="form.name"
                                    placeholder="John Doe"
                                    autofocus
                                />
                            </FormGroup>

                            <FormGroup label="Email Address" :error="form.errors.email" required>
                                <Input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="john@example.com"
                                />
                            </FormGroup>
                        </div>
                    </div>
                </Card>

                <!-- Account Settings -->
                <Card title="Account Settings" class="mb-6">
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <FormGroup label="Password" :error="form.errors.password" required>
                                <PasswordInput
                                    v-model="form.password"
                                    strength-meter
                                    placeholder="Minimum 8 characters"
                                />
                            </FormGroup>

                            <FormGroup
                                label="Confirm Password"
                                :error="form.errors.password_confirmation"
                                required
                            >
                                <PasswordInput
                                    v-model="form.password_confirmation"
                                    placeholder="Re-enter password"
                                />
                            </FormGroup>

                            <FormGroup label="Role" :error="form.errors.role_id" required>
                                <Select
                                    v-model="form.role_id"
                                    :options="roles.map(r => ({ value: r.id, label: r.name }))"
                                    placeholder="Select a role"
                                />
                            </FormGroup>

                            <FormGroup label="Status" :error="form.errors.status" required>
                                <Select
                                    v-model="form.status"
                                    :options="statusOptions"
                                />
                            </FormGroup>
                        </div>

                        <Switch
                            v-model="form.send_welcome_email"
                            label="Send welcome email"
                            description="User will receive an email with their login credentials"
                        />
                    </div>
                </Card>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3">
                    <Button
                        variant="outline"
                        :href="route('users.index')"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        variant="primary"
                        :loading="form.processing"
                    >
                        Create User
                    </Button>
                </div>
            </Form>
        </div>
    </AdminLayout>
</template>
```

## With Additional Fields

```vue
<Card title="Additional Information" class="mb-6">
    <div class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormGroup label="Phone Number" :error="form.errors.phone">
                <PhoneInput v-model="form.phone" />
            </FormGroup>

            <FormGroup label="Department" :error="form.errors.department_id">
                <Select
                    v-model="form.department_id"
                    :options="departments"
                    placeholder="Select department"
                />
            </FormGroup>

            <FormGroup label="Job Title" :error="form.errors.job_title">
                <Input v-model="form.job_title" placeholder="Software Engineer" />
            </FormGroup>

            <FormGroup label="Timezone" :error="form.errors.timezone">
                <Select
                    v-model="form.timezone"
                    :options="timezones"
                />
            </FormGroup>
        </div>

        <FormGroup label="Bio" :error="form.errors.bio">
            <Textarea
                v-model="form.bio"
                :rows="4"
                placeholder="A short bio about the user..."
            />
        </FormGroup>
    </div>
</Card>
```

## Permissions Section

```vue
<Card title="Permissions" class="mb-6">
    <div class="p-6">
        <p class="text-sm text-gray-600 mb-4">
            Select the permissions this user should have. These override role permissions.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
                v-for="permission in permissions"
                :key="permission.id"
                class="p-4 border border-gray-200 rounded-lg"
            >
                <Checkbox
                    v-model="form.permissions"
                    :value="permission.id"
                    :label="permission.name"
                />
                <p class="text-xs text-gray-500 mt-1 ml-6">
                    {{ permission.description }}
                </p>
            </div>
        </div>
    </div>
</Card>
```
