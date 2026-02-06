<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { BellIcon, CheckIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { BellIcon as BellIconSolid, BellAlertIcon } from '@heroicons/vue/24/solid'
import Badge from '@/Components/UI/Badge.vue'
import Spinner from '@/Components/UI/Spinner.vue'
import RelativeTime from '@/Components/Utilities/RelativeTime.vue'

const props = defineProps({
    /**
     * Poll interval in ms (0 to disable)
     */
    pollInterval: {
        type: Number,
        default: 60000
    },
    /**
     * Maximum notifications to show
     */
    maxVisible: {
        type: Number,
        default: 5
    },
    /**
     * Position of dropdown
     */
    position: {
        type: String,
        default: 'bottom-end',
        validator: (v) => ['bottom-start', 'bottom-end', 'bottom-center'].includes(v)
    }
})

const emit = defineEmits(['update:unreadCount'])

const open = ref(false)
const loading = ref(false)
const notifications = ref([])
const unreadCount = ref(0)
let pollTimer = null

// Computed
const hasUnread = computed(() => unreadCount.value > 0)
const visibleNotifications = computed(() => notifications.value.slice(0, props.maxVisible))
const hasMore = computed(() => notifications.value.length > props.maxVisible)

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

// Position classes
const positionClasses = computed(() => {
    const [, horizontal] = props.position.split('-')
    if (horizontal === 'start') return 'left-0'
    if (horizontal === 'end') return 'right-0'
    return 'left-1/2 -translate-x-1/2'
})

// API Methods
const fetchNotifications = async () => {
    loading.value = true
    try {
        const response = await fetch('/notifications/recent', {
            headers: { 'Accept': 'application/json' }
        })
        if (response.ok) {
            const data = await response.json()
            notifications.value = data.notifications || []
            unreadCount.value = data.unread_count || 0
            emit('update:unreadCount', unreadCount.value)
        }
    } catch (e) {
        console.error('Failed to fetch notifications:', e)
    } finally {
        loading.value = false
    }
}

const fetchUnreadCount = async () => {
    try {
        const response = await fetch('/notifications/unread-count', {
            headers: { 'Accept': 'application/json' }
        })
        if (response.ok) {
            const data = await response.json()
            unreadCount.value = data.count || 0
            emit('update:unreadCount', unreadCount.value)
        }
    } catch (e) {
        console.error('Failed to fetch unread count:', e)
    }
}

const markAsRead = async (notification) => {
    try {
        const response = await fetch(`/notifications/${notification.id}/read`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        })
        if (response.ok) {
            notification.read_at = new Date().toISOString()
            unreadCount.value = Math.max(0, unreadCount.value - 1)
            emit('update:unreadCount', unreadCount.value)

            if (notification.action_url) {
                close()
                router.visit(notification.action_url)
            }
        }
    } catch (e) {
        console.error('Failed to mark as read:', e)
    }
}

const markAllAsRead = async () => {
    try {
        const response = await fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        })
        if (response.ok) {
            notifications.value.forEach(n => n.read_at = new Date().toISOString())
            unreadCount.value = 0
            emit('update:unreadCount', 0)
        }
    } catch (e) {
        console.error('Failed to mark all as read:', e)
    }
}

