<script setup>
import { ref, computed, watch } from 'vue'
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
import Timeline from '@/Components/Data/Timeline.vue'
import TimelineItem from '@/Components/Data/TimelineItem.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import Avatar from '@/Components/UI/Avatar.vue'
import Select from '@/Components/Form/Select.vue'
import SearchInput from '@/Components/Form/SearchInput.vue'
import DatePicker from '@/Components/Form/DatePicker.vue'
import EmptyState from '@/Components/Feedback/EmptyState.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import Toggle from '@/Components/Form/Toggle.vue'
import RelativeTime from '@/Components/Utilities/RelativeTime.vue'
import {
    ClockIcon,
    FunnelIcon,
    TrashIcon,
    EyeIcon,
    TableCellsIcon,
    ListBulletIcon,
    PlusCircleIcon,
    PencilSquareIcon,
    XCircleIcon,
    ArrowPathIcon,
    UserIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    activities: Object,
    logNames: Array,
    events: Array,
    users: Array,
    filters: Object,
})

// State
const viewMode = ref('timeline') // 'timeline' or 'table'
const clearModal = ref(false)
const clearDays = ref(30)

// Filter values
const search = ref(props.filters?.search || '')
const selectedLog = ref(props.filters?.log || '')
const selectedEvent = ref(props.filters?.event || '')
const selectedUser = ref(props.filters?.user_id || '')
const dateFrom = ref(props.filters?.from || null)
const dateTo = ref(props.filters?.to || null)

// Computed
const hasActivities = computed(() => props.activities?.data?.length > 0)
const hasActiveFilters = computed(() =>
    search.value || selectedLog.value || selectedEvent.value ||
    selectedUser.value || dateFrom.value || dateTo.value
)

// Event icons and colors
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

// Log name options
const logOptions = computed(() => [
    { value: '', label: 'Tous les logs' },
    ...props.logNames.map(log => ({ value: log, label: formatLogName(log) }))
])

// Event options
const eventOptions = computed(() => [
    { value: '', label: 'Tous les événements' },
    ...props.events.map(event => ({ value: event, label: formatEventName(event) }))
])

// User options
const userOptions = computed(() => [
    { value: '', label: 'Tous les utilisateurs' },
    ...props.users.map(user => ({ value: user.id, label: user.name }))
])

// Format helpers
const formatLogName = (name) => {
    return name.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
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

// Actions
const applyFilters = () => {
    router.get('/activity-log', {
        search: search.value || undefined,
        log: selectedLog.value || undefined,
        event: selectedEvent.value || undefined,
        user_id: selectedUser.value || undefined,
        from: dateFrom.value || undefined,
        to: dateTo.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    })
}

const clearFilters = () => {
    search.value = ''
    selectedLog.value = ''
    selectedEvent.value = ''
    selectedUser.value = ''
    dateFrom.value = null
    dateTo.value = null
    router.get('/activity-log', {}, { preserveState: true, replace: true })
}

const confirmClear = () => {
    router.delete('/activity-log/clear', {
        data: { older_than_days: clearDays.value },
        onSuccess: () => clearModal.value = false,
    })
}

// Debounced search
let searchTimeout
watch(search, () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(applyFilters, 500)
})
</script>

