<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import SidebarItem from './SidebarItem.vue'
import SidebarDivider from './SidebarDivider.vue'
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
const modules = computed(() => page.props.modules || page.props.config?.modules || {})

// Helper to check if module is enabled
const isModuleEnabled = (module) => modules.value[module] !== false

// Get current path for active state
const currentPath = computed(() => page.url || window.location.pathname)
const isActive = (path) => currentPath.value.startsWith(path)
</script>

<template>
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
