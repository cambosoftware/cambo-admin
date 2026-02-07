<script setup>
import { ref, provide, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Sidebar from './Sidebar.vue'
import Navbar from './Navbar.vue'
import DefaultSidebarMenu from './DefaultSidebarMenu.vue'

// Tailwind safelist: lg:ml-16 lg:ml-64 lg:mr-16 lg:mr-64 lg:pt-14

const props = defineProps({
    title: {
        type: String,
        default: ''
    },
    breadcrumb: {
        type: Array,
        default: () => []
    },
    fluid: {
        type: Boolean,
        default: false
    },
    // Sidebar options
    sidebarCollapsible: {
        type: Boolean,
        default: null  // null = use config
    },
    sidebarCollapsedByDefault: {
        type: Boolean,
        default: null  // null = use config
    },
    sidebarExpandOnHover: {
        type: Boolean,
        default: null  // null = use config
    },
    sidebarMode: {
        type: String,
        default: 'overlay',  // 'overlay' = sidebar over content, 'push' = content adapts
        validator: (value) => ['overlay', 'push'].includes(value)
    },
    sidebarPosition: {
        type: String,
        default: 'left',
        validator: (value) => ['left', 'right', 'top'].includes(value)
    },
    sidebarTheme: {
        type: String,
        default: null,  // null = use config
        validator: (value) => value === null || ['dark', 'light', 'colorful'].includes(value)
    },
    // Show separate navbar (useful when sidebar is on left/right)
    showNavbar: {
        type: Boolean,
        default: null  // null = auto (hide when sidebar is on top)
    }
})

// Get config from server
const sidebarConfig = computed(() => page.props.config?.sidebar || {})

// Computed sidebar options with config fallback
const effectiveSidebarCollapsible = computed(() =>
    props.sidebarCollapsible !== null ? props.sidebarCollapsible : (sidebarConfig.value.collapsible ?? true)
)
const effectiveSidebarCollapsedByDefault = computed(() =>
    props.sidebarCollapsedByDefault !== null ? props.sidebarCollapsedByDefault : (sidebarConfig.value.collapsed_by_default ?? true)
)
const effectiveSidebarExpandOnHover = computed(() =>
    props.sidebarExpandOnHover !== null ? props.sidebarExpandOnHover : (sidebarConfig.value.expand_on_hover ?? true)
)
const effectiveSidebarTheme = computed(() =>
    props.sidebarTheme || sidebarConfig.value.theme || 'light'
)

// Sidebar state - respects collapsible and default props
const sidebarCollapsed = ref(true) // Will be updated after mount
const sidebarMobileOpen = ref(false)
const sidebarVisuallyCollapsed = ref(true) // Will be updated after mount

// Initialize sidebar state on mount
onMounted(() => {
    sidebarCollapsed.value = effectiveSidebarCollapsible.value && effectiveSidebarCollapsedByDefault.value
    sidebarVisuallyCollapsed.value = sidebarCollapsed.value
})

// Update visual state when sidebar emits change
const onSidebarVisualStateChange = (isCollapsed) => {
    sidebarVisuallyCollapsed.value = isCollapsed
}

// Check if top position
const isTopPosition = computed(() => props.sidebarPosition === 'top')

// Should we show the navbar?
const shouldShowNavbar = computed(() => {
    if (props.showNavbar !== null) return props.showNavbar
    // Auto: hide navbar when sidebar is on top (the top bar replaces it)
    return !isTopPosition.value
})

// Content margin/padding classes based on sidebar position and state
const contentMarginClass = computed(() => {
    // For top position, add padding-top to account for the fixed header bar
    if (isTopPosition.value) {
        return 'lg:pt-14'
    }

    const isRight = props.sidebarPosition === 'right'
    const isCollapsed = props.sidebarMode === 'overlay' || sidebarVisuallyCollapsed.value

    if (isRight) {
        return isCollapsed ? 'lg:mr-16' : 'lg:mr-64'
    }
    return isCollapsed ? 'lg:ml-16' : 'lg:ml-64'
})

// Provide to children
provide('sidebarCollapsed', sidebarCollapsed)
provide('sidebarMobileOpen', sidebarMobileOpen)

// Toggle functions
const toggleSidebar = () => {
    if (effectiveSidebarCollapsible.value) {
        sidebarCollapsed.value = !sidebarCollapsed.value
    }
}

const toggleMobileSidebar = () => {
    sidebarMobileOpen.value = !sidebarMobileOpen.value
}

const closeMobileSidebar = () => {
    sidebarMobileOpen.value = false
}

// Get page props for flash messages, user, etc.
const page = usePage()
const user = computed(() => page.props.auth?.user)
const flash = computed(() => page.props.flash)

// Provide user to children
provide('currentUser', user)
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <!-- Mobile sidebar backdrop -->
        <Transition
            enter-active-class="transition-opacity ease-linear duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-linear duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="sidebarMobileOpen"
                class="fixed inset-0 z-40 bg-gray-900/80 lg:hidden"
                @click="closeMobileSidebar"
            />
        </Transition>

        <!-- Sidebar -->
        <Sidebar
            :collapsed="sidebarCollapsed"
            :mobile-open="sidebarMobileOpen"
            :collapsible="effectiveSidebarCollapsible"
            :expand-on-hover="effectiveSidebarExpandOnHover"
            :mode="sidebarMode"
            :position="sidebarPosition"
            :theme="effectiveSidebarTheme"
            @toggle="toggleSidebar"
            @close-mobile="closeMobileSidebar"
            @visual-state-change="onSidebarVisualStateChange"
        >
            <template #header>
                <slot name="sidebar-header" />
            </template>
            <template #menu>
                <slot name="sidebar-menu">
                    <DefaultSidebarMenu />
                </slot>
            </template>
            <template #footer>
                <slot name="sidebar-footer" />
            </template>
        </Sidebar>

        <!-- Main content area -->
        <div
            class="transition-all duration-300"
            :class="contentMarginClass"
        >
            <!-- Navbar (hidden when sidebar is on top) -->
            <Navbar
                v-if="shouldShowNavbar"
                :title="title"
                :breadcrumb="breadcrumb"
                @toggle-sidebar="toggleMobileSidebar"
            >
                <template #actions>
                    <slot name="header-actions" />
                </template>
                <template #user-menu>
                    <slot name="user-menu" />
                </template>
            </Navbar>

            <!-- Page content -->
            <main class="py-6">
                <div
                    :class="[
                        fluid ? 'px-4 sm:px-6 lg:px-8' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8'
                    ]"
                >
                    <!-- Flash messages -->
                    <div v-if="flash?.success || flash?.error" class="mb-6">
                        <div
                            v-if="flash?.success"
                            class="rounded-md bg-green-50 dark:bg-green-900/50 p-4 border border-green-200 dark:border-green-800"
                        >
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ flash.success }}</p>
                                </div>
                            </div>
                        </div>
                        <div
                            v-if="flash?.error"
                            class="rounded-md bg-red-50 dark:bg-red-900/50 p-4 border border-red-200 dark:border-red-800"
                        >
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ flash.error }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Page content slot -->
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
