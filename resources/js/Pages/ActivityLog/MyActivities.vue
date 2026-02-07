<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Timeline from '@/Components/Data/Timeline.vue'
import TimelineItem from '@/Components/Data/TimelineItem.vue'
import Pagination from '@/Components/Data/Pagination.vue'
import Badge from '@/Components/UI/Badge.vue'
import Button from '@/Components/UI/Button.vue'
import EmptyState from '@/Components/Feedback/EmptyState.vue'
import RelativeTime from '@/Components/Utilities/RelativeTime.vue'
import {
    ArrowLeftIcon,
    ClockIcon,
    EyeIcon,
    PlusCircleIcon,
    PencilSquareIcon,
    XCircleIcon,
    ArrowPathIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    activities: Object,
})

const hasActivities = computed(() => props.activities?.data?.length > 0)

const getEventIcon = (event) => {
    const icons = {
        created: PlusCircleIcon,
        updated: PencilSquareIcon,
        deleted: XCircleIcon,
        restored: ArrowPathIcon,
    }
    return icons[event] || ClockIcon
}

const getEventColor = (event) => {
    const colors = {
        created: 'success',
        updated: 'info',
        deleted: 'danger',
        restored: 'warning',
    }
    return colors[event] || 'default'
}

const getEventBadgeClass = (event) => {
    const classes = {
        created: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
        updated: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
        deleted: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        restored: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
    }
    return classes[event] || 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'
}

const formatEventName = (event) => {
    const names = {
        created: 'Création',
        updated: 'Modification',
        deleted: 'Suppression',
        restored: 'Restauration',
    }
    return names[event] || event
}

const formatLogName = (name) => {
    return name.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
}
</script>

<template>
    <AdminLayout title="Mes activités">
        <PageHeader
            title="Mes activités"
            subtitle="Historique de vos actions"
        >
            <template #actions>
                <Link href="/activity-log">
                    <Button variant="secondary" :icon-left="ArrowLeftIcon">
                        Tout le journal
                    </Button>
                </Link>
            </template>
        </PageHeader>

        <Card v-if="hasActivities">
            <div class="p-6">
                <Timeline>
                    <TimelineItem
                        v-for="activity in activities.data"
                        :key="activity.id"
                        :color="getEventColor(activity.event)"
                    >
                        <template #icon>
                            <component :is="getEventIcon(activity.event)" class="h-4 w-4" />
                        </template>

                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <Badge :class="getEventBadgeClass(activity.event)" size="sm">
                                        {{ formatEventName(activity.event) }}
                                    </Badge>
                                    <Badge variant="secondary" size="sm" outline>
                                        {{ formatLogName(activity.log_name) }}
                                    </Badge>
                                </div>

                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ activity.description }}
                                </p>

                                <p class="mt-2 text-xs text-gray-500">
                                    <RelativeTime :date="activity.created_at" />
                                </p>
                            </div>

                            <Link
                                :href="`/activity-log/${activity.id}`"
                                class="flex-shrink-0 p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                <EyeIcon class="h-5 w-5" />
                            </Link>
                        </div>
                    </TimelineItem>
                </Timeline>
            </div>
        </Card>

        <EmptyState
            v-else
            :icon="ClockIcon"
            title="Aucune activité"
            description="Vous n'avez pas encore effectué d'actions."
        />

        <div v-if="activities?.meta?.last_page > 1" class="mt-6">
            <Pagination :links="activities.links" :meta="activities.meta" />
        </div>
    </AdminLayout>
</template>
