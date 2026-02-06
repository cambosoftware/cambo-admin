<script setup>
import { ref, computed, inject, watch, nextTick } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
    label: {
        type: String,
        required: true
    },
    icon: {
        type: [Object, Function],  // Vue component (from @heroicons/vue)
        default: null
    },
    href: {
        type: String,
        default: null
    },
    route: {
        type: String,
        default: null
    },
    children: {
        type: Array,
        default: () => []
    },
    badge: {
        type: [String, Number],
        default: null
    },
    badgeVariant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'success', 'warning', 'danger'].includes(value)
    },
    active: {
        type: Boolean,
        default: null
    },
    theme: {
        type: String,
        default: 'dark',
        validator: (value) => ['dark', 'light'].includes(value)
    }
})

// Get sidebar state from parent
const sidebarCollapsed = inject('sidebarCollapsed', ref(false))
const sidebarAccordion = inject('sidebarAccordion', ref(false))
const sidebarActiveSubmenu = inject('sidebarActiveSubmenu', ref(null))
const setSidebarActiveSubmenu = inject('setSidebarActiveSubmenu', () => {})
const sidebarPosition = inject('sidebarPosition', ref('left'))

// Check if horizontal (top position)
const isHorizontal = computed(() => sidebarPosition.value === 'top')

// Unique ID for this item (for accordion mode)
const itemId = props.label + '-' + Math.random().toString(36).substr(2, 9)

// Submenu state
const isOpen = ref(false)

// Watch active submenu for accordion mode
watch(sidebarActiveSubmenu, (activeId) => {
    if (sidebarAccordion.value && activeId !== itemId && isOpen.value) {
        isOpen.value = false
    }
})

// Check if current route matches
const page = usePage()
const isActive = computed(() => {
    if (props.active !== null) return props.active
    if (props.href) {
        return page.url === props.href || page.url.startsWith(props.href + '/')
    }
    if (props.route) {
        // Check if current route name matches
        return page.component?.startsWith(props.route)
    }
    // Check if any child is active
    if (props.children.length > 0) {
        return props.children.some(child => {
            if (child.href) return page.url === child.href || page.url.startsWith(child.href + '/')
            return false
        })
    }
    return false
})

// Open submenu if a child is active
watch(isActive, (active) => {
    if (active && props.children.length > 0) {
        isOpen.value = true
        // Notify accordion
        if (sidebarAccordion.value) {
            setSidebarActiveSubmenu(itemId)
        }
    }
}, { immediate: true })

// Has children
const hasChildren = computed(() => props.children.length > 0)

// Toggle submenu
const toggleSubmenu = async () => {
    if (hasChildren.value) {
        const willOpen = !isOpen.value
        if (willOpen && sidebarAccordion.value) {
            // First close others, then open this one
            setSidebarActiveSubmenu(itemId)
            await nextTick()
            isOpen.value = true
        } else {
            isOpen.value = willOpen
        }
    }
}

// Resolve href
const resolvedHref = computed(() => {
    if (props.href) return props.href
    if (props.route) {
        try {
            return route(props.route)
        } catch {
            return '#'
        }
    }
    return '#'
})

// Get theme from parent or prop
const sidebarTheme = inject('sidebarTheme', ref('dark'))
const currentTheme = computed(() => props.theme || sidebarTheme.value || 'dark')

