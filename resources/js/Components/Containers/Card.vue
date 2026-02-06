<script setup>
defineProps({
    title: {
        type: String,
        default: null
    },
    subtitle: {
        type: String,
        default: null
    },
    padding: {
        type: Boolean,
        default: true
    },
    hoverable: {
        type: Boolean,
        default: false
    },
    bordered: {
        type: Boolean,
        default: true
    },
    /**
     * Control overflow behavior
     * Use 'visible' when card contains dropdowns, tooltips, etc.
     */
    overflow: {
        type: String,
        default: 'hidden',
        validator: (v) => ['hidden', 'visible', 'auto'].includes(v)
    }
})
</script>

<template>
    <div
        :class="[
            'bg-white dark:bg-gray-800 rounded-xl transition-colors duration-200',
            overflow === 'hidden' ? 'overflow-hidden' : overflow === 'auto' ? 'overflow-auto' : 'overflow-visible',
            bordered ? 'ring-1 ring-gray-200 dark:ring-gray-700' : 'shadow-lg dark:shadow-gray-900/50',
            hoverable ? 'transition-shadow hover:shadow-lg dark:hover:shadow-gray-900/50 cursor-pointer' : ''
        ]"
    >
        <!-- Header -->
        <div
            v-if="title || $slots.header"
            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"
        >
            <slot name="header">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ title }}</h3>
                        <p v-if="subtitle" class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ subtitle }}</p>
                    </div>
                    <div v-if="$slots['header-actions']">
                        <slot name="header-actions" />
                    </div>
                </div>
            </slot>
        </div>

        <!-- Body -->
        <div :class="padding ? 'px-6 py-4' : ''">
            <slot />
        </div>

        <!-- Footer -->
        <div
            v-if="$slots.footer"
            class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50"
        >
            <slot name="footer" />
        </div>
    </div>
</template>