const deleteNotification = async (notification, event) => {
    event.stopPropagation()
    try {
        const response = await fetch(`/notifications/${notification.id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        })
        if (response.ok) {
            const index = notifications.value.findIndex(n => n.id === notification.id)
            if (index > -1) {
                if (!notification.read_at) {
                    unreadCount.value = Math.max(0, unreadCount.value - 1)
                }
                notifications.value.splice(index, 1)
            }
        }
    } catch (e) {
        console.error('Failed to delete notification:', e)
    }
}

// UI Methods
const toggle = () => {
    open.value = !open.value
    if (open.value) {
        fetchNotifications()
    }
}

const close = () => {
    open.value = false
}

const viewAll = () => {
    close()
    router.visit('/notifications')
}

const onNotificationClick = (notification) => {
    if (!notification.read_at) {
        markAsRead(notification)
    } else if (notification.action_url) {
        close()
        router.visit(notification.action_url)
    }
}

// Click outside handler
const onClickOutside = (e) => {
    if (open.value && !e.target.closest('.notification-center')) {
        close()
    }
}

onMounted(() => {
    fetchUnreadCount()
    document.addEventListener('click', onClickOutside)

    if (props.pollInterval > 0) {
        pollTimer = setInterval(fetchUnreadCount, props.pollInterval)
    }
})

onUnmounted(() => {
    document.removeEventListener('click', onClickOutside)
    if (pollTimer) {
        clearInterval(pollTimer)
    }
})
</script>

<template>
    <div class="notification-center relative">
        <!-- Trigger button -->
        <button
            type="button"
            class="relative p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500"
            @click="toggle"
        >
            <span class="sr-only">Notifications</span>
            <component
                :is="hasUnread ? BellAlertIcon : BellIcon"
                class="h-5 w-5"
                :class="{ 'text-primary-500': hasUnread }"
            />

            <!-- Badge -->
            <span
                v-if="hasUnread"
                class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-red-500 rounded-full animate-pulse"
            >
                {{ unreadCount > 99 ? '99+' : unreadCount }}
            </span>
        </button>

        <!-- Dropdown -->
        <Transition
            enter-active-class="transition duration-150 ease-out origin-top"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-100 ease-in origin-top"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="open"
                :class="[
                    'absolute z-50 top-full mt-2 w-80 sm:w-96 bg-white dark:bg-gray-800 rounded-xl shadow-xl ring-1 ring-black/5 dark:ring-gray-700 overflow-hidden',
                    positionClasses
                ]"
            >
                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Notifications
                        <span v-if="hasUnread" class="text-gray-500 font-normal">
                            ({{ unreadCount }})
                        </span>
                    </h3>
                    <button
                        v-if="hasUnread"
                        type="button"
                        class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400"
                        @click="markAllAsRead"
                    >
                        Tout marquer comme lu
                    </button>
                </div>

                <!-- Loading -->
                <div v-if="loading" class="py-12 flex justify-center">
                    <Spinner size="md" />
                </div>

                <!-- Notifications list -->
                <div v-else class="max-h-96 overflow-y-auto">
                    <template v-if="visibleNotifications.length > 0">
                        <div
                            v-for="notification in visibleNotifications"
                            :key="notification.id"
                            :class="[
                                'group relative flex gap-3 px-4 py-3 cursor-pointer transition-colors',
                                notification.read_at
                                    ? 'hover:bg-gray-50 dark:hover:bg-gray-700/50'
                                    : 'bg-primary-50/50 dark:bg-primary-900/10 hover:bg-primary-50 dark:hover:bg-primary-900/20'
                            ]"
                            @click="onNotificationClick(notification)"
                        >
                            <!-- Unread indicator -->
                            <span
                                v-if="!notification.read_at"
                                class="absolute left-1.5 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-primary-500 rounded-full"
                            />

                            <!-- Icon -->
                            <div
                                :class="[
                                    'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center',
                                    getTypeClass(notification.type)
                                ]"
                            >
                                <BellIcon class="h-5 w-5" />
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ notification.title }}
                                </p>
                                <p v-if="notification.body" class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                    {{ notification.body }}
                                </p>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    <RelativeTime :date="notification.created_at" />
                                </p>
                            </div>

                            <!-- Delete button -->
                            <button
                                type="button"
                                class="flex-shrink-0 p-1 text-gray-400 hover:text-red-600 rounded opacity-0 group-hover:opacity-100 transition-opacity"
                                title="Supprimer"
                                @click="deleteNotification(notification, $event)"
                            >
                                <XMarkIcon class="h-4 w-4" />
                            </button>
                        </div>
                    </template>

                    <!-- Empty state -->
                    <div v-else class="py-12 text-center">
                        <BellIcon class="h-12 w-12 mx-auto text-gray-300 dark:text-gray-600" />
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Aucune notification</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 text-center">
                    <button
                        type="button"
                        class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700"
                        @click="viewAll"
                    >
                        Voir toutes les notifications
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
