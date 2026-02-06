<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import Form from '@/Components/Form/Form.vue'
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
        title="Mot de passe oublié"
        subtitle="Entrez votre email pour recevoir un lien de réinitialisation"
    >
        <!-- Success message -->
        <Alert v-if="status" variant="success" class="mb-6">
            {{ status }}
        </Alert>

        <Form @submit="submit" :errors="form.errors">
            <div class="space-y-5">
                <FormGroup label="Adresse email" name="email" required>
                    <Input
                        v-model="form.email"
                        type="email"
                        placeholder="vous@exemple.com"
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
                    Envoyer le lien
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
