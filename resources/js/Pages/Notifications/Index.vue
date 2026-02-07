<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import EmptyState from '@/Components/Feedback/EmptyState.vue'
import Pagination from '@/Components/Data/Pagination.vue'
import RelativeTime from '@/Components/Utilities/RelativeTime.vue'
import { BellIcon, CheckIcon, TrashIcon, FunnelIcon } from '@heroicons/vue/24/outline'
import { CheckCircleIcon, ExclamationTriangleIcon, InformationCircleIcon, XCircleIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
    notifications: Object,
    unreadCount: {
        type: Number,
        default: 0
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

const loading = ref(false)
const selectedFilter = ref(props.filters.status || 'all')

// Type icons
const typeIcons = {
    info: InformationCircleIcon,
    success: CheckCircleIcon,
    warning: ExclamationTriangleIcon,
    danger: XCircleIcon,
    error: XCircleIcon,
}

// Type styles
const getTypeClass = (type) => {
    const styles = {
        info: 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
        success: 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
        warning: 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
        danger: 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
        error: 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
    }
    return styles[type] || styles.info
}

const getTypeBadgeVariant = (type) => {
    const variants = {
        info: 'info',
        success: 'success',
        warning: 'warning',
        danger: 'danger',
        error: 'danger',
    }
    return variants[type] || 'info'
}

// Computed
const hasNotifications = computed(() => props.notifications?.data?.length > 0)

// Actions
const markAsRead = async (notification) => {
    if (notification.read_at) return

    loading.value = true
    try {
        await router.post(`/notifications/${notification.id}/read`, {}, {
            preserveScroll: true,
            onFinish: () => loading.value = false
        })
    } catch (e) {
        loading.value = false
    }
}

const markAllAsRead = async () => {
    loading.value = true
    try {
        await router.post('/notifications/read-all', {}, {
            preserveScroll: true,
            onFinish: () => loading.value = false
        })
    } catch (e) {
        loading.value = false
    }
}

const deleteNotification = async (notification) => {
    loading.value = true
    try {
        await router.delete(`/notifications/${notification.id}`, {
            preserveScroll: true,
            onFinish: () => loading.value = false
        })
    } catch (e) {
        loading.value = false
    }
}

const deleteAllRead = async () => {
    if (!confirm('Supprimer toutes les notifications lues ?')) return

    loading.value = true
    try {
        await router.delete('/notifications/read', {
            preserveScroll: true,
            onFinish: () => loading.value = false
        })
    } catch (e) {
        loading.value = false
    }
}

const filterNotifications = (status) => {
    selectedFilter.value = status
    router.get('/notifications', { status: status === 'all' ? null : status }, {
        preserveState: true,
        preserveScroll: true,
    })
}

const visitAction = (notification) => {
    if (!notification.read_at) {
        markAsRead(notification)
    }
    if (notification.action_url) {
        router.visit(notification.action_url)
    }
}
</script>

<template>
    <AdminLayout title="Notifications">
        <PageHeader
            title="Notifications"
            :subtitle="unreadCount > 0 ? `${unreadCount} non lue${unreadCount > 1 ? 's' : ''}` : 'Toutes vos notifications'"
        >
            <template #actions>
                <div class="flex items-center gap-2">
                    <Button
                        v-if="unreadCount > 0"
                        variant="secondary"
                        size="sm"
                        @click="markAllAsRead"
                        :disabled="loading"
                        :icon-left="CheckIcon"
                    >
                        Tout marquer comme lu
                    </Button>
                    <Button
                        variant="ghost"
                        size="sm"
                        @click="deleteAllRead"
                        :disabled="loading"
                        :icon-left="TrashIcon"
                    >
                        Supprimer les lues
                    </Button>
                </div>
            </template>
        </PageHeader>

        <div class="max-w-4xl mx-auto">
                <!-- Filters -->
                <div class="mb-6 flex items-center gap-2">
                    <FunnelIcon class="h-5 w-5 text-gray-400" />
                    <div class="flex gap-1">
                        <button
                            v-for="filter in [
                                { value: 'all', label: 'Toutes' },
                                { value: 'unread', label: 'Non lues' },
                                { value: 'read', label: 'Lues' }
                            ]"
                            :key="filter.value"
                            :class="[
                                'px-3 py-1.5 text-sm font-medium rounded-lg transition-colors',
                                selectedFilter === filter.value
                                    ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400'
                                    : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700'
                            ]"
                            @click="filterNotifications(filter.value)"
                        >
                            {{ filter.label }}
                        </button>
                    </div>
                </div>

                <!-- Notifications List -->
                <Card v-if="hasNotifications" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div
                        v-for="notification in notifications.data"
                        :key="notification.id"
                        :class="[
                            'group relative flex gap-4 p-4 transition-colors',
                            notification.read_at
                                ? 'bg-white dark:bg-gray-800'
                                : 'bg-indigo-50/50 dark:bg-indigo-900/10'
                        ]"
                    >
                        <!-- Unread indicator -->
                        <span
                            v-if="!notification.read_at"
                            class="absolute left-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-indigo-500 rounded-full"
                        />

                        <!-- Icon -->
                        <div
                            :class="[
                                'flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center',
                                getTypeClass(notification.type)
                            ]"
                        >
                            <component
                                :is="typeIcons[notification.type] || InformationCircleIcon"
                                class="h-6 w-6"
                            />
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ notification.title }}
                                    </p>
                                    <p v-if="notification.body" class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ notification.body }}
                                    </p>
                                </div>
                                <Badge :variant="getTypeBadgeVariant(notification.type)" size="sm">
                                    {{ notification.type }}
                                </Badge>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <p class="text-xs text-gray-500 dark:text-gray-500">
                                    <RelativeTime :date="notification.created_at" />
                                </p>

                                <div class="flex items-center gap-2">
                                    <!-- Action button -->
                                    <Button
                                        v-if="notification.action_url"
                                        variant="primary"
                                        size="xs"
                                        @click="visitAction(notification)"
                                    >
                                        {{ notification.action_text || 'Voir' }}
                                    </Button>

                                    <!-- Mark as read -->
                                    <Button
                                        v-if="!notification.read_at"
                                        variant="ghost"
                                        size="xs"
                                        @click="markAsRead(notification)"
                                        title="Marquer comme lu"
                                    >
                                        <CheckIcon class="h-4 w-4" />
                                    </Button>

                                    <!-- Delete -->
                                    <Button
                                        variant="ghost"
                                        size="xs"
                                        class="text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity"
                                        @click="deleteNotification(notification)"
                                        title="Supprimer"
                                    >
                                        <TrashIcon class="h-4 w-4" />
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </Card>

                <!-- Empty State -->
                <EmptyState
                    v-else
                    :icon="BellIcon"
                    title="Aucune notification"
                    description="Vous n'avez pas encore de notifications."
                />

            <!-- Pagination -->
            <div v-if="notifications?.meta?.last_page > 1" class="mt-6">
                <Pagination :links="notifications.links" :meta="notifications.meta" />
            </div>
        </div>
    </AdminLayout>
</template>
