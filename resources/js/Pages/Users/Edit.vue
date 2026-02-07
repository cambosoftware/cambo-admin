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
import Switch from '@/Components/Form/Switch.vue'
import Button from '@/Components/UI/Button.vue'
import BackButton from '@/Components/Navigation/BackButton.vue'
import Avatar from '@/Components/UI/Avatar.vue'
import Alert from '@/Components/Feedback/Alert.vue'

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    roles: {
        type: Array,
        default: () => ['admin', 'user', 'editor']
    }
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.role,
    avatar: null,
    is_active: props.user.status === 'active'
})

const submit = () => {
    form.put(`/admin/users/${props.user.id}`)
}
</script>

<template>
    <AdminLayout title="Modifier l'utilisateur">
        <PageHeader :title="user.name" subtitle="Modifiez les informations de l'utilisateur">
            <template #breadcrumbs>
                <BackButton href="/admin/users" />
            </template>
        </PageHeader>

        <div class="max-w-2xl">
            <Card>
                <Form @submit="submit" :errors="form.errors">
                    <div class="space-y-6">
                        <!-- Avatar -->
                        <FormGroup label="Photo de profil" name="avatar">
                            <div class="flex items-center gap-4">
                                <Avatar
                                    :src="user.avatar"
                                    :name="user.name"
                                    size="xl"
                                />
                                <div>
                                    <ImagePicker
                                        v-model="form.avatar"
                                        accept="image/*"
                                        :max-size="2"
                                        :error="form.errors.avatar"
                                    >
                                        <Button type="button" variant="secondary" size="sm">
                                            Changer la photo
                                        </Button>
                                    </ImagePicker>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG. Max 2Mo</p>
                                </div>
                            </div>
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
                        <Alert variant="info">
                            Laissez les champs de mot de passe vides si vous ne souhaitez pas le modifier.
                        </Alert>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <FormGroup label="Nouveau mot de passe" name="password">
                                <PasswordInput
                                    v-model="form.password"
                                    placeholder="••••••••"
                                    :error="form.errors.password"
                                />
                            </FormGroup>

                            <FormGroup label="Confirmer le mot de passe" name="password_confirmation">
                                <PasswordInput
                                    v-model="form.password_confirmation"
                                    placeholder="••••••••"
                                    :error="form.errors.password_confirmation"
                                />
                            </FormGroup>
                        </div>

                        <!-- Role & Status -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <FormGroup label="Rôle" name="role" required>
                                <Select
                                    v-model="form.role"
                                    :options="roles.map(r => ({ label: r.charAt(0).toUpperCase() + r.slice(1), value: r }))"
                                    :error="form.errors.role"
                                />
                            </FormGroup>

                            <FormGroup label="Statut" name="is_active">
                                <div class="pt-2">
                                    <Switch
                                        v-model="form.is_active"
                                        label="Compte actif"
                                    />
                                </div>
                            </FormGroup>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <Button href="/admin/users" variant="secondary">
                                Annuler
                            </Button>
                            <Button
                                type="submit"
                                variant="primary"
                                :loading="form.processing"
                            >
                                Enregistrer les modifications
                            </Button>
                        </div>
                    </div>
                </Form>
            </Card>
        </div>
    </AdminLayout>
</template>
