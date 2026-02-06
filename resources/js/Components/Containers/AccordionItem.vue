<script setup>
import { inject, computed, getCurrentInstance } from 'vue'

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    subtitle: {
        type: String,
        default: null
    },
    defaultOpen: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    }
})

const instance = getCurrentInstance()
const id = instance.uid

const { openItems, toggle } = inject('accordion')

if (props.defaultOpen) {
    openItems.value.add(id)
}

const isOpen = computed(() => openItems.value.has(id))

const handleToggle = () => {
    if (!props.disabled) toggle(id)
}
</script>

<template>
    <div>
        <!-- Header -->
        <button
            type="button"
            :class="[
                'flex items-center justify-between w-full px-5 py-4 text-left transition-colors',
                disabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer',
                isOpen ? 'bg-gray-50' : ''
            ]"
            :disabled="disabled"
            @click="handleToggle"
        >
            <div class="min-w-0 flex-1">
                <span class="text-sm font-semibold text-gray-900">{{ title }}</span>
                <span v-if="subtitle" class="block text-xs text-gray-500 mt-0.5">{{ subtitle }}</span>
            </div>
            <svg
                :class="[
                    'h-5 w-5 text-gray-400 flex-shrink-0 ml-4 transition-transform duration-200',
                    isOpen ? 'rotate-180' : ''
                ]"
                viewBox="0 0 20 20"
                fill="currentColor"
            >
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <!-- Content -->
        <Transition
            enter-active-class="transition-all duration-200 ease-out overflow-hidden"
            enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-96 opacity-100"
            leave-active-class="transition-all duration-150 ease-in overflow-hidden"
            leave-from-class="max-h-96 opacity-100"
            leave-to-class="max-h-0 opacity-0"
        >
            <div v-if="isOpen" class="px-5 pb-4 text-sm text-gray-600">
                <slot />
            </div>
        </Transition>
    </div>
</template>
