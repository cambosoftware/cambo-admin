<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import Form from '@/Components/Form/Form.vue'
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
    <GuestLayout title="Créer un compte" subtitle="Rejoignez-nous dès maintenant" max-width="md">
        <Form @submit="submit" :errors="form.errors">
            <div class="space-y-5">
                <FormGroup label="Nom complet" name="name" required>
                    <Input
                        v-model="form.name"
                        type="text"
                        placeholder="Jean Dupont"
                        autocomplete="name"
                        :error="form.errors.name"
                        autofocus
                    />
                </FormGroup>

                <FormGroup label="Adresse email" name="email" required>
                    <Input
                        v-model="form.email"
                        type="email"
                        placeholder="vous@exemple.com"
                        autocomplete="email"
                        :error="form.errors.email"
                    />
                </FormGroup>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <FormGroup label="Mot de passe" name="password" required>
                        <PasswordInput
                            v-model="form.password"
                            placeholder="••••••••"
                            autocomplete="new-password"
                            :error="form.errors.password"
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
                </div>

                <div class="pt-2">
                    <Checkbox
                        v-model="form.terms"
                        :error="form.errors.terms"
                    >
                        <template #default>
                            <span class="text-sm text-gray-600">
                                J'accepte les
                                <a href="/terms" target="_blank" class="text-primary-600 hover:text-primary-500">
                                    conditions d'utilisation
                                </a>
                                et la
                                <a href="/privacy" target="_blank" class="text-primary-600 hover:text-primary-500">
                                    politique de confidentialité
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
                    Créer mon compte
                </Button>
            </div>
        </Form>

        <template #footer>
            <p class="text-center text-sm text-gray-600">
                Déjà un compte ?
                <Link href="/login" class="font-medium text-primary-600 hover:text-primary-500">
                    Se connecter
                </Link>
            </p>
        </template>
    </GuestLayout>
</template>
