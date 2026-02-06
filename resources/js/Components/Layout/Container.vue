<script setup>
import { computed } from 'vue'

const props = defineProps({
    size: {
        type: String,
        default: 'default',
        validator: (value) => ['sm', 'default', 'lg', 'xl', 'full'].includes(value)
    },
    padding: {
        type: Boolean,
        default: true
    },
    centered: {
        type: Boolean,
        default: true
    }
})

const maxWidthClass = computed(() => {
    const sizes = {
        sm: 'max-w-3xl',
        default: 'max-w-5xl',
        lg: 'max-w-6xl',
        xl: 'max-w-7xl',
        full: 'max-w-full'
    }
    return sizes[props.size]
})
</script>

<template>
    <div
        :class="[
            maxWidthClass,
            centered ? 'mx-auto' : '',
            padding ? 'px-4 sm:px-6 lg:px-8' : ''
        ]"
    >
        <slot />
    </div>
</template>
