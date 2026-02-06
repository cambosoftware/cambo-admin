<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import Avatar from '@/Components/UI/Avatar.vue'
import BackButton from '@/Components/Navigation/BackButton.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import Divider from '@/Components/UI/Divider.vue'
import { PencilIcon, TrashIcon, EnvelopeIcon, CalendarIcon, ShieldCheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

const deleteModal = ref(false)

const statusVariant = (status) => {
    const variants = {
        active: 'success',
        inactive: 'secondary',
        pending: 'warning',
        banned: 'danger'
    }
    return variants[status] || 'secondary'
}

const deleteUser = () => {
    router.delete(`/users/${props.user.id}`, {
        onSuccess: () => {
            deleteModal.value = false
        }
    })
}
</script>

<template>
    <AdminLayout :title="user.name">
        <PageHeader :title="user.name" :subtitle="`Détails de l'utilisateur #${user.id}`">
            <template #breadcrumbs>
                <BackButton href="/users" />
            </template>
            <template #actions>
                <Button :href="`/users/${user.id}/edit`" variant="secondary" :icon="PencilIcon">
                    Modifier
                </Button>
                <Button variant="danger" :icon="TrashIcon" @click="deleteModal = true">
                    Supprimer
                </Button>
            </template>
        </PageHeader>

        <div class="max-w-4xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <Card class="lg:col-span-1">
                    <div class="text-center">
                        <Avatar
                            :src="user.avatar"
                            :name="user.name"
                            size="2xl"
                            class="mx-auto"
                        />
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ user.name }}</h3>
                        <p class="text-sm text-gray-500">{{ user.email }}</p>

                        <div class="mt-4 flex justify-center gap-2">
                            <Badge :variant="statusVariant(user.status)">
                                {{ user.status }}
                            </Badge>
                            <Badge variant="primary" outline>
                                {{ user.role }}
                            </Badge>
                        </div>
                    </div>

                    <Divider class="my-6" />

                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-sm">
                            <EnvelopeIcon class="h-5 w-5 text-gray-400" />
                            <span class="text-gray-600">{{ user.email }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <ShieldCheckIcon class="h-5 w-5 text-gray-400" />
                            <span class="text-gray-600">{{ user.role }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <CalendarIcon class="h-5 w-5 text-gray-400" />
                            <span class="text-gray-600">Inscrit le {{ user.created_at }}</span>
                        </div>
                    </div>
                </Card>

                <!-- Details Card -->
                <Card title="Informations" class="lg:col-span-2">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">#{{ user.id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ user.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ user.email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Rôle</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ user.role }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Statut</dt>
                            <dd class="mt-1">
                                <Badge :variant="statusVariant(user.status)">{{ user.status }}</Badge>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email vérifié</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ user.email_verified_at ? 'Oui' : 'Non' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ user.created_at }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dernière mise à jour</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ user.updated_at }}</dd>
                        </div>
                    </dl>
                </Card>

                <!-- Activity Card (placeholder) -->
                <Card title="Activité récente" class="lg:col-span-3">
                    <div class="text-center py-8 text-gray-500">
                        <p>Aucune activité récente</p>
                    </div>
                </Card>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <ConfirmModal
            v-model="deleteModal"
            title="Supprimer l'utilisateur"
            :message="`Êtes-vous sûr de vouloir supprimer ${user.name} ? Cette action est irréversible.`"
            confirm-text="Supprimer"
            variant="danger"
            @confirm="deleteUser"
        />
    </AdminLayout>
</template>
