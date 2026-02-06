<script setup>
defineProps({
    sticky: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <thead
        :class="[
            'bg-gray-50 dark:bg-gray-800/50',
            sticky ? 'sticky top-0 z-10' : ''
        ]"
    >
        <slot />
    </thead>
</template>
