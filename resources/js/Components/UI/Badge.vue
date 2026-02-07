<script setup>
import { computed } from 'vue'

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: (v) => ['primary', 'secondary', 'success', 'danger', 'warning', 'info'].includes(v)
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    },
    pill: {
        type: Boolean,
        default: false
    },
    dot: {
        type: Boolean,
        default: false
    },
    outline: {
        type: Boolean,
        default: false
    },
    removable: {
        type: Boolean,
        default: false
    }
})

defineEmits(['remove'])

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'px-1.5 py-0.5 text-xs',
        md: 'px-2 py-0.5 text-xs',
        lg: 'px-2.5 py-1 text-sm'
    }
    return sizes[props.size]
})

const variantClasses = computed(() => {
    if (props.outline) {
        const outlineVariants = {
            primary: 'border border-indigo-300 text-indigo-700 bg-transparent',
            secondary: 'border border-gray-300 text-gray-700 bg-transparent',
            success: 'border border-emerald-300 text-emerald-700 bg-transparent',
            danger: 'border border-red-300 text-red-700 bg-transparent',
            warning: 'border border-amber-300 text-amber-700 bg-transparent',
            info: 'border border-sky-300 text-sky-700 bg-transparent',
        }
        return outlineVariants[props.variant]
    }

    const solidVariants = {
        primary: 'bg-indigo-100 text-indigo-700',
        secondary: 'bg-gray-100 text-gray-700',
        success: 'bg-emerald-100 text-emerald-700',
        danger: 'bg-red-100 text-red-700',
        warning: 'bg-amber-100 text-amber-700',
        info: 'bg-sky-100 text-sky-700',
    }
    return solidVariants[props.variant]
})

const dotColor = computed(() => {
    const colors = {
        primary: 'bg-indigo-500',
        secondary: 'bg-gray-500',
        success: 'bg-emerald-500',
        danger: 'bg-red-500',
        warning: 'bg-amber-500',
        info: 'bg-sky-500',
    }
    return colors[props.variant]
})

const removeHoverColor = computed(() => {
    const colors = {
        primary: 'hover:bg-indigo-200',
        secondary: 'hover:bg-gray-200',
        success: 'hover:bg-emerald-200',
        danger: 'hover:bg-red-200',
        warning: 'hover:bg-amber-200',
        info: 'hover:bg-sky-200',
    }
    return colors[props.variant]
})
</script>

<template>
    <span
        :class="[
            'inline-flex items-center gap-1 font-medium',
            pill ? 'rounded-full' : 'rounded-md',
            sizeClasses,
            variantClasses
        ]"
    >
        <!-- Dot indicator -->
        <span
            v-if="dot"
            :class="['inline-block h-1.5 w-1.5 rounded-full', dotColor]"
        />

        <!-- Content -->
        <slot />

        <!-- Remove button -->
        <button
            v-if="removable"
            type="button"
            :class="[
                'ml-0.5 -mr-0.5 inline-flex items-center justify-center h-4 w-4 rounded-full transition-colors',
                removeHoverColor
            ]"
            @click.stop="$emit('remove')"
        >
            <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
            </svg>
        </button>
    </span>
</template>
