<script setup>
import { computed, provide, toRef, ref, watch, h } from 'vue'

// Mobile context wrapper component
const MobileMenuWrapper = {
    setup(props, { slots }) {
        // Override sidebarCollapsed to always be false in mobile context
        provide('sidebarCollapsed', ref(false))
        return () => slots.default?.()
    }
}

const props = defineProps({
    collapsed: {
        type: Boolean,
        default: false
    },
    mobileOpen: {
        type: Boolean,
        default: false
    },
    theme: {
        type: String,
        default: 'colorful',
        validator: (value) => ['dark', 'light', 'colorful'].includes(value)
    },
    logo: {
        type: String,
        default: null
    },
    logoCollapsed: {
        type: String,
        default: null
    },
    title: {
        type: String,
        default: 'CamboAdmin'
    },
    collapsible: {
        type: Boolean,
        default: true
    },
    expandOnHover: {
        type: Boolean,
        default: false
    },
    mode: {
        type: String,
        default: 'overlay',
        validator: (value) => ['overlay', 'push'].includes(value)
    },
    accordion: {
        type: Boolean,
        default: true  // Only one submenu open at a time
    },
    position: {
        type: String,
        default: 'left',
        validator: (value) => ['left', 'right', 'top'].includes(value)
    }
})

const emit = defineEmits(['toggle', 'close-mobile', 'visual-state-change'])

// Hover state for expandOnHover mode
const isHovered = ref(false)

// Effective visual state: always expanded if not collapsible, otherwise respects collapsed/hover state
const isVisuallyCollapsed = computed(() => {
    // If not collapsible, always show expanded
    if (!props.collapsible) {
        return false
    }
    // If expandOnHover mode and hovered, show expanded
    if (props.expandOnHover && props.collapsed && isHovered.value) {
        return false
    }
    return props.collapsed
})

// Check if top position
const isTopPosition = computed(() => props.position === 'top')

// Provide the visual collapsed state to child components (SidebarItem, SidebarDivider)
// This is for desktop - mobile will override this via MobileMenuWrapper
provide('sidebarCollapsed', isVisuallyCollapsed)
provide('sidebarTheme', toRef(props, 'theme'))
provide('sidebarPosition', toRef(props, 'position'))

// Accordion mode: track which submenu is open
const activeSubmenu = ref(null)
provide('sidebarAccordion', toRef(props, 'accordion'))
provide('sidebarActiveSubmenu', activeSubmenu)
provide('setSidebarActiveSubmenu', (id) => {
    activeSubmenu.value = id
})

// Emit visual state changes for push mode
watch(isVisuallyCollapsed, (newVal) => {
    emit('visual-state-change', newVal)
}, { immediate: true })

// Mouse event handlers for expandOnHover mode
const onMouseEnter = () => {
    if (props.collapsible && props.expandOnHover && props.collapsed) {
        isHovered.value = true
    }
}

const onMouseLeave = () => {
    if (props.collapsible && props.expandOnHover) {
        isHovered.value = false
    }
}

// Theme classes
const themeClasses = computed(() => {
    if (props.theme === 'colorful') {
        return {
            sidebar: 'bg-indigo-600',
            header: 'border-indigo-500',
            text: 'text-white',
            textMuted: 'text-indigo-200',
            hover: 'hover:bg-indigo-500',
            border: 'border-indigo-500'
        }
    }
    if (props.theme === 'dark') {
        return {
            sidebar: 'bg-gray-900',
            header: 'border-gray-800',
            text: 'text-white',
            textMuted: 'text-gray-400',
            hover: 'hover:bg-gray-800',
            border: 'border-gray-800'
        }
    }
    return {
        sidebar: 'bg-white border-r border-gray-200',
        header: 'border-gray-200',
        text: 'text-gray-900',
        textMuted: 'text-gray-500',
        hover: 'hover:bg-gray-100',
        border: 'border-gray-200'
    }
})

// Width classes - use visual state
const widthClass = computed(() => {
    return isVisuallyCollapsed.value ? 'w-16' : 'w-64'
})

