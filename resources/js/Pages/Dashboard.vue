<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import SidebarItem from '@/Components/Layout/SidebarItem.vue'
import SidebarDivider from '@/Components/Layout/SidebarDivider.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import {
    HomeIcon,
    UsersIcon,
    ShieldCheckIcon,
    Cog6ToothIcon,
    FolderIcon,
    BellIcon,
    ClockIcon,
    ArrowDownTrayIcon,
    LanguageIcon,
    SwatchIcon,
} from '@heroicons/vue/24/outline'

// Get page props
const page = usePage()
const stats = computed(() => page.props.stats || {})
const modules = computed(() => page.props.modules || {})
const user = computed(() => page.props.auth?.user)

// Helper to check if module is enabled
const isModuleEnabled = (module) => modules.value[module] !== false

// Get current path for active state
const currentPath = computed(() => page.url || window.location.pathname)
const isActive = (path) => currentPath.value.startsWith(path)

// Define props for customization
defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    }
})
</script>

<template>
    <AdminLayout :title="title">
        <!-- Sidebar Menu -->
        <template #sidebar-menu>
            <!-- Dashboard -->
            <SidebarItem
                label="Dashboard"
                :icon="HomeIcon"
                href="/admin"
                :active="currentPath === '/admin' || currentPath === '/admin/'"
            />

            <SidebarDivider v-if="isModuleEnabled('users') || isModuleEnabled('roles')" label="Administration" />

            <!-- Users -->
            <SidebarItem
                v-if="isModuleEnabled('users')"
                label="Users"
                :icon="UsersIcon"
                href="/admin/users"
                :active="isActive('/admin/users')"
            />

            <!-- Roles -->
            <SidebarItem
                v-if="isModuleEnabled('roles')"
                label="Roles"
                :icon="ShieldCheckIcon"
                href="/admin/roles"
                :active="isActive('/admin/roles')"
            />

            <SidebarDivider v-if="isModuleEnabled('media') || isModuleEnabled('notifications') || isModuleEnabled('activity-log')" label="Content" />

            <!-- Media -->
            <SidebarItem
                v-if="isModuleEnabled('media')"
                label="Media"
                :icon="FolderIcon"
                href="/admin/media"
                :active="isActive('/admin/media')"
            />

            <!-- Notifications -->
            <SidebarItem
                v-if="isModuleEnabled('notifications')"
                label="Notifications"
                :icon="BellIcon"
                href="/admin/notifications"
                :active="isActive('/admin/notifications')"
            />

            <!-- Activity Log -->
            <SidebarItem
                v-if="isModuleEnabled('activity-log')"
                label="Activity Log"
                :icon="ClockIcon"
                href="/admin/activity-log"
                :active="isActive('/admin/activity-log')"
            />

            <SidebarDivider v-if="isModuleEnabled('settings') || isModuleEnabled('import-export') || isModuleEnabled('i18n') || isModuleEnabled('themes')" label="Settings" />

            <!-- Import/Export -->
            <SidebarItem
                v-if="isModuleEnabled('import-export')"
                label="Import/Export"
                :icon="ArrowDownTrayIcon"
                href="/admin/import-export"
                :active="isActive('/admin/import-export')"
            />

            <!-- Translations -->
            <SidebarItem
                v-if="isModuleEnabled('i18n')"
                label="Translations"
                :icon="LanguageIcon"
                href="/admin/translations"
                :active="isActive('/admin/translations')"
            />

            <!-- Themes -->
            <SidebarItem
                v-if="isModuleEnabled('themes')"
                label="Themes"
                :icon="SwatchIcon"
                href="/admin/themes"
                :active="isActive('/admin/themes')"
            />

            <!-- Settings -->
            <SidebarItem
                v-if="isModuleEnabled('settings')"
                label="Settings"
                :icon="Cog6ToothIcon"
                href="/admin/settings"
                :active="isActive('/admin/settings')"
            />
        </template>

        <!-- Page Content -->
        <PageHeader :title="title">
            <template #subtitle>
                Welcome back, {{ user?.name || 'Admin' }}
            </template>
        </PageHeader>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div v-if="isModuleEnabled('users')" class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-100">Total Users</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ (stats.users_count || 0).toLocaleString() }}</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-3">
                            <UsersIcon class="h-8 w-8 text-white" />
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="isModuleEnabled('roles')" class="relative overflow-hidden rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 shadow-lg">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-emerald-100">Roles</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ (stats.roles_count || 0).toLocaleString() }}</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-3">
                            <ShieldCheckIcon class="h-8 w-8 text-white" />
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="isModuleEnabled('media')" class="relative overflow-hidden rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 p-6 shadow-lg">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-amber-100">Media Files</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ (stats.media_count || 0).toLocaleString() }}</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-3">
                            <FolderIcon class="h-8 w-8 text-white" />
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="isModuleEnabled('notifications')" class="relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 p-6 shadow-lg">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-100">Notifications</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ (stats.notifications_count || 0).toLocaleString() }}</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-3">
                            <BellIcon class="h-8 w-8 text-white" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <Card title="Quick Actions" class="mb-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4">
                <Link
                    v-if="isModuleEnabled('users')"
                    href="/admin/users/create"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                    <UsersIcon class="h-8 w-8 text-indigo-500 mb-2" />
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Add User</span>
                </Link>
                <Link
                    v-if="isModuleEnabled('roles')"
                    href="/admin/roles/create"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                    <ShieldCheckIcon class="h-8 w-8 text-green-500 mb-2" />
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Add Role</span>
                </Link>
                <Link
                    v-if="isModuleEnabled('media')"
                    href="/admin/media"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                    <FolderIcon class="h-8 w-8 text-amber-500 mb-2" />
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Upload Files</span>
                </Link>
                <Link
                    v-if="isModuleEnabled('settings')"
                    href="/admin/settings"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                    <Cog6ToothIcon class="h-8 w-8 text-gray-500 mb-2" />
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Settings</span>
                </Link>
            </div>
        </Card>

        <!-- Welcome Message -->
        <Card>
            <div class="p-6 text-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Welcome to your Admin Panel
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    Use the sidebar to navigate through the different sections of your application.
                </p>
            </div>
        </Card>
    </AdminLayout>
</template>
