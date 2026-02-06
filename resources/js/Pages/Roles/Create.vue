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
import Divider from '@/Components/UI/Divider.vue'

const props = defineProps({
    permissions: {
        type: Object,
        default: () => ({})
    }
})

const form = useForm({
    name: '',
    slug: '',
    description: '',
    permissions: []
})

const submit = () => {
    form.post('/roles')
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
    <AdminLayout title="Créer un rôle">
        <PageHeader
            title="Créer un rôle"
            subtitle="Définissez un nouveau rôle avec ses permissions"
        >
            <template #actions>
                <Link href="/roles">
                    <Button variant="secondary">Annuler</Button>
                </Link>
            </template>
        </PageHeader>

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

                        <FormGroup label="Slug" name="slug" hint="Généré automatiquement si vide">
                            <Input
                                v-model="form.slug"
                                placeholder="Ex: editor"
                                :error="form.errors.slug"
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
                                />
                            </div>

                            <div class="ml-6 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <Checkbox
                                    v-for="permission in perms"
                                    :key="permission.slug"
                                    v-model="form.permissions"
                                    :value="permission.slug"
                                    :label="permission.name"
                                />
                            </div>

                            <Divider />
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Submit -->
            <div class="mt-6 flex justify-end gap-3">
                <Link href="/roles">
                    <Button variant="secondary">Annuler</Button>
                </Link>
                <Button type="submit" variant="primary" :loading="form.processing">
                    Créer le rôle
                </Button>
            </div>
        </Form>
    </AdminLayout>
</template>
