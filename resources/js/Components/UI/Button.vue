<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: (v) => ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'ghost', 'link'].includes(v)
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['xs', 'sm', 'md', 'lg'].includes(v)
    },
    outline: {
        type: Boolean,
        default: false
    },
    block: {
        type: Boolean,
        default: false
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
    icon: {
        type: [Object, Function],
        default: null
    },
    iconRight: {
        type: [Object, Function],
        default: null
    },
    href: {
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
        xs: 'px-2.5 py-1 text-xs gap-1',
        sm: 'px-3 py-1.5 text-sm gap-1.5',
        md: 'px-4 py-2 text-sm gap-2',
        lg: 'px-6 py-3 text-base gap-2'
    }
    return sizes[props.size]
})

const iconSize = computed(() => {
    const sizes = {
        xs: 'h-3.5 w-3.5',
        sm: 'h-4 w-4',
        md: 'h-4 w-4',
        lg: 'h-5 w-5'
    }
    return sizes[props.size]
})

const variantClasses = computed(() => {
    if (props.outline) {
        const outlineVariants = {
            primary: 'border border-indigo-500 text-indigo-600 hover:bg-indigo-50 active:ring-indigo-500 focus-visible:ring-indigo-500 dark:text-indigo-400 dark:hover:bg-indigo-900/20',
            secondary: 'border border-gray-300 text-gray-700 hover:bg-gray-50 active:ring-gray-500 focus-visible:ring-gray-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700',
            success: 'border border-emerald-500 text-emerald-600 hover:bg-emerald-50 active:ring-emerald-500 focus-visible:ring-emerald-500 dark:text-emerald-400 dark:hover:bg-emerald-900/20',
            danger: 'border border-red-500 text-red-600 hover:bg-red-50 active:ring-red-500 focus-visible:ring-red-500 dark:text-red-400 dark:hover:bg-red-900/20',
            warning: 'border border-amber-500 text-amber-600 hover:bg-amber-50 active:ring-amber-500 focus-visible:ring-amber-500 dark:text-amber-400 dark:hover:bg-amber-900/20',
            info: 'border border-sky-500 text-sky-600 hover:bg-sky-50 active:ring-sky-500 focus-visible:ring-sky-500 dark:text-sky-400 dark:hover:bg-sky-900/20',
        }
        return outlineVariants[props.variant] || outlineVariants.primary
    }

    const solidVariants = {
        primary: 'bg-indigo-600 text-white hover:bg-indigo-700 active:ring-indigo-500 focus-visible:ring-indigo-500 shadow-sm dark:bg-indigo-500 dark:hover:bg-indigo-600',
        secondary: 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 active:ring-indigo-500 focus-visible:ring-indigo-500 shadow-sm dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600',
        success: 'bg-emerald-600 text-white hover:bg-emerald-700 active:ring-emerald-500 focus-visible:ring-emerald-500 shadow-sm dark:bg-emerald-500 dark:hover:bg-emerald-600',
        danger: 'bg-red-600 text-white hover:bg-red-700 active:ring-red-500 focus-visible:ring-red-500 shadow-sm dark:bg-red-500 dark:hover:bg-red-600',
        warning: 'bg-amber-500 text-white hover:bg-amber-600 active:ring-amber-500 focus-visible:ring-amber-500 shadow-sm dark:bg-amber-400 dark:hover:bg-amber-500',
        info: 'bg-sky-500 text-white hover:bg-sky-600 active:ring-sky-500 focus-visible:ring-sky-500 shadow-sm dark:bg-sky-400 dark:hover:bg-sky-500',
        ghost: 'text-gray-700 hover:bg-gray-100 active:ring-gray-500 focus-visible:ring-gray-500 dark:text-gray-300 dark:hover:bg-gray-700',
        link: 'text-indigo-600 hover:text-indigo-700 hover:underline active:ring-indigo-500 focus-visible:ring-indigo-500 !px-0 !py-0 dark:text-indigo-400 dark:hover:text-indigo-300'
    }
    return solidVariants[props.variant]
})

const baseClasses = computed(() => [
    'inline-flex items-center justify-center font-semibold transition-colors duration-150 cursor-pointer',
    'focus:outline-none active:ring-2 active:ring-offset-2 focus-visible:ring-2 focus-visible:ring-offset-2',
    'dark:active:ring-offset-gray-900 dark:focus-visible:ring-offset-gray-900',
    'disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none',
    props.pill ? 'rounded-full' : 'rounded-lg',
    props.block ? 'w-full' : '',
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

        <!-- Left icon -->
        <component
            v-if="icon && !loading"
            :is="icon"
            :class="iconSize"
        />

        <!-- Content -->
        <span v-if="$slots.default">
            <slot />
        </span>

        <!-- Right icon -->
        <component
            v-if="iconRight"
            :is="iconRight"
            :class="iconSize"
        />
    </component>
</template>
