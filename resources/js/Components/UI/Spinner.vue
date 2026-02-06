<script setup>
import { computed } from 'vue'

const props = defineProps({
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(v)
    },
    color: {
        type: String,
        default: 'primary',
        validator: (v) => ['primary', 'white', 'gray', 'success', 'danger'].includes(v)
    },
    label: {
        type: String,
        default: 'Chargement...'
    }
})

const sizeClasses = computed(() => {
    const sizes = {
        xs: 'h-3 w-3',
        sm: 'h-4 w-4',
        md: 'h-6 w-6',
        lg: 'h-8 w-8',
        xl: 'h-12 w-12'
    }
    return sizes[props.size]
})

const colorClasses = computed(() => {
    const colors = {
        primary: 'text-primary-600',
        white: 'text-white',
        gray: 'text-gray-400',
        success: 'text-emerald-600',
        danger: 'text-red-600'
    }
    return colors[props.color]
})
</script>

<template>
    <svg
        :class="['animate-spin', sizeClasses, colorClasses]"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        role="status"
        :aria-label="label"
    >
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
    </svg>
</template>
