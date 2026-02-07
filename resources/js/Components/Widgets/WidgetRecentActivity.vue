<script setup>
import { ref } from 'vue'
import Avatar from '@/Components/UI/Avatar.vue'
import RelativeTime from '@/Components/Utilities/RelativeTime.vue'
import {
    PlusCircleIcon,
    PencilSquareIcon,
    TrashIcon,
    UserPlusIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    config: {
        type: Object,
        default: () => ({})
    }
})

// Mock activity data
const activities = ref([
    {
        id: 1,
        user: { name: 'Marie Martin', avatar: null },
        action: 'created',
        target: 'Nouveau produit',
        time: new Date(Date.now() - 1000 * 60 * 5).toISOString()
    },
    {
        id: 2,
        user: { name: 'Jean Dupont', avatar: null },
        action: 'updated',
        target: 'Configuration système',
        time: new Date(Date.now() - 1000 * 60 * 25).toISOString()
    },
    {
        id: 3,
        user: { name: 'Pierre Bernard', avatar: null },
        action: 'deleted',
        target: 'Article obsolète',
        time: new Date(Date.now() - 1000 * 60 * 60).toISOString()
    },
    {
        id: 4,
        user: { name: 'Sophie Lambert', avatar: null },
        action: 'registered',
        target: null,
        time: new Date(Date.now() - 1000 * 60 * 120).toISOString()
    },
    {
        id: 5,
        user: { name: 'Lucas Moreau', avatar: null },
        action: 'updated',
        target: 'Profil utilisateur',
        time: new Date(Date.now() - 1000 * 60 * 180).toISOString()
    },
])

const getActionIcon = (action) => {
    const icons = {
        created: PlusCircleIcon,
        updated: PencilSquareIcon,
        deleted: TrashIcon,
        registered: UserPlusIcon,
    }
    return icons[action] || PencilSquareIcon
}

const getActionColor = (action) => {
    const colors = {
        created: 'text-green-500 bg-green-100 dark:bg-green-900/30',
        updated: 'text-blue-500 bg-blue-100 dark:bg-blue-900/30',
        deleted: 'text-red-500 bg-red-100 dark:bg-red-900/30',
        registered: 'text-purple-500 bg-purple-100 dark:bg-purple-900/30',
    }
    return colors[action] || 'text-gray-500 bg-gray-100'
}

const getActionText = (activity) => {
    const texts = {
        created: 'a créé',
        updated: 'a modifié',
        deleted: 'a supprimé',
        registered: 's\'est inscrit(e)',
    }
    return texts[activity.action] || 'a effectué une action'
}
</script>

<template>
    <div class="h-full flex flex-col">
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ config.title }}
            </h3>
        </div>

        <!-- Activities -->
        <div class="flex-1 overflow-y-auto">
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                <div
                    v-for="activity in activities.slice(0, config.limit || 5)"
                    :key="activity.id"
                    class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                >
                    <div class="flex items-start gap-3">
                        <!-- Avatar -->
                        <Avatar
                            :name="activity.user.name"
                            :src="activity.user.avatar"
                            size="sm"
                        />

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white">
                                <span class="font-medium">{{ activity.user.name }}</span>
                                <span class="text-gray-500"> {{ getActionText(activity) }}</span>
                                <span v-if="activity.target" class="font-medium"> {{ activity.target }}</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <RelativeTime :date="activity.time" />
                            </p>
                        </div>

                        <!-- Icon -->
                        <div
                            :class="[
                                'flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center',
                                getActionColor(activity.action)
                            ]"
                        >
                            <component :is="getActionIcon(activity.action)" class="h-4 w-4" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
            <a
                href="/activity-log"
                class="text-xs text-indigo-600 hover:text-indigo-700 font-medium"
            >
                Voir tout le journal
            </a>
        </div>
    </div>
</template>
