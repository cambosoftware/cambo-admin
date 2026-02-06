<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import DescriptionList from '@/Components/Data/DescriptionList.vue'
import Badge from '@/Components/UI/Badge.vue'
import Avatar from '@/Components/UI/Avatar.vue'
import Button from '@/Components/UI/Button.vue'
import {
    ArrowLeftIcon,
    ClockIcon,
    UserIcon,
    ComputerDesktopIcon,
    GlobeAltIcon,
    DocumentTextIcon,
    PlusCircleIcon,
    PencilSquareIcon,
    XCircleIcon,
    ArrowPathIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    activity: Object,
})

// Event helpers
const getEventIcon = (event) => {
    const icons = {
        created: PlusCircleIcon,
        updated: PencilSquareIcon,
        deleted: XCircleIcon,
        restored: ArrowPathIcon,
    }
    return icons[event] || ClockIcon
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

const formatDate = (date) => {
    return new Date(date).toLocaleString('fr-FR', {
        dateStyle: 'full',
        timeStyle: 'medium'
    })
}

// Computed changes
const changes = computed(() => {
    if (!props.activity.properties) return []

    const old = props.activity.properties.old || {}
    const newValues = props.activity.properties.attributes || {}
    const result = []

    // Get all keys from both old and new
    const allKeys = new Set([...Object.keys(old), ...Object.keys(newValues)])

    for (const key of allKeys) {
        if (old[key] !== newValues[key]) {
            result.push({
                field: key,
                old: old[key],
                new: newValues[key],
            })
        }
    }

    return result
})

// Basic info for description list
const basicInfo = computed(() => [
    {
        label: 'Date',
        value: formatDate(props.activity.created_at),
        icon: ClockIcon,
    },
    {
        label: 'Utilisateur',
        value: props.activity.causer?.name || 'Système',
        icon: UserIcon,
    },
    {
        label: 'Action',
        value: formatEventName(props.activity.event),
        badge: true,
        badgeClass: getEventBadgeClass(props.activity.event),
    },
    {
        label: 'Log',
        value: formatLogName(props.activity.log_name),
    },
    {
        label: 'Adresse IP',
        value: props.activity.ip_address || 'Non disponible',
        icon: GlobeAltIcon,
    },
    {
        label: 'Navigateur',
        value: props.activity.user_agent || 'Non disponible',
        icon: ComputerDesktopIcon,
        truncate: true,
    },
])
</script>

<template>
    <AdminLayout title="Détail de l'activité">
        <PageHeader
            title="Détail de l'activité"
            :subtitle="activity.description"
        >
            <template #actions>
                <Link href="/activity-log">
                    <Button variant="secondary" :icon-left="ArrowLeftIcon">
                        Retour
                    </Button>
                </Link>
            </template>
        </PageHeader>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <Card>
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <DocumentTextIcon class="h-5 w-5 text-gray-400" />
                            Description
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ activity.description }}
                        </p>
                    </div>
                </Card>

                <!-- Changes -->
                <Card v-if="changes.length > 0">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            Modifications
                        </h3>

                        <div class="space-y-4">
                            <div
                                v-for="change in changes"
                                :key="change.field"
                                class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden"
                            >
                                <div class="px-4 py-2 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">
                                        {{ change.field }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 divide-x divide-gray-200 dark:divide-gray-700">
                                    <div class="p-4">
                                        <div class="text-xs text-gray-500 mb-1">Avant</div>
                                        <div class="text-sm">
                                            <code
                                                v-if="change.old !== null && change.old !== undefined"
                                                class="px-2 py-1 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded"
                                            >
                                                {{ typeof change.old === 'object' ? JSON.stringify(change.old) : change.old }}
                                            </code>
                                            <span v-else class="text-gray-400 italic">vide</span>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <div class="text-xs text-gray-500 mb-1">Après</div>
                                        <div class="text-sm">
                                            <code
                                                v-if="change.new !== null && change.new !== undefined"
                                                class="px-2 py-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded"
                                            >
                                                {{ typeof change.new === 'object' ? JSON.stringify(change.new) : change.new }}
                                            </code>
                                            <span v-else class="text-gray-400 italic">vide</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Card>

                <!-- Raw Properties -->
                <Card v-if="activity.properties">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            Données brutes
                        </h3>
                        <pre class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg overflow-x-auto text-xs">{{ JSON.stringify(activity.properties, null, 2) }}</pre>
                    </div>
                </Card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Basic Info -->
                <Card>
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            Informations
                        </h3>

                        <dl class="space-y-4">
                            <div v-for="info in basicInfo" :key="info.label">
                                <dt class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                    <component :is="info.icon" v-if="info.icon" class="h-4 w-4" />
                                    {{ info.label }}
                                </dt>
                                <dd class="mt-1">
                                    <Badge
                                        v-if="info.badge"
                                        :class="info.badgeClass"
                                    >
                                        {{ info.value }}
                                    </Badge>
                                    <span
                                        v-else
                                        :class="[
                                            'text-sm text-gray-900 dark:text-white',
                                            info.truncate ? 'block truncate' : ''
                                        ]"
                                        :title="info.truncate ? info.value : undefined"
                                    >
                                        {{ info.value }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </Card>

                <!-- Causer Info -->
                <Card v-if="activity.causer">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            Utilisateur
                        </h3>

                        <div class="flex items-center gap-4">
                            <Avatar
                                :name="activity.causer.name"
                                :src="activity.causer.avatar"
                                size="lg"
                            />
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ activity.causer.name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ activity.causer.email }}
                                </div>
                            </div>
                        </div>
                    </div>
                </Card>

                <!-- Subject Info -->
                <Card v-if="activity.subject">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            Sujet
                        </h3>

                        <dl class="space-y-2 text-sm">
                            <div>
                                <dt class="text-gray-500">Type</dt>
                                <dd class="text-gray-900 dark:text-white">
                                    {{ activity.subject_type?.split('\\').pop() }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">ID</dt>
                                <dd class="text-gray-900 dark:text-white font-mono">
                                    {{ activity.subject_id }}
                                </dd>
                            </div>
                            <div v-if="activity.subject.name">
                                <dt class="text-gray-500">Nom</dt>
                                <dd class="text-gray-900 dark:text-white">
                                    {{ activity.subject.name }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </Card>
            </div>
        </div>
    </AdminLayout>
</template>
