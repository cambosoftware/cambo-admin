<script setup>
defineProps({
    striped: {
        type: Boolean,
        default: false
    },
    hoverable: {
        type: Boolean,
        default: true
    }
})
</script>

<template>
    <tbody
        :class="[
            'divide-y divide-gray-200 bg-white',
            striped ? '[&>tr:nth-child(even)]:bg-gray-50' : '',
            hoverable ? '[&>tr]:hover:bg-gray-50' : ''
        ]"
    >
        <slot />
    </tbody>
</template>
