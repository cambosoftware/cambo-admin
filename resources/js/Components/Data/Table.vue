<script setup>
defineProps({
    striped: {
        type: Boolean,
        default: false
    },
    hoverable: {
        type: Boolean,
        default: true
    },
    bordered: {
        type: Boolean,
        default: false
    },
    compact: {
        type: Boolean,
        default: false
    },
    stickyHeader: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <div class="relative overflow-hidden">
        <!-- Loading overlay -->
        <div
            v-if="loading"
            class="absolute inset-0 bg-white/60 dark:bg-gray-900/60 z-10 flex items-center justify-center"
        >
            <svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div class="overflow-x-auto">
            <table
                :class="[
                    'min-w-full divide-y divide-gray-200 dark:divide-gray-700',
                    bordered ? 'border border-gray-200 dark:border-gray-700' : ''
                ]"
            >
                <slot />
            </table>
        </div>
    </div>
</template>
