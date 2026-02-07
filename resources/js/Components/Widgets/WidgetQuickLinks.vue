<script setup>
import { Link } from '@inertiajs/vue3'
import {
    UsersIcon,
    ShieldCheckIcon,
    Cog6ToothIcon,
    DocumentTextIcon,
    ChartBarIcon,
    FolderIcon,
    BellIcon,
    ClockIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    config: {
        type: Object,
        default: () => ({})
    }
})

const iconMap = {
    users: UsersIcon,
    'shield-check': ShieldCheckIcon,
    cog: Cog6ToothIcon,
    document: DocumentTextIcon,
    chart: ChartBarIcon,
    folder: FolderIcon,
    bell: BellIcon,
    clock: ClockIcon,
}

const defaultLinks = [
    { label: 'Utilisateurs', url: '/users', icon: 'users' },
    { label: 'Rôles', url: '/roles', icon: 'shield-check' },
    { label: 'Activité', url: '/activity-log', icon: 'clock' },
    { label: 'Notifications', url: '/notifications', icon: 'bell' },
]

const links = props.config.links?.length ? props.config.links : defaultLinks
</script>

<template>
    <div class="h-full flex flex-col">
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ config.title }}
            </h3>
        </div>

        <!-- Links -->
        <div class="flex-1 p-2">
            <div class="grid grid-cols-2 gap-2">
                <Link
                    v-for="link in links"
                    :key="link.url"
                    :href="link.url"
                    class="flex flex-col items-center justify-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-center group"
                >
                    <component
                        :is="iconMap[link.icon] || DocumentTextIcon"
                        class="h-6 w-6 text-gray-400 group-hover:text-indigo-500 transition-colors mb-1"
                    />
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                        {{ link.label }}
                    </span>
                </Link>
            </div>
        </div>
    </div>
</template>
