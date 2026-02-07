<script setup>
import { ArrowTopRightOnSquareIcon } from '@heroicons/vue/20/solid'

defineProps({
    href: {
        type: String,
        required: true
    },
    showIcon: {
        type: Boolean,
        default: true
    },
    iconPosition: {
        type: String,
        default: 'right',
        validator: (v) => ['left', 'right'].includes(v)
    },
    underline: {
        type: Boolean,
        default: true
    },
    variant: {
        type: String,
        default: 'default',
        validator: (v) => ['default', 'muted'].includes(v)
    }
})
</script>

<template>
    <a
        :href="href"
        target="_blank"
        rel="noopener noreferrer"
        :class="[
            'inline-flex items-center gap-1',
            variant === 'default' ? 'text-indigo-600 hover:text-indigo-800' : 'text-gray-600 hover:text-gray-900',
            underline ? 'hover:underline' : ''
        ]"
    >
        <ArrowTopRightOnSquareIcon
            v-if="showIcon && iconPosition === 'left'"
            class="h-4 w-4 flex-shrink-0"
        />
        <slot />
        <ArrowTopRightOnSquareIcon
            v-if="showIcon && iconPosition === 'right'"
            class="h-4 w-4 flex-shrink-0"
        />
    </a>
</template>
