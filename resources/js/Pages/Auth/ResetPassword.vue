<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import Form from '@/Components/Form/Form.vue'
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
        title="Réinitialiser le mot de passe"
        subtitle="Choisissez un nouveau mot de passe"
    >
        <Form @submit="submit" :errors="form.errors">
            <div class="space-y-5">
                <FormGroup label="Adresse email" name="email" required>
                    <Input
                        v-model="form.email"
                        type="email"
                        autocomplete="email"
                        :error="form.errors.email"
                        readonly
                    />
                </FormGroup>

                <FormGroup label="Nouveau mot de passe" name="password" required>
                    <PasswordInput
                        v-model="form.password"
                        placeholder="••••••••"
                        autocomplete="new-password"
                        :error="form.errors.password"
                        autofocus
                    />
                </FormGroup>

                <FormGroup label="Confirmer le mot de passe" name="password_confirmation" required>
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
                    Réinitialiser le mot de passe
                </Button>
            </div>
        </Form>

        <template #footer>
            <p class="text-center text-sm text-gray-600">
                <Link href="/login" class="font-medium text-primary-600 hover:text-primary-500">
                    Retour à la connexion
                </Link>
            </p>
        </template>
    </GuestLayout>
</template>
