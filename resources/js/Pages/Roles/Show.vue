<script setup>
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import Avatar from '@/Components/UI/Avatar.vue'
import DescriptionList from '@/Components/Data/DescriptionList.vue'
import { PencilIcon, CheckCircleIcon, XCircleIcon, ShieldCheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    role: {
        type: Object,
        required: true
    },
    permissionsGrouped: {
        type: Object,
        default: () => ({})
    }
})

const rolePermissionSlugs = props.role.permissions?.map(p => p.slug) || []

const hasPermission = (slug) => {
    if (props.role.slug === 'super-admin') return true
    return rolePermissionSlugs.includes(slug)
}
</script>

<template>
    <AdminLayout :title="role.name">
        <PageHeader
            :title="role.name"
            :subtitle="role.description || 'Aucune description'"
        >
            <template #actions>
                <Link :href="`/admin/roles/${role.id}/edit`">
                    <Button variant="primary" :icon-left="PencilIcon">
                        Modifier
                    </Button>
                </Link>
            </template>
        </PageHeader>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Info -->
            <Card title="Informations">
                <DescriptionList>
                    <div class="py-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nom</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ role.name }}</dd>
                    </div>
                    <div class="py-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ role.slug }}</dd>
                    </div>
                    <div class="py-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut</dt>
                        <dd class="mt-1 flex gap-2">
                            <Badge v-if="role.is_default" variant="primary">Par défaut</Badge>
                            <Badge v-if="role.slug === 'super-admin'" variant="warning">Super Admin</Badge>
                            <span v-if="!role.is_default && role.slug !== 'super-admin'" class="text-sm text-gray-500 dark:text-gray-400">-</span>
                        </dd>
                    </div>
                    <div class="py-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Utilisateurs</dt>
                        <dd class="mt-1">
                            <Badge variant="secondary">{{ role.users_count }} utilisateur(s)</Badge>
                        </dd>
                    </div>
                </DescriptionList>
            </Card>

            <!-- Permissions -->
            <Card title="Permissions" class="lg:col-span-2">
                <div class="space-y-6">
                    <div
                        v-for="(perms, group) in permissionsGrouped"
                        :key="group"
                    >
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">{{ group }}</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div
                                v-for="permission in perms"
                                :key="permission.slug"
                                class="flex items-center gap-2 text-sm"
                            >
                                <CheckCircleIcon
                                    v-if="hasPermission(permission.slug)"
                                    class="h-5 w-5 text-green-500"
                                />
                                <XCircleIcon
                                    v-else
                                    class="h-5 w-5 text-gray-300 dark:text-gray-600"
                                />
                                <span :class="hasPermission(permission.slug) ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500'">
                                    {{ permission.name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </Card>

            <!-- Users with this role -->
            <Card v-if="role.users?.length > 0" title="Utilisateurs" class="lg:col-span-3">
                <div class="flex flex-wrap gap-4">
                    <div
                        v-for="user in role.users"
                        :key="user.id"
                        class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
                    >
                        <Avatar :name="user.name" size="sm" />
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ user.name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</div>
                        </div>
                    </div>
                </div>
                <p v-if="role.users_count > 10" class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    Et {{ role.users_count - 10 }} autres utilisateurs...
                </p>
            </Card>
        </div>
    </AdminLayout>
</template>
