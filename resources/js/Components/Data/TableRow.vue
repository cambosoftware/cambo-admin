<script setup>
defineProps({
    selected: {
        type: Boolean,
        default: false
    },
    clickable: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    }
})

defineEmits(['click'])
</script>

<template>
    <tr
        :class="[
            'transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/50',
            selected ? 'bg-indigo-50 dark:bg-indigo-900/20' : '',
            clickable && !disabled ? 'cursor-pointer' : '',
            disabled ? 'opacity-50 pointer-events-none' : ''
        ]"
        @click="clickable && !disabled ? $emit('click', $event) : null"
    >
        <slot />
    </tr>
</template>
