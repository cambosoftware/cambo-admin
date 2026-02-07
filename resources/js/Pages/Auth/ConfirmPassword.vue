<script setup>
import { useForm } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import PasswordInput from '@/Components/Form/PasswordInput.vue'
import Button from '@/Components/UI/Button.vue'

const form = useForm({
    password: ''
})

const submit = () => {
    form.post('/confirm-password', {
        onFinish: () => form.reset()
    })
}
</script>

<template>
    <GuestLayout
        title="Confirmation Required"
        subtitle="This is a secure area. Please confirm your password before continuing."
    >
        <form @submit.prevent="submit" class="space-y-5">
            <FormGroup label="Password" :error="form.errors.password" required>
                <PasswordInput
                    v-model="form.password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    :error="form.errors.password"
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
                Confirm
            </Button>
        </form>
    </GuestLayout>
</template>