// Theme classes
const themeClasses = computed(() => {
    const theme = currentTheme.value

    if (theme === 'colorful') {
        return {
            item: 'text-indigo-100 hover:bg-indigo-500 hover:text-white',
            itemActive: 'bg-indigo-700 text-white font-semibold',
            icon: 'text-indigo-300',
            iconActive: 'text-white',
            submenu: 'bg-indigo-700/50',
            badge: {
                primary: 'bg-white text-indigo-600',
                success: 'bg-emerald-400 text-emerald-900',
                warning: 'bg-amber-300 text-amber-900',
                danger: 'bg-rose-500 text-white'
            }
        }
    }

    if (theme === 'dark') {
        return {
            item: 'text-white hover:bg-gray-800',
            itemActive: 'bg-gray-800 text-white font-semibold',
            icon: 'text-gray-300',
            iconActive: 'text-white',
            submenu: 'bg-gray-950/50',
            badge: {
                primary: 'bg-primary-600 text-white',
                success: 'bg-green-600 text-white',
                warning: 'bg-amber-500 text-white',
                danger: 'bg-red-600 text-white'
            }
        }
    }

    return {
        item: 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
        itemActive: 'bg-primary-50 text-primary-700',
        icon: 'text-gray-500',
        iconActive: 'text-primary-600',
        submenu: 'bg-gray-50',
        badge: {
            primary: 'bg-primary-600 text-white',
            success: 'bg-green-600 text-white',
            warning: 'bg-amber-500 text-white',
            danger: 'bg-red-600 text-white'
        }
    }
})

</script>

