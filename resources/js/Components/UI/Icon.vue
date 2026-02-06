<script setup>
import { computed } from 'vue'

const props = defineProps({
    icon: {
        type: [Object, Function],
        required: true
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['xs', 'sm', 'md', 'lg', 'xl', '2xl'].includes(v)
    },
    color: {
        type: String,
        default: 'current'
    },
    stroke: {
        type: Number,
        default: null
    }
})

const sizeClasses = {
    xs: 'w-3 h-3',
    sm: 'w-4 h-4',
    md: 'w-5 h-5',
    lg: 'w-6 h-6',
    xl: 'w-8 h-8',
    '2xl': 'w-10 h-10'
}

const colorClasses = {
    current: 'text-current',
    primary: 'text-indigo-600',
    secondary: 'text-gray-500',
    success: 'text-emerald-600',
    danger: 'text-red-600',
    warning: 'text-amber-600',
    info: 'text-sky-600',
    white: 'text-white',
    black: 'text-gray-900',
    muted: 'text-gray-400'
}

const classes = computed(() => [
    sizeClasses[props.size],
    colorClasses[props.color] || props.color,
    'flex-shrink-0'
])

const strokeWidth = computed(() => props.stroke)
</script>

<template>
    <component
        :is="icon"
        :class="classes"
        :stroke-width="strokeWidth"
        aria-hidden="true"
    />
</template>
