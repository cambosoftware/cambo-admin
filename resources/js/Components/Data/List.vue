<script setup>
defineProps({
    divided: {
        type: Boolean,
        default: true
    },
    hoverable: {
        type: Boolean,
        default: false
    },
    bordered: {
        type: Boolean,
        default: false
    },
    compact: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <ul
        :class="[
            'bg-white',
            bordered ? 'border border-gray-200 rounded-lg overflow-hidden' : '',
            divided ? 'divide-y divide-gray-200' : ''
        ]"
        role="list"
    >
        <slot />
    </ul>
</template>
