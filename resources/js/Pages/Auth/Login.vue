<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import PasswordInput from '@/Components/Form/PasswordInput.vue'
import Checkbox from '@/Components/Form/Checkbox.vue'
import Button from '@/Components/UI/Button.vue'
import Alert from '@/Components/Feedback/Alert.vue'

defineProps({
    canResetPassword: {
        type: Boolean,
        default: true
    },
    canRegister: {
        type: Boolean,
        default: true
    },
    status: {
        type: String,
        default: null
    }
})

const form = useForm({
    email: '',
    password: '',
    remember: false
})

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password')
    })
}
</script>

<template>
    <GuestLayout title="Sign In" subtitle="Access your account">
        <!-- Status message (e.g., after password reset) -->
        <Alert v-if="status" variant="success" class="mb-6">
            {{ status }}
        </Alert>

        <form @submit.prevent="submit" class="space-y-5">
            <FormGroup label="Email Address" :error="form.errors.email" required>
                <Input
                    v-model="form.email"
                    type="email"
                    placeholder="you@example.com"
                    autocomplete="email"
                    :error="form.errors.email"
                    autofocus
                />
            </FormGroup>

            <FormGroup label="Password" :error="form.errors.password" required>
                <PasswordInput
                    v-model="form.password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    :error="form.errors.password"
                />
            </FormGroup>

            <div class="flex items-center justify-between">
                <Checkbox
                    v-model="form.remember"
                    label="Remember me"
                    size="sm"
                />

                <Link
                    v-if="canResetPassword"
                    href="/forgot-password"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                >
                    Forgot password?
                </Link>
            </div>

            <Button
                type="submit"
                variant="primary"
                class="w-full"
                size="lg"
                :loading="form.processing"
            >
                Sign In
            </Button>
        </form>

        <template #footer>
            <p v-if="canRegister" class="text-center text-sm text-gray-600 dark:text-gray-400">
                Don't have an account?
                <Link href="/register" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Create an account
                </Link>
            </p>
        </template>
    </GuestLayout>
</template>
