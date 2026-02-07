<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import PasswordInput from '@/Components/Form/PasswordInput.vue'
import Checkbox from '@/Components/Form/Checkbox.vue'
import Button from '@/Components/UI/Button.vue'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false
})

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation')
    })
}
</script>

<template>
    <GuestLayout title="Create Account" subtitle="Join us today" max-width="md">
        <form @submit.prevent="submit" class="space-y-5">
            <FormGroup label="Full Name" :error="form.errors.name" required>
                <Input
                    v-model="form.name"
                    type="text"
                    placeholder="John Doe"
                    autocomplete="name"
                    :error="form.errors.name"
                    autofocus
                />
            </FormGroup>

            <FormGroup label="Email Address" :error="form.errors.email" required>
                <Input
                    v-model="form.email"
                    type="email"
                    placeholder="you@example.com"
                    autocomplete="email"
                    :error="form.errors.email"
                />
            </FormGroup>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <FormGroup label="Password" :error="form.errors.password" required>
                    <PasswordInput
                        v-model="form.password"
                        placeholder="••••••••"
                        autocomplete="new-password"
                        :error="form.errors.password"
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
            </div>

            <div class="pt-2">
                <Checkbox
                    v-model="form.terms"
                    :error="form.errors.terms"
                >
                    <template #default>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            I agree to the
                            <a href="/terms" target="_blank" class="text-indigo-600 hover:text-indigo-500">
                                Terms of Service
                            </a>
                            and
                            <a href="/privacy" target="_blank" class="text-indigo-600 hover:text-indigo-500">
                                Privacy Policy
                            </a>
                        </span>
                    </template>
                </Checkbox>
            </div>

            <Button
                type="submit"
                variant="primary"
                class="w-full"
                size="lg"
                :loading="form.processing"
            >
                Create Account
            </Button>
        </form>

        <template #footer>
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                Already have an account?
                <Link href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Sign In
                </Link>
            </p>
        </template>
    </GuestLayout>
</template>
