<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    icon: {
        type: [Object, Function],
        required: true
    },
    variant: {
        type: String,
        default: 'ghost',
        validator: (v) => ['primary', 'secondary', 'success', 'danger', 'warning', 'ghost'].includes(v)
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['xs', 'sm', 'md', 'lg'].includes(v)
    },
    pill: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    href: {
        type: String,
        default: null
    },
    label: {
        type: String,
        default: null
    },
    type: {
        type: String,
        default: 'button'
    }
})

defineEmits(['click'])

const sizeClasses = computed(() => {
    const sizes = {
        xs: 'p-1',
        sm: 'p-1.5',
        md: 'p-2',
        lg: 'p-3'
    }
    return sizes[props.size]
})

const iconSize = computed(() => {
    const sizes = {
        xs: 'h-3.5 w-3.5',
        sm: 'h-4 w-4',
        md: 'h-5 w-5',
        lg: 'h-6 w-6'
    }
    return sizes[props.size]
})

const variantClasses = computed(() => {
    const variants = {
        primary: 'text-primary-600 hover:bg-primary-50 active:ring-primary-500 focus-visible:ring-primary-500',
        secondary: 'text-gray-500 hover:bg-gray-100 active:ring-gray-500 focus-visible:ring-gray-500',
        success: 'text-emerald-600 hover:bg-emerald-50 active:ring-emerald-500 focus-visible:ring-emerald-500',
        danger: 'text-red-600 hover:bg-red-50 active:ring-red-500 focus-visible:ring-red-500',
        warning: 'text-amber-600 hover:bg-amber-50 active:ring-amber-500 focus-visible:ring-amber-500',
        ghost: 'text-gray-400 hover:text-gray-600 hover:bg-gray-100 active:ring-gray-500 focus-visible:ring-gray-500'
    }
    return variants[props.variant]
})

const baseClasses = computed(() => [
    'inline-flex items-center justify-center transition-colors duration-150 cursor-pointer',
    'focus:outline-none active:ring-2 active:ring-offset-2 focus-visible:ring-2 focus-visible:ring-offset-2',
    'disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none',
    props.pill ? 'rounded-full' : 'rounded-lg',
    sizeClasses.value,
    variantClasses.value,
])

const isDisabled = computed(() => props.disabled || props.loading)
const componentTag = computed(() => props.href ? Link : 'button')

const componentProps = computed(() => {
    if (props.href) {
        return { href: props.href }
    }
    return { type: props.type, disabled: isDisabled.value }
})
</script>

<template>
    <component
        :is="componentTag"
        v-bind="componentProps"
        :class="baseClasses"
        :aria-label="label"
        :title="label"
        @click="$emit('click', $event)"
    >
        <!-- Loading spinner -->
        <svg
            v-if="loading"
            class="animate-spin"
            :class="iconSize"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
        </svg>

        <!-- Icon -->
        <component
            v-else
            :is="icon"
            :class="iconSize"
        />
    </component>
</template>