<template>
    <AdminLayout title="Journal d'activité">
        <PageHeader
            title="Journal d'activité"
            subtitle="Historique de toutes les actions effectuées"
        >
            <template #actions>
                <div class="flex items-center gap-2">
                    <!-- View mode toggle -->
                    <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                        <button
                            :class="[
                                'p-2 rounded-md transition-colors',
                                viewMode === 'timeline'
                                    ? 'bg-white dark:bg-gray-600 shadow-sm'
                                    : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'
                            ]"
                            @click="viewMode = 'timeline'"
                            title="Vue timeline"
                        >
                            <ListBulletIcon class="h-5 w-5" />
                        </button>
                        <button
                            :class="[
                                'p-2 rounded-md transition-colors',
                                viewMode === 'table'
                                    ? 'bg-white dark:bg-gray-600 shadow-sm'
                                    : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'
                            ]"
                            @click="viewMode = 'table'"
                            title="Vue tableau"
                        >
                            <TableCellsIcon class="h-5 w-5" />
                        </button>
                    </div>

                    <Button
                        variant="danger"
                        size="sm"
                        :icon-left="TrashIcon"
                        @click="clearModal = true"
                    >
                        Nettoyer
                    </Button>
                </div>
            </template>
        </PageHeader>

        <!-- Filters -->
        <Card class="mb-6">
            <div class="p-4">
                <div class="flex items-center gap-2 mb-4">
                    <FunnelIcon class="h-5 w-5 text-gray-400" />
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtres</span>
                    <button
                        v-if="hasActiveFilters"
                        class="ml-auto text-sm text-primary-600 hover:text-primary-700"
                        @click="clearFilters"
                    >
                        Réinitialiser
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <SearchInput
                        v-model="search"
                        placeholder="Rechercher..."
                        size="sm"
                    />

                    <Select
                        v-model="selectedLog"
                        :options="logOptions"
                        size="sm"
                        @change="applyFilters"
                    />

                    <Select
                        v-model="selectedEvent"
                        :options="eventOptions"
                        size="sm"
                        @change="applyFilters"
                    />

                    <Select
                        v-model="selectedUser"
                        :options="userOptions"
                        size="sm"
                        @change="applyFilters"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <DatePicker
                        v-model="dateFrom"
                        placeholder="Date de début"
                        size="sm"
                        @update:modelValue="applyFilters"
                    />
                    <DatePicker
                        v-model="dateTo"
                        placeholder="Date de fin"
                        size="sm"
                        @update:modelValue="applyFilters"
                    />
                </div>
            </div>
        </Card>

        <!-- Timeline View -->
        <Card v-if="viewMode === 'timeline' && hasActivities">
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

                        <div class="flex items-start gap-4">
                            <!-- Causer avatar -->
                            <Avatar
                                v-if="activity.causer"
                                :name="activity.causer.name"
                                :src="activity.causer.avatar"
                                size="sm"
                            />
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center" v-else>
                                <UserIcon class="h-4 w-4 text-gray-500" />
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ activity.causer?.name || 'Système' }}
                                    </span>
                                    <Badge
                                        :class="getEventBadgeClass(activity.event)"
                                        size="sm"
                                    >
                                        {{ formatEventName(activity.event) }}
                                    </Badge>
                                    <Badge variant="secondary" size="sm" outline>
                                        {{ formatLogName(activity.log_name) }}
                                    </Badge>
                                </div>

                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ activity.description }}
                                </p>

                                <!-- Changes preview -->
                                <div
                                    v-if="activity.properties?.old || activity.properties?.attributes"
                                    class="mt-2 text-xs"
                                >
                                    <details class="group">
                                        <summary class="cursor-pointer text-primary-600 hover:text-primary-700">
                                            Voir les détails
                                        </summary>
                                        <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg space-y-2">
                                            <div v-if="activity.properties?.old" class="flex gap-2">
                                                <span class="text-gray-500">Avant:</span>
                                                <code class="text-red-600 dark:text-red-400">
                                                    {{ JSON.stringify(activity.properties.old, null, 2) }}
                                                </code>
                                            </div>
                                            <div v-if="activity.properties?.attributes" class="flex gap-2">
                                                <span class="text-gray-500">Après:</span>
                                                <code class="text-green-600 dark:text-green-400">
                                                    {{ JSON.stringify(activity.properties.attributes, null, 2) }}
                                                </code>
                                            </div>
                                        </div>
                                    </details>
                                </div>

                                <div class="mt-2 flex items-center gap-4 text-xs text-gray-500">
                                    <span>
                                        <RelativeTime :date="activity.created_at" />
                                    </span>
                                    <span v-if="activity.ip_address">
                                        IP: {{ activity.ip_address }}
                                    </span>
                                </div>
                            </div>

                            <Link
                                :href="`/activity-log/${activity.id}`"
                                class="flex-shrink-0 p-2 text-gray-400 hover:text-primary-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                <EyeIcon class="h-5 w-5" />
                            </Link>
                        </div>
                    </TimelineItem>
                </Timeline>
            </div>
        </Card>

        <!-- Table View -->
        <Card v-else-if="viewMode === 'table' && hasActivities">
            <Table>
                <TableHead>
                    <TableRow>
                        <TableCell header>Date</TableCell>
                        <TableCell header>Utilisateur</TableCell>
                        <TableCell header>Action</TableCell>
                        <TableCell header>Description</TableCell>
                        <TableCell header>Log</TableCell>
                        <TableCell header class="w-20"></TableCell>
                    </TableRow>
                </TableHead>
                <TableBody>
                    <TableRow v-for="activity in activities.data" :key="activity.id">
                        <TableCell class="whitespace-nowrap">
                            <RelativeTime :date="activity.created_at" />
                        </TableCell>
                        <TableCell>
                            <div class="flex items-center gap-2">
                                <Avatar
                                    v-if="activity.causer"
                                    :name="activity.causer.name"
                                    :src="activity.causer.avatar"
                                    size="xs"
                                />
                                <span>{{ activity.causer?.name || 'Système' }}</span>
                            </div>
                        </TableCell>
                        <TableCell>
                            <Badge :class="getEventBadgeClass(activity.event)" size="sm">
                                {{ formatEventName(activity.event) }}
                            </Badge>
                        </TableCell>
                        <TableCell class="max-w-xs truncate">
                            {{ activity.description }}
                        </TableCell>
                        <TableCell>
                            <Badge variant="secondary" size="sm" outline>
                                {{ formatLogName(activity.log_name) }}
                            </Badge>
                        </TableCell>
                        <TableCell>
                            <Link
                                :href="`/activity-log/${activity.id}`"
                                class="p-2 text-gray-400 hover:text-primary-600"
                            >
                                <EyeIcon class="h-5 w-5" />
                            </Link>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </Card>

        <!-- Empty State -->
        <EmptyState
            v-else
            :icon="ClockIcon"
            title="Aucune activité"
            description="Aucune activité n'a été enregistrée pour le moment."
        />

        <!-- Pagination -->
        <div v-if="activities?.meta?.last_page > 1" class="mt-6">
            <Pagination :links="activities.links" :meta="activities.meta" />
        </div>

        <!-- Clear Modal -->
        <ConfirmModal
            :show="clearModal"
            title="Nettoyer le journal"
            confirm-text="Supprimer"
            variant="danger"
            @close="clearModal = false"
            @confirm="confirmClear"
        >
            <p class="text-gray-600 dark:text-gray-400">
                Cette action supprimera toutes les entrées du journal plus anciennes que :
            </p>
            <div class="mt-4">
                <Select
                    v-model="clearDays"
                    :options="[
                        { value: 7, label: '7 jours' },
                        { value: 14, label: '14 jours' },
                        { value: 30, label: '30 jours' },
                        { value: 60, label: '60 jours' },
                        { value: 90, label: '90 jours' },
                    ]"
                />
            </div>
        </ConfirmModal>
    </AdminLayout>
</template>
