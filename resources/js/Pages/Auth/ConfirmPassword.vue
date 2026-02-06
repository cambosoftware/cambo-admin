<script setup>
import { useForm } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import Form from '@/Components/Form/Form.vue'
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
        title="Confirmation requise"
        subtitle="Ceci est une zone sécurisée. Veuillez confirmer votre mot de passe avant de continuer."
    >
        <Form @submit="submit" :errors="form.errors">
            <div class="space-y-5">
                <FormGroup label="Mot de passe" name="password" required>
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
                    Confirmer
                </Button>
            </div>
        </Form>
    </GuestLayout>
</template>
