<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Form from '@/Components/Form/Form.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import Textarea from '@/Components/Form/Textarea.vue'
import Checkbox from '@/Components/Form/Checkbox.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import Divider from '@/Components/UI/Divider.vue'
import Alert from '@/Components/Feedback/Alert.vue'

const props = defineProps({
    role: {
        type: Object,
        required: true
    },
    permissions: {
        type: Object,
        default: () => ({})
    }
})

const form = useForm({
    name: props.role.name,
    slug: props.role.slug,
    description: props.role.description || '',
    permissions: props.role.permissions || []
})

const submit = () => {
    form.put(`/admin/roles/${props.role.id}`)
}

const toggleGroup = (group, perms) => {
    const slugs = perms.map(p => p.slug)
    const allSelected = slugs.every(s => form.permissions.includes(s))

    if (allSelected) {
        form.permissions = form.permissions.filter(p => !slugs.includes(p))
    } else {
        form.permissions = [...new Set([...form.permissions, ...slugs])]
    }
}

const isGroupSelected = (perms) => {
    const slugs = perms.map(p => p.slug)
    return slugs.every(s => form.permissions.includes(s))
}

const isGroupPartial = (perms) => {
    const slugs = perms.map(p => p.slug)
    return slugs.some(s => form.permissions.includes(s)) && !slugs.every(s => form.permissions.includes(s))
}
</script>

<template>
    <AdminLayout :title="`Modifier ${role.name}`">
        <PageHeader
            :title="`Modifier le rôle`"
            :subtitle="role.name"
        >
            <template #actions>
                <Link href="/admin/roles">
                    <Button variant="secondary">Retour</Button>
                </Link>
            </template>
        </PageHeader>

        <Alert v-if="role.slug === 'super-admin'" variant="warning" class="mb-6">
            Le rôle Super Administrateur a automatiquement accès à toutes les permissions.
            Les permissions sélectionnées ici ne sont pas appliquées.
        </Alert>

        <Form @submit="submit" :errors="form.errors">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main info -->
                <Card title="Informations" class="lg:col-span-1">
                    <div class="space-y-4">
                        <FormGroup label="Nom du rôle" name="name" required>
                            <Input
                                v-model="form.name"
                                placeholder="Ex: Éditeur"
                                :error="form.errors.name"
                            />
                        </FormGroup>

                        <FormGroup label="Slug" name="slug" hint="Identifiant unique">
                            <Input
                                v-model="form.slug"
                                placeholder="Ex: editor"
                                :error="form.errors.slug"
                                :disabled="role.slug === 'super-admin'"
                            />
                        </FormGroup>

                        <FormGroup label="Description" name="description">
                            <Textarea
                                v-model="form.description"
                                placeholder="Description du rôle..."
                                :rows="3"
                                :error="form.errors.description"
                            />
                        </FormGroup>

                        <div v-if="role.is_default" class="pt-2">
                            <Badge variant="primary">Rôle par défaut</Badge>
                        </div>
                    </div>
                </Card>

                <!-- Permissions -->
                <Card title="Permissions" class="lg:col-span-2">
                    <div class="space-y-6">
                        <div
                            v-for="(perms, group) in permissions"
                            :key="group"
                            class="space-y-3"
                        >
                            <div class="flex items-center gap-3">
                                <Checkbox
                                    :model-value="isGroupSelected(perms)"
                                    :indeterminate="isGroupPartial(perms)"
                                    @update:model-value="toggleGroup(group, perms)"
                                    :label="group"
                                    class="font-semibold"
                                    :disabled="role.slug === 'super-admin'"
                                />
                            </div>

                            <div class="ml-6 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <Checkbox
                                    v-for="permission in perms"
                                    :key="permission.slug"
                                    v-model="form.permissions"
                                    :value="permission.slug"
                                    :label="permission.name"
                                    :disabled="role.slug === 'super-admin'"
                                />
                            </div>

                            <Divider />
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Submit -->
            <div class="mt-6 flex justify-end gap-3">
                <Link href="/admin/roles">
                    <Button variant="secondary">Annuler</Button>
                </Link>
                <Button type="submit" variant="primary" :loading="form.processing">
                    Enregistrer
                </Button>
            </div>
        </Form>
    </AdminLayout>
</template>
