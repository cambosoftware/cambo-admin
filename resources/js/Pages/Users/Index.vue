<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Table from '@/Components/Data/Table.vue'
import TableHead from '@/Components/Data/TableHead.vue'
import TableBody from '@/Components/Data/TableBody.vue'
import TableRow from '@/Components/Data/TableRow.vue'
import TableCell from '@/Components/Data/TableCell.vue'
import SortableHeader from '@/Components/Data/SortableHeader.vue'
import Pagination from '@/Components/Data/Pagination.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import Avatar from '@/Components/UI/Avatar.vue'
import SearchInput from '@/Components/Form/SearchInput.vue'
import Dropdown from '@/Components/Overlays/Dropdown.vue'
import DropdownItem from '@/Components/Overlays/DropdownItem.vue'
import DropdownDivider from '@/Components/Overlays/DropdownDivider.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import EmptyState from '@/Components/Feedback/EmptyState.vue'
import { PlusIcon, EyeIcon, PencilIcon, TrashIcon, EllipsisVerticalIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    users: {
        type: Object,
        required: true
        // { data: [], meta: { current_page, last_page, per_page, total } }
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

// State
const search = ref(props.filters.search || '')
const sort = ref(props.filters.sort || 'name')
const sortDir = ref(props.filters.direction || 'asc')
const deleteModal = ref(false)
const userToDelete = ref(null)

// Methods
const handleSearch = (value) => {
    router.get('/admin/users', { search: value, sort: sort.value, direction: sortDir.value }, {
        preserveState: true,
        replace: true
    })
}

const handleSort = ({ column, direction }) => {
    sort.value = column
    sortDir.value = direction
    router.get('/admin/users', { search: search.value, sort: column, direction }, {
        preserveState: true,
        replace: true
    })
}

const handlePageChange = (page) => {
    router.get('/admin/users', { ...props.filters, page }, {
        preserveState: true,
        replace: true
    })
}

const confirmDelete = (user) => {
    userToDelete.value = user
    deleteModal.value = true
}

const deleteUser = () => {
    if (userToDelete.value) {
        router.delete(`/admin/users/${userToDelete.value.id}`, {
            onSuccess: () => {
                deleteModal.value = false
                userToDelete.value = null
            }
        })
    }
}

const statusVariant = (status) => {
    const variants = {
        active: 'success',
        inactive: 'secondary',
        pending: 'warning',
        banned: 'danger'
    }
    return variants[status] || 'secondary'
}
</script>

<template>
    <AdminLayout title="Utilisateurs">
        <PageHeader title="Utilisateurs" subtitle="Gérez les utilisateurs de votre application">
            <template #actions>
                <Button :href="'/admin/users/create'" variant="primary" :icon="PlusIcon">
                    Nouvel utilisateur
                </Button>
            </template>
        </PageHeader>

        <Card :padding="false">
            <!-- Toolbar -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row gap-4 justify-between">
                    <SearchInput
                        v-model="search"
                        placeholder="Rechercher un utilisateur..."
                        class="w-full sm:w-64"
                        @search="handleSearch"
                    />
                </div>
            </div>

            <!-- Table -->
            <Table hoverable>
                <TableHead>
                    <tr>
                        <SortableHeader
                            column="name"
                            :current-sort="sort"
                            :current-direction="sortDir"
                            @sort="handleSort"
                        >
                            Utilisateur
                        </SortableHeader>
                        <SortableHeader
                            column="email"
                            :current-sort="sort"
                            :current-direction="sortDir"
                            @sort="handleSort"
                        >
                            Email
                        </SortableHeader>
                        <SortableHeader
                            column="role"
                            :current-sort="sort"
                            :current-direction="sortDir"
                            @sort="handleSort"
                        >
                            Rôle
                        </SortableHeader>
                        <SortableHeader
                            column="status"
                            :current-sort="sort"
                            :current-direction="sortDir"
                            @sort="handleSort"
                            filterable
                            :filter-options="[
                                { label: 'Actif', value: 'active' },
                                { label: 'Inactif', value: 'inactive' },
                                { label: 'En attente', value: 'pending' }
                            ]"
                            :filter-value="filters.status"
                        >
                            Statut
                        </SortableHeader>
                        <SortableHeader
                            column="created_at"
                            :current-sort="sort"
                            :current-direction="sortDir"
                            @sort="handleSort"
                        >
                            Créé le
                        </SortableHeader>
                        <TableCell header align="right">Actions</TableCell>
                    </tr>
                </TableHead>
                <TableBody>
                    <template v-if="users.data.length">
                        <TableRow v-for="user in users.data" :key="user.id">
                            <TableCell>
                                <div class="flex items-center gap-3">
                                    <Avatar :src="user.avatar" :name="user.name" size="sm" />
                                    <span class="font-medium text-gray-900">{{ user.name }}</span>
                                </div>
                            </TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell>
                                <Badge variant="primary" outline>{{ user.role }}</Badge>
                            </TableCell>
                            <TableCell>
                                <Badge :variant="statusVariant(user.status)">{{ user.status }}</Badge>
                            </TableCell>
                            <TableCell>{{ user.created_at }}</TableCell>
                            <TableCell align="right">
                                <Dropdown position="bottom-end">
                                    <template #trigger>
                                        <button class="p-1 rounded-lg hover:bg-gray-100">
                                            <EllipsisVerticalIcon class="h-5 w-5 text-gray-500" />
                                        </button>
                                    </template>
                                    <DropdownItem :href="`/admin/users/${user.id}`" :icon="EyeIcon">
                                        Voir
                                    </DropdownItem>
                                    <DropdownItem :href="`/admin/users/${user.id}/edit`" :icon="PencilIcon">
                                        Modifier
                                    </DropdownItem>
                                    <DropdownDivider />
                                    <DropdownItem :icon="TrashIcon" variant="danger" @click="confirmDelete(user)">
                                        Supprimer
                                    </DropdownItem>
                                </Dropdown>
                            </TableCell>
                        </TableRow>
                    </template>
                    <tr v-else>
                        <td colspan="6">
                            <EmptyState
                                title="Aucun utilisateur"
                                description="Commencez par créer votre premier utilisateur."
                                class="py-12"
                            >
                                <Button :href="'/admin/users/create'" variant="primary" :icon="PlusIcon">
                                    Nouvel utilisateur
                                </Button>
                            </EmptyState>
                        </td>
                    </tr>
                </TableBody>
            </Table>

            <!-- Pagination -->
            <div v-if="users.meta && users.meta.last_page > 1" class="p-4 border-t border-gray-200">
                <Pagination
                    :current-page="users.meta.current_page"
                    :total-pages="users.meta.last_page"
                    :total-items="users.meta.total"
                    :per-page="users.meta.per_page"
                    @update:current-page="handlePageChange"
                />
            </div>
        </Card>

        <!-- Delete Confirmation Modal -->
        <ConfirmModal
            v-model="deleteModal"
            title="Supprimer l'utilisateur"
            :message="`Êtes-vous sûr de vouloir supprimer ${userToDelete?.name} ? Cette action est irréversible.`"
            confirm-text="Supprimer"
            variant="danger"
            @confirm="deleteUser"
        />
    </AdminLayout>
</template>
