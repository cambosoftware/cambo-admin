<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Table from '@/Components/Data/Table.vue'
import TableHead from '@/Components/Data/TableHead.vue'
import TableBody from '@/Components/Data/TableBody.vue'
import TableRow from '@/Components/Data/TableRow.vue'
import TableCell from '@/Components/Data/TableCell.vue'
import Pagination from '@/Components/Data/Pagination.vue'
import SearchInput from '@/Components/Form/SearchInput.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import Dropdown from '@/Components/Overlays/Dropdown.vue'
import DropdownItem from '@/Components/Overlays/DropdownItem.vue'
import DropdownDivider from '@/Components/Overlays/DropdownDivider.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import Alert from '@/Components/Feedback/Alert.vue'
import {
    PlusIcon,
    PencilIcon,
    TrashIcon,
    EyeIcon,
    EllipsisVerticalIcon,
    StarIcon,
    ShieldCheckIcon
} from '@heroicons/vue/24/outline'
import { StarIcon as StarIconSolid } from '@heroicons/vue/24/solid'

const props = defineProps({
    roles: Object,
    filters: Object,
})

const search = ref(props.filters?.search || '')
const deleteModal = ref(false)
const roleToDelete = ref(null)

const doSearch = () => {
    router.get('/roles', { search: search.value }, {
        preserveState: true,
        replace: true,
    })
}

const confirmDelete = (role) => {
    roleToDelete.value = role
    deleteModal.value = true
}

const deleteRole = () => {
    router.delete(`/roles/${roleToDelete.value.id}`, {
        onSuccess: () => {
            deleteModal.value = false
            roleToDelete.value = null
        }
    })
}

const setDefault = (role) => {
    router.post(`/roles/${role.id}/set-default`)
}
</script>

<template>
    <AdminLayout title="Rôles">
        <PageHeader
            title="Gestion des rôles"
            subtitle="Gérez les rôles et leurs permissions"
        >
            <template #actions>
                <Link href="/roles/create">
                    <Button variant="primary" :icon-left="PlusIcon">
                        Nouveau rôle
                    </Button>
                </Link>
            </template>
        </PageHeader>

        <Card>
            <!-- Filters -->
            <div class="mb-4">
                <SearchInput
                    v-model="search"
                    placeholder="Rechercher un rôle..."
                    @search="doSearch"
                    class="max-w-sm"
                />
            </div>

            <!-- Table -->
            <Table>
                <TableHead>
                    <TableCell header>Rôle</TableCell>
                    <TableCell header>Description</TableCell>
                    <TableCell header class="text-center">Utilisateurs</TableCell>
                    <TableCell header class="text-center">Permissions</TableCell>
                    <TableCell header class="text-right">Actions</TableCell>
                </TableHead>
                <TableBody>
                    <TableRow v-for="role in roles.data" :key="role.id">
                        <TableCell>
                            <div class="flex items-center gap-2">
                                <ShieldCheckIcon class="h-5 w-5 text-gray-400" />
                                <div>
                                    <div class="font-medium text-gray-900 flex items-center gap-2">
                                        {{ role.name }}
                                        <Badge v-if="role.is_default" variant="primary" size="sm">
                                            Par défaut
                                        </Badge>
                                        <Badge v-if="role.slug === 'super-admin'" variant="warning" size="sm">
                                            Super Admin
                                        </Badge>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ role.slug }}</div>
                                </div>
                            </div>
                        </TableCell>
                        <TableCell>
                            <span class="text-sm text-gray-500">
                                {{ role.description || '-' }}
                            </span>
                        </TableCell>
                        <TableCell class="text-center">
                            <Badge variant="secondary">
                                {{ role.users_count }}
                            </Badge>
                        </TableCell>
                        <TableCell class="text-center">
                            <Badge variant="info">
                                {{ role.permissions_count }}
                            </Badge>
                        </TableCell>
                        <TableCell class="text-right">
                            <Dropdown align="right">
                                <template #trigger>
                                    <Button variant="ghost" size="sm">
                                        <EllipsisVerticalIcon class="h-5 w-5" />
                                    </Button>
                                </template>

                                <DropdownItem :href="`/roles/${role.id}`" :icon="EyeIcon">
                                    Voir
                                </DropdownItem>
                                <DropdownItem :href="`/roles/${role.id}/edit`" :icon="PencilIcon">
                                    Modifier
                                </DropdownItem>
                                <DropdownItem
                                    v-if="!role.is_default"
                                    @click="setDefault(role)"
                                    :icon="StarIcon"
                                >
                                    Définir par défaut
                                </DropdownItem>
                                <DropdownDivider />
                                <DropdownItem
                                    v-if="role.slug !== 'super-admin'"
                                    @click="confirmDelete(role)"
                                    :icon="TrashIcon"
                                    variant="danger"
                                >
                                    Supprimer
                                </DropdownItem>
                            </Dropdown>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Pagination -->
            <div class="mt-4">
                <Pagination :data="roles" />
            </div>
        </Card>

        <!-- Delete Confirmation Modal -->
        <ConfirmModal
            v-model="deleteModal"
            title="Supprimer le rôle"
            :message="`Êtes-vous sûr de vouloir supprimer le rôle '${roleToDelete?.name}' ?`"
            confirm-text="Supprimer"
            variant="danger"
            @confirm="deleteRole"
        />
    </AdminLayout>
</template>