// In overlay mode, when expanded by hover, add shadow and higher z-index
const isOverlayExpanded = computed(() => {
    return props.mode === 'overlay' && props.collapsed && isHovered.value
})

// Position classes
const isRight = computed(() => props.position === 'right')
const positionClass = computed(() => {
    if (isTopPosition.value) return 'top-0 left-0 right-0'
    return isRight.value ? 'right-0' : 'left-0'
})

// Mobile transition classes based on position
const mobileTransitionClasses = computed(() => {
    if (isRight.value) {
        return {
            enterFrom: 'translate-x-full',
            enterTo: 'translate-x-0',
            leaveFrom: 'translate-x-0',
            leaveTo: 'translate-x-full'
        }
    }
    return {
        enterFrom: '-translate-x-full',
        enterTo: 'translate-x-0',
        leaveFrom: 'translate-x-0',
        leaveTo: '-translate-x-full'
    }
})
</script>

<template>
    <!-- Mobile sidebar (left/right positions only) -->
    <Transition
        v-if="!isTopPosition"
        enter-active-class="transition ease-in-out duration-300 transform"
        :enter-from-class="mobileTransitionClasses.enterFrom"
        :enter-to-class="mobileTransitionClasses.enterTo"
        leave-active-class="transition ease-in-out duration-300 transform"
        :leave-from-class="mobileTransitionClasses.leaveFrom"
        :leave-to-class="mobileTransitionClasses.leaveTo"
    >
        <aside
            v-show="mobileOpen"
            class="fixed inset-y-0 z-50 w-64 lg:hidden"
            :class="[themeClasses.sidebar, positionClass]"
        >
            <!-- Mobile header -->
            <div
                class="flex h-16 items-center justify-between px-4 border-b"
                :class="themeClasses.header"
            >
                <div class="flex items-center gap-3">
                    <img
                        v-if="logo"
                        :src="logo"
                        :alt="title"
                        class="h-8 w-auto"
                    />
                    <span
                        class="text-lg font-semibold"
                        :class="themeClasses.text"
                    >
                        {{ title }}
                    </span>
                </div>
                <button
                    type="button"
                    class="rounded-md p-2"
                    :class="[themeClasses.textMuted, themeClasses.hover]"
                    @click="emit('close-mobile')"
                >
                    <span class="sr-only">Fermer le menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile menu (always expanded via MobileMenuWrapper) -->
            <nav class="flex-1 overflow-y-auto py-4 px-3">
                <MobileMenuWrapper>
                    <slot name="menu" />
                </MobileMenuWrapper>
            </nav>

            <!-- Mobile footer -->
            <div
                v-if="$slots.footer"
                class="border-t p-4"
                :class="themeClasses.border"
            >
                <slot name="footer" />
            </div>
        </aside>
    </Transition>

    <!-- Desktop sidebar (left/right positions only) -->
    <aside
        v-if="!isTopPosition"
        class="fixed inset-y-0 hidden lg:flex lg:flex-col transition-all duration-300"
        :class="[
            themeClasses.sidebar,
            widthClass,
            positionClass,
            isOverlayExpanded ? 'z-40 shadow-2xl' : 'z-30'
        ]"
        @mouseenter="onMouseEnter"
        @mouseleave="onMouseLeave"
    >
        <!-- Desktop header -->
        <div
            class="flex h-16 items-center border-b px-4"
            :class="[
                themeClasses.header,
                isVisuallyCollapsed ? 'justify-center' : 'justify-between'
            ]"
        >
            <div class="flex items-center gap-3 overflow-hidden">
                <img
                    v-if="logo && !isVisuallyCollapsed"
                    :src="logo"
                    :alt="title"
                    class="h-8 w-auto flex-shrink-0"
                />
                <img
                    v-else-if="logoCollapsed && isVisuallyCollapsed"
                    :src="logoCollapsed"
                    :alt="title"
                    class="h-8 w-8 flex-shrink-0"
                />
                <div
                    v-else-if="isVisuallyCollapsed"
                    class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0"
                >
                    <span class="text-white font-bold text-sm">
                        {{ title.charAt(0).toUpperCase() }}
                    </span>
                </div>
                <span
                    v-if="!isVisuallyCollapsed"
                    class="text-lg font-semibold truncate"
                    :class="themeClasses.text"
                >
                    <slot name="header">
                        {{ title }}
                    </slot>
                </span>
            </div>
            <button
                v-if="collapsible && !isVisuallyCollapsed && !expandOnHover"
                type="button"
                class="rounded-md p-1.5 transition-colors"
                :class="[themeClasses.textMuted, themeClasses.hover]"
                @click="emit('toggle')"
            >
                <span class="sr-only">Réduire le menu</span>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                </svg>
            </button>
        </div>

        <!-- Expand button when collapsed (only in button mode, not expandOnHover mode) -->
        <button
            v-if="collapsible && collapsed && !expandOnHover"
            type="button"
            class="absolute top-20 z-40 rounded-full border bg-white p-1 shadow-md hover:bg-gray-50"
            :class="isRight ? '-left-3' : '-right-3'"
            @click="emit('toggle')"
        >
            <svg
                class="h-4 w-4 text-gray-600"
                :class="isRight ? 'rotate-180' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>

        <!-- Desktop menu -->
        <nav class="flex-1 overflow-y-auto py-4" :class="isVisuallyCollapsed ? 'px-2' : 'px-3'">
            <slot name="menu" />
        </nav>

        <!-- Desktop footer -->
        <div
            v-if="$slots.footer"
            class="border-t p-4"
            :class="[themeClasses.border, isVisuallyCollapsed ? 'px-2' : 'px-4']"
        >
            <slot name="footer" />
        </div>
    </aside>

    <!-- Top position (horizontal menu bar) -->
    <header
        v-if="isTopPosition"
        class="fixed top-0 left-0 right-0 z-30 hidden lg:block"
        :class="[themeClasses.sidebar, 'border-b', themeClasses.border]"
    >
        <div class="flex h-14 items-center justify-between px-4">
            <!-- Logo / Title -->
            <div class="flex items-center gap-3 flex-shrink-0">
                <img
                    v-if="logo"
                    :src="logo"
                    :alt="title"
                    class="h-8 w-auto"
                />
                <span
                    class="text-lg font-semibold"
                    :class="themeClasses.text"
                >
                    <slot name="header">
                        {{ title }}
                    </slot>
                </span>
            </div>

            <!-- Horizontal menu -->
            <nav class="flex items-center gap-1">
                <slot name="menu" />
            </nav>

            <!-- Right actions (optional) -->
            <div v-if="$slots.footer" class="flex items-center gap-2">
                <slot name="footer" />
            </div>
        </div>
    </header>

    <!-- Mobile menu for top position -->
    <Transition
        v-if="isTopPosition"
        enter-active-class="transition ease-in-out duration-300 transform"
        enter-from-class="-translate-y-full"
        enter-to-class="translate-y-0"
        leave-active-class="transition ease-in-out duration-300 transform"
        leave-from-class="translate-y-0"
        leave-to-class="-translate-y-full"
    >
        <aside
            v-show="mobileOpen"
            class="fixed inset-x-0 top-0 z-50 lg:hidden max-h-screen overflow-y-auto"
            :class="themeClasses.sidebar"
        >
            <!-- Mobile header -->
            <div
                class="flex h-14 items-center justify-between px-4 border-b"
                :class="themeClasses.header"
            >
                <div class="flex items-center gap-3">
                    <img
                        v-if="logo"
                        :src="logo"
                        :alt="title"
                        class="h-8 w-auto"
                    />
                    <span
                        class="text-lg font-semibold"
                        :class="themeClasses.text"
                    >
                        {{ title }}
                    </span>
                </div>
                <button
                    type="button"
                    class="rounded-md p-2"
                    :class="[themeClasses.textMuted, themeClasses.hover]"
                    @click="emit('close-mobile')"
                >
                    <span class="sr-only">Fermer le menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile menu (vertical, always expanded) -->
            <nav class="py-4 px-3">
                <MobileMenuWrapper>
                    <slot name="menu" />
                </MobileMenuWrapper>
            </nav>
        </aside>
    </Transition>
</template>
