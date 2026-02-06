<script setup>
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import Form from '@/Components/Form/Form.vue'
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
    <GuestLayout title="Connexion" subtitle="Accédez à votre espace">
        <!-- Status message (e.g., after password reset) -->
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

                <FormGroup label="Mot de passe" name="password" required>
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
                        label="Se souvenir de moi"
                        size="sm"
                    />

                    <Link
                        v-if="canResetPassword"
                        href="/forgot-password"
                        class="text-sm font-medium text-primary-600 hover:text-primary-500"
                    >
                        Mot de passe oublié ?
                    </Link>
                </div>

                <Button
                    type="submit"
                    variant="primary"
                    class="w-full"
                    size="lg"
                    :loading="form.processing"
                >
                    Se connecter
                </Button>
            </div>
        </Form>

        <template #footer>
            <p v-if="canRegister" class="text-center text-sm text-gray-600">
                Pas encore de compte ?
                <Link href="/register" class="font-medium text-primary-600 hover:text-primary-500">
                    Créer un compte
                </Link>
            </p>
        </template>
    </GuestLayout>
</template>
