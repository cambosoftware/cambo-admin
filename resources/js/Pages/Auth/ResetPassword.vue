<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import PasswordInput from '@/Components/Form/PasswordInput.vue'
import Button from '@/Components/UI/Button.vue'

const props = defineProps({
    email: {
        type: String,
        required: true
    },
    token: {
        type: String,
        required: true
    }
})

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: ''
})

const submit = () => {
    form.post('/reset-password', {
        onFinish: () => form.reset('password', 'password_confirmation')
    })
}
</script>

<template>
    <GuestLayout
        title="Reset Password"
        subtitle="Choose a new password"
    >
        <form @submit.prevent="submit" class="space-y-5">
            <FormGroup label="Email Address" :error="form.errors.email" required>
                <Input
                    v-model="form.email"
                    type="email"
                    autocomplete="email"
                    :error="form.errors.email"
                    readonly
                />
            </FormGroup>

            <FormGroup label="New Password" :error="form.errors.password" required>
                <PasswordInput
                    v-model="form.password"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    :error="form.errors.password"
                    autofocus
                />
            </FormGroup>

            <FormGroup label="Confirm Password" :error="form.errors.password_confirmation" required>
                <PasswordInput
                    v-model="form.password_confirmation"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    :error="form.errors.password_confirmation"
                />
            </FormGroup>

            <Button
                type="submit"
                variant="primary"
                class="w-full"
                size="lg"
                :loading="form.processing"
            >
                Reset Password
            </Button>
        </form>

        <template #footer>
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                <Link href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Back to Sign In
                </Link>
            </p>
        </template>
    </GuestLayout>
</template>
