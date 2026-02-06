<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Form from '@/Components/Form/Form.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import PasswordInput from '@/Components/Form/PasswordInput.vue'
import Select from '@/Components/Form/Select.vue'
import ImagePicker from '@/Components/Form/ImagePicker.vue'
import Button from '@/Components/UI/Button.vue'
import BackButton from '@/Components/Navigation/BackButton.vue'

defineProps({
    roles: {
        type: Array,
        default: () => ['admin', 'user', 'editor']
    }
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
    avatar: null
})

const submit = () => {
    form.post('/users')
}
</script>

<template>
    <AdminLayout title="Nouvel utilisateur">
        <PageHeader title="Nouvel utilisateur" subtitle="Créez un nouvel utilisateur">
            <template #breadcrumbs>
                <BackButton href="/users" />
            </template>
        </PageHeader>

        <div class="max-w-2xl">
            <Card>
                <Form @submit="submit" :errors="form.errors">
                    <div class="space-y-6">
                        <!-- Avatar -->
                        <FormGroup label="Photo de profil" name="avatar">
                            <ImagePicker
                                v-model="form.avatar"
                                accept="image/*"
                                :max-size="2"
                                :error="form.errors.avatar"
                            />
                        </FormGroup>

                        <!-- Name & Email -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <FormGroup label="Nom complet" name="name" required>
                                <Input
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Jean Dupont"
                                    :error="form.errors.name"
                                />
                            </FormGroup>

                            <FormGroup label="Adresse email" name="email" required>
                                <Input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="jean@exemple.com"
                                    :error="form.errors.email"
                                />
                            </FormGroup>
                        </div>

                        <!-- Password -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <FormGroup label="Mot de passe" name="password" required>
                                <PasswordInput
                                    v-model="form.password"
                                    placeholder="••••••••"
                                    :error="form.errors.password"
                                />
                            </FormGroup>

                            <FormGroup label="Confirmer le mot de passe" name="password_confirmation" required>
                                <PasswordInput
                                    v-model="form.password_confirmation"
                                    placeholder="••••••••"
                                    :error="form.errors.password_confirmation"
                                />
                            </FormGroup>
                        </div>

                        <!-- Role -->
                        <FormGroup label="Rôle" name="role" required>
                            <Select
                                v-model="form.role"
                                :options="roles.map(r => ({ label: r.charAt(0).toUpperCase() + r.slice(1), value: r }))"
                                :error="form.errors.role"
                            />
                        </FormGroup>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <Button href="/users" variant="secondary">
                                Annuler
                            </Button>
                            <Button
                                type="submit"
                                variant="primary"
                                :loading="form.processing"
                            >
                                Créer l'utilisateur
                            </Button>
                        </div>
                    </div>
                </Form>
            </Card>
        </div>
    </AdminLayout>
</template>
