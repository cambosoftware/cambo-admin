<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    href: {
        type: String,
        default: null
    },
    icon: {
        type: [Object, Function],
        default: null
    },
    variant: {
        type: String,
        default: 'default',
        validator: (v) => ['default', 'danger'].includes(v)
    },
    disabled: {
        type: Boolean,
        default: false
    },
    active: {
        type: Boolean,
        default: false
    }
})

defineEmits(['click'])

const variantClasses = computed(() => {
    if (props.disabled) return 'text-gray-400 dark:text-gray-500 cursor-not-allowed'
    const variants = {
        default: 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white',
        danger: 'text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/20 dark:hover:text-red-300'
    }
    return variants[props.variant]
})

const tag = computed(() => {
    if (props.disabled) return 'span'
    return props.href ? Link : 'button'
})

const tagProps = computed(() => {
    if (props.href && !props.disabled) return { href: props.href }
    if (!props.href && !props.disabled) return { type: 'button' }
    return {}
})
</script>

<template>
    <component
        :is="tag"
        v-bind="tagProps"
        :class="[
            'flex items-center gap-2 w-full px-4 py-2 text-sm transition-colors cursor-pointer',
            variantClasses,
            active ? 'bg-gray-100 dark:bg-gray-700 font-medium' : ''
        ]"
        role="menuitem"
        @click="!disabled && $emit('click', $event)"
    >
        <component v-if="icon" :is="icon" class="h-4 w-4 flex-shrink-0" />
        <slot />
    </component>
</template>
