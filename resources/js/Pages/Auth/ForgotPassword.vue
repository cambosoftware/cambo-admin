<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import Button from '@/Components/UI/Button.vue'
import Alert from '@/Components/Feedback/Alert.vue'

defineProps({
    status: {
        type: String,
        default: null
    }
})

const form = useForm({
    email: ''
})

const submit = () => {
    form.post('/forgot-password')
}
</script>

<template>
    <GuestLayout
        title="Forgot Password"
        subtitle="Enter your email to receive a reset link"
    >
        <!-- Success message -->
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

            <Button
                type="submit"
                variant="primary"
                class="w-full"
                size="lg"
                :loading="form.processing"
            >
                Send Reset Link
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