<template>
    <!-- Horizontal mode (top position) -->
    <div v-if="isHorizontal" class="relative group">
        <component
            :is="hasChildren ? 'button' : Link"
            :href="hasChildren ? undefined : resolvedHref"
            :type="hasChildren ? 'button' : undefined"
            class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition-colors whitespace-nowrap"
            :class="[isActive ? themeClasses.itemActive : themeClasses.item]"
        >
            <!-- Icon -->
            <component
                v-if="icon"
                :is="icon"
                class="h-4 w-4 flex-shrink-0"
                :class="isActive ? themeClasses.iconActive : themeClasses.icon"
            />

            <!-- Label -->
            <span>{{ label }}</span>

            <!-- Badge -->
            <span
                v-if="badge"
                class="inline-flex items-center rounded-full px-1.5 py-0.5 text-xs font-medium"
                :class="themeClasses.badge[badgeVariant]"
            >
                {{ badge }}
            </span>

            <!-- Chevron for submenu -->
            <svg
                v-if="hasChildren"
                class="h-3 w-3 flex-shrink-0"
                :class="isActive ? themeClasses.iconActive : themeClasses.icon"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </component>

        <!-- Dropdown menu (cascading) -->
        <div
            v-if="hasChildren"
            class="absolute left-0 top-full pt-1 hidden group-hover:block z-50"
        >
            <div
                class="rounded-md shadow-lg py-1"
                :class="theme === 'dark' ? 'bg-gray-800' : 'bg-white border border-gray-200'"
            >
                <template v-for="child in children" :key="child.href || child.label">
                    <!-- Child with sub-children (nested dropdown) -->
                    <div v-if="child.children && child.children.length > 0" class="relative group/nested">
                        <button
                            type="button"
                            class="w-full flex items-center justify-between px-4 py-2 text-sm transition-colors whitespace-nowrap"
                            :class="themeClasses.item"
                        >
                            <span>{{ child.label }}</span>
                            <svg class="h-3 w-3 ml-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                        <!-- Nested dropdown (overlaps parent slightly) -->
                        <div class="absolute left-full top-[-1px] -ml-1 hidden group-hover/nested:block z-50">
                            <div
                                class="rounded-md shadow-lg py-1"
                                :class="theme === 'dark' ? 'bg-gray-800' : 'bg-white border border-gray-200'"
                            >
                                <Link
                                    v-for="subChild in child.children"
                                    :key="subChild.href || subChild.label"
                                    :href="subChild.href || '#'"
                                    class="block px-4 py-2 text-sm transition-colors whitespace-nowrap"
                                    :class="[
                                        (subChild.href && (page.url === subChild.href || page.url.startsWith(subChild.href + '/')))
                                            ? themeClasses.itemActive
                                            : themeClasses.item
                                    ]"
                                >
                                    {{ subChild.label }}
                                </Link>
                            </div>
                        </div>
                    </div>
                    <!-- Simple child link -->
                    <Link
                        v-else
                        :href="child.href || '#'"
                        class="flex items-center justify-between px-4 py-2 text-sm transition-colors whitespace-nowrap"
                        :class="[
                            (child.href && (page.url === child.href || page.url.startsWith(child.href + '/')))
                                ? themeClasses.itemActive
                                : themeClasses.item
                        ]"
                    >
                        <span>{{ child.label }}</span>
                        <span
                            v-if="child.badge"
                            class="inline-flex items-center rounded-full px-1.5 py-0.5 text-xs font-medium"
                            :class="themeClasses.badge[child.badgeVariant || 'primary']"
                        >
                            {{ child.badge }}
                        </span>
                    </Link>
                </template>
            </div>
        </div>
    </div>

    <!-- Vertical mode (left/right position) -->
    <div v-else class="mb-1">
        <!-- Item without children or with link -->
        <component
            :is="hasChildren ? 'button' : Link"
            :href="hasChildren ? undefined : resolvedHref"
            :type="hasChildren ? 'button' : undefined"
            class="w-full flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-medium transition-colors"
            :class="[
                isActive ? themeClasses.itemActive : themeClasses.item,
                sidebarCollapsed ? 'justify-center px-2' : ''
            ]"
            @click="hasChildren ? toggleSubmenu() : undefined"
        >
            <!-- Icon with badge indicator when collapsed -->
            <div class="relative flex-shrink-0">
                <component
                    v-if="icon"
                    :is="icon"
                    class="h-5 w-5"
                    :class="isActive ? themeClasses.iconActive : themeClasses.icon"
                />

                <!-- Custom icon slot -->
                <slot v-else name="icon" />

                <!-- Mini badge when collapsed -->
                <span
                    v-if="badge && sidebarCollapsed"
                    class="absolute -top-1.5 -right-1.5 inline-flex items-center justify-center min-w-[16px] h-4 px-1 text-[10px] font-bold rounded-full"
                    :class="themeClasses.badge[badgeVariant]"
                >
                    {{ badge }}
                </span>
            </div>

            <!-- Label (hidden when collapsed) -->
            <span
                v-if="!sidebarCollapsed"
                class="flex-1 text-left truncate"
            >
                {{ label }}
            </span>

            <!-- Badge -->
            <span
                v-if="badge && !sidebarCollapsed"
                class="ml-auto inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium"
                :class="themeClasses.badge[badgeVariant]"
            >
                {{ badge }}
            </span>

            <!-- Chevron for submenu -->
            <svg
                v-if="hasChildren && !sidebarCollapsed"
                class="ml-auto h-4 w-4 flex-shrink-0 transition-transform duration-200"
                :class="[
                    isOpen ? 'rotate-90' : '',
                    isActive ? themeClasses.iconActive : themeClasses.icon
                ]"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </component>

        <!-- Tooltip when collapsed -->
        <div
            v-if="sidebarCollapsed"
            class="fixed left-16 ml-2 z-50 hidden group-hover:block"
        >
            <div class="rounded-md bg-gray-900 px-2 py-1 text-xs text-white shadow-lg">
                {{ label }}
            </div>
        </div>

        <!-- Submenu -->
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 -translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-1"
        >
            <div
                v-if="hasChildren && isOpen && !sidebarCollapsed"
                class="mt-1 space-y-1 rounded-md py-1"
                :class="themeClasses.submenu"
            >
                <Link
                    v-for="child in children"
                    :key="child.href || child.label"
                    :href="child.href || '#'"
                    class="flex items-center gap-x-3 rounded-md py-2 pl-10 pr-3 text-sm transition-colors"
                    :class="[
                        (child.href && (page.url === child.href || page.url.startsWith(child.href + '/')))
                            ? themeClasses.itemActive
                            : themeClasses.item
                    ]"
                >
                    <span class="truncate">{{ child.label }}</span>
                    <span
                        v-if="child.badge"
                        class="ml-auto inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium"
                        :class="themeClasses.badge[child.badgeVariant || 'primary']"
                    >
                        {{ child.badge }}
                    </span>
                </Link>
            </div>
        </Transition>
    </div>
</template>
