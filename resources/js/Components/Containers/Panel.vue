<script setup>
import { ref } from 'vue'

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    subtitle: {
        type: String,
        default: null
    },
    collapsible: {
        type: Boolean,
        default: false
    },
    defaultOpen: {
        type: Boolean,
        default: true
    }
})

const isOpen = ref(props.collapsible ? props.defaultOpen : true)
</script>

<template>
    <div class="bg-white rounded-xl ring-1 ring-gray-200 overflow-hidden">
        <!-- Header -->
        <component
            :is="collapsible ? 'button' : 'div'"
            :type="collapsible ? 'button' : undefined"
            :class="[
                'flex items-center justify-between w-full px-5 py-3 border-b border-gray-200',
                collapsible ? 'hover:bg-gray-50 cursor-pointer' : ''
            ]"
            @click="collapsible && (isOpen = !isOpen)"
        >
            <div>
                <h3 class="text-sm font-semibold text-gray-900">{{ title }}</h3>
                <p v-if="subtitle" class="text-xs text-gray-500">{{ subtitle }}</p>
            </div>
            <div class="flex items-center gap-2">
                <slot name="header-actions" />
                <svg
                    v-if="collapsible"
                    :class="['h-4 w-4 text-gray-400 transition-transform duration-200', isOpen ? 'rotate-180' : '']"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </div>
        </component>

        <!-- Content -->
        <div v-show="!collapsible || isOpen" class="px-5 py-4">
            <slot />
        </div>

        <!-- Footer -->
        <div v-if="$slots.footer" class="px-5 py-3 border-t border-gray-200 bg-gray-50">
            <slot name="footer" />
        </div>
    </div>
</template>
