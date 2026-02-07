<script setup>
import { ref, computed, inject } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import Breadcrumb from './Breadcrumb.vue'

const props = defineProps({
    title: {
        type: String,
        default: ''
    },
    breadcrumb: {
        type: Array,
        default: () => []
    },
    showSearch: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['toggle-sidebar'])

// Get current user from provider
const currentUser = inject('currentUser', ref(null))

// User dropdown state
const userMenuOpen = ref(false)

// Search state
const searchQuery = ref('')
const searchOpen = ref(false)

// Toggle user menu
const toggleUserMenu = () => {
    userMenuOpen.value = !userMenuOpen.value
}

// Close user menu when clicking outside
const closeUserMenu = () => {
    userMenuOpen.value = false
}

// Handle logout
const logout = () => {
    router.post('/logout')
}

// User initials for avatar
const userInitials = computed(() => {
    if (!currentUser.value?.name) return '?'
    return currentUser.value.name
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
})
</script>

<template>
    <header class="sticky top-0 z-20 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
            <!-- Left side: Menu button + Title/Breadcrumb -->
            <div class="flex items-center gap-4">
                <!-- Mobile menu button -->
                <button
                    type="button"
                    class="lg:hidden -ml-2 p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700"
                    @click="emit('toggle-sidebar')"
                >
                    <span class="sr-only">Ouvrir le menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Breadcrumb or Title -->
                <div class="flex flex-col">
                    <Breadcrumb
                        v-if="breadcrumb.length > 0"
                        :items="breadcrumb"
                        class="hidden sm:flex"
                    />
                    <h1
                        v-if="title"
                        class="text-lg font-semibold text-gray-900 dark:text-white"
                        :class="{ 'sm:hidden': breadcrumb.length > 0 }"
                    >
                        {{ title }}
                    </h1>
                </div>
            </div>

            <!-- Right side: Search + Actions + User -->
            <div class="flex items-center gap-3">
                <!-- Search (optional) -->
                <div v-if="showSearch" class="hidden sm:block relative">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Rechercher..."
                        class="w-64 rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-1.5 pl-10 pr-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:bg-white dark:focus:bg-gray-600 focus:ring-indigo-500"
                    />
                    <svg
                        class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>

                <!-- Mobile search button -->
                <button
                    v-if="showSearch"
                    type="button"
                    class="sm:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700"
                    @click="searchOpen = !searchOpen"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </button>

                <!-- Header actions slot -->
                <slot name="actions" />

                <!-- Notifications (example) -->
                <button
                    type="button"
                    class="relative p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700"
                >
                    <span class="sr-only">Voir les notifications</span>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <!-- Notification badge -->
                    <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-red-500" />
                </button>

                <!-- User menu -->
                <div class="relative">
                    <button
                        type="button"
                        class="flex items-center gap-2 rounded-full p-1 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        @click="toggleUserMenu"
                        @blur="closeUserMenu"
                    >
                        <!-- Avatar -->
                        <div
                            v-if="currentUser?.avatar"
                            class="h-8 w-8 rounded-full bg-cover bg-center"
                            :style="{ backgroundImage: `url(${currentUser.avatar})` }"
                        />
                        <div
                            v-else
                            class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center"
                        >
                            <span class="text-sm font-medium text-white">
                                {{ userInitials }}
                            </span>
                        </div>

                        <!-- Name (hidden on mobile) -->
                        <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ currentUser?.name || 'Utilisateur' }}
                        </span>

                        <!-- Chevron -->
                        <svg
                            class="hidden sm:block h-4 w-4 text-gray-400 dark:text-gray-500"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <Transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                    >
                        <div
                            v-show="userMenuOpen"
                            class="absolute right-0 mt-2 w-56 z-50 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 dark:ring-gray-700 focus:outline-none"
                        >
                            <!-- Custom user menu slot -->
                            <slot name="user-menu">
                                <div class="py-1">
                                    <!-- User info -->
                                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ currentUser?.name || 'Utilisateur' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ currentUser?.email }}
                                        </p>
                                    </div>

                                    <!-- Menu items -->
                                    <Link
                                        href="/profile"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    >
                                        <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                        Mon profil
                                    </Link>
                                    <Link
                                        href="/settings"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    >
                                        <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Paramètres
                                    </Link>

                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1" />

                                    <button
                                        type="button"
                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20"
                                        @click="logout"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                        </svg>
                                        Déconnexion
                                    </button>
                                </div>
                            </slot>
                        </div>
                    </Transition>
                </div>
            </div>
        </div>

        <!-- Mobile search bar (when open) -->
        <Transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showSearch && searchOpen"
                class="sm:hidden border-t border-gray-200 dark:border-gray-700 px-4 py-3"
            >
                <div class="relative">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Rechercher..."
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-2 pl-10 pr-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:bg-white dark:focus:bg-gray-600 focus:ring-indigo-500"
                        autofocus
                    />
                    <svg
                        class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
            </div>
        </Transition>
    </header>
</template>
