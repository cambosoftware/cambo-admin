<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import SidebarItem from '@/Components/Layout/SidebarItem.vue'
import SidebarDivider from '@/Components/Layout/SidebarDivider.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import CardGrid from '@/Components/Containers/CardGrid.vue'
import StatCard from '@/Components/Stats/StatCard.vue'
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
                :href="route('cambo.dashboard')"
                :active="route().current('cambo.dashboard')"
            />

            <SidebarDivider v-if="isModuleEnabled('users') || isModuleEnabled('roles')" label="Administration" />

            <!-- Users -->
            <SidebarItem
                v-if="isModuleEnabled('users')"
                label="Users"
                :icon="UsersIcon"
                :href="route('cambo.users.index')"
                :active="route().current('cambo.users.*')"
            />

            <!-- Roles -->
            <SidebarItem
                v-if="isModuleEnabled('roles')"
                label="Roles"
                :icon="ShieldCheckIcon"
                :href="route('cambo.roles.index')"
                :active="route().current('cambo.roles.*')"
            />

            <SidebarDivider v-if="isModuleEnabled('media') || isModuleEnabled('notifications') || isModuleEnabled('activity-log')" label="Content" />

            <!-- Media -->
            <SidebarItem
                v-if="isModuleEnabled('media')"
                label="Media"
                :icon="FolderIcon"
                :href="route('cambo.media.index')"
                :active="route().current('cambo.media.*')"
            />

            <!-- Notifications -->
            <SidebarItem
                v-if="isModuleEnabled('notifications')"
                label="Notifications"
                :icon="BellIcon"
                :href="route('cambo.notifications.index')"
                :active="route().current('cambo.notifications.*')"
            />

            <!-- Activity Log -->
            <SidebarItem
                v-if="isModuleEnabled('activity-log')"
                label="Activity Log"
                :icon="ClockIcon"
                :href="route('cambo.activity-log.index')"
                :active="route().current('cambo.activity-log.*')"
            />

            <SidebarDivider v-if="isModuleEnabled('settings') || isModuleEnabled('import-export') || isModuleEnabled('i18n') || isModuleEnabled('themes')" label="Settings" />

            <!-- Import/Export -->
            <SidebarItem
                v-if="isModuleEnabled('import-export')"
                label="Import/Export"
                :icon="ArrowDownTrayIcon"
                :href="route('cambo.import-export.index')"
                :active="route().current('cambo.import-export.*')"
            />

            <!-- Translations -->
            <SidebarItem
                v-if="isModuleEnabled('i18n')"
                label="Translations"
                :icon="LanguageIcon"
                :href="route('cambo.translations.index')"
                :active="route().current('cambo.translations.*')"
            />

            <!-- Themes -->
            <SidebarItem
                v-if="isModuleEnabled('themes')"
                label="Themes"
                :icon="SwatchIcon"
                :href="route('cambo.themes.index')"
                :active="route().current('cambo.themes.*')"
            />

            <!-- Settings -->
            <SidebarItem
                v-if="isModuleEnabled('settings')"
                label="Settings"
                :icon="Cog6ToothIcon"
                :href="route('cambo.settings.index')"
                :active="route().current('cambo.settings.*')"
            />
        </template>

        <!-- Page Content -->
        <PageHeader :title="title">
            <template #subtitle>
                Welcome back, {{ user?.name || 'Admin' }}
            </template>
        </PageHeader>

        <!-- Stats Grid -->
        <CardGrid :cols="4" class="mb-8">
            <StatCard
                v-if="isModuleEnabled('users')"
                title="Total Users"
                :value="stats.users_count || 0"
                :icon="UsersIcon"
                color="primary"
            />
            <StatCard
                v-if="isModuleEnabled('roles')"
                title="Roles"
                :value="stats.roles_count || 0"
                :icon="ShieldCheckIcon"
                color="success"
            />
            <StatCard
                v-if="isModuleEnabled('media')"
                title="Media Files"
                :value="stats.media_count || 0"
                :icon="FolderIcon"
                color="warning"
            />
            <StatCard
                v-if="isModuleEnabled('notifications')"
                title="Notifications"
                :value="stats.notifications_count || 0"
                :icon="BellIcon"
                color="info"
            />
        </CardGrid>

        <!-- Quick Actions -->
        <Card title="Quick Actions" class="mb-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4">
                <Link
                    v-if="isModuleEnabled('users')"
                    :href="route('cambo.users.create')"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                    <UsersIcon class="h-8 w-8 text-indigo-500 mb-2" />
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Add User</span>
                </Link>
                <Link
                    v-if="isModuleEnabled('roles')"
                    :href="route('cambo.roles.create')"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                    <ShieldCheckIcon class="h-8 w-8 text-green-500 mb-2" />
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Add Role</span>
                </Link>
                <Link
                    v-if="isModuleEnabled('media')"
                    :href="route('cambo.media.index')"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                    <FolderIcon class="h-8 w-8 text-amber-500 mb-2" />
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Upload Files</span>
                </Link>
                <Link
                    v-if="isModuleEnabled('settings')"
                    :href="route('cambo.settings.index')"
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
