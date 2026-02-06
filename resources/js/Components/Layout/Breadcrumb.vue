<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    items: {
        type: Array,
        required: true,
        // Each item: { label: string, href?: string, route?: string, icon?: string }
    },
    showHome: {
        type: Boolean,
        default: true
    },
    homeHref: {
        type: String,
        default: '/'
    },
    homeLabel: {
        type: String,
        default: 'Accueil'
    }
})

// Resolve href for an item
const resolveHref = (item) => {
    if (item.href) return item.href
    if (item.route) {
        try {
            return route(item.route)
        } catch {
            return '#'
        }
    }
    return null
}
</script>

<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <!-- Home -->
            <li v-if="showHome">
                <Link
                    :href="homeHref"
                    class="text-gray-400 hover:text-gray-500 transition-colors"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="sr-only">{{ homeLabel }}</span>
                </Link>
            </li>

            <!-- Items -->
            <li
                v-for="(item, index) in items"
                :key="item.label"
                class="flex items-center"
            >
                <!-- Separator -->
                <svg
                    class="h-4 w-4 flex-shrink-0 text-gray-300"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>

                <!-- Item -->
                <Link
                    v-if="resolveHref(item)"
                    :href="resolveHref(item)"
                    class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors"
                >
                    {{ item.label }}
                </Link>
                <span
                    v-else
                    class="ml-2 text-sm font-medium"
                    :class="index === items.length - 1 ? 'text-gray-900' : 'text-gray-500'"
                    :aria-current="index === items.length - 1 ? 'page' : undefined"
                >
                    {{ item.label }}
                </span>
            </li>
        </ol>
    </nav>
</template>
