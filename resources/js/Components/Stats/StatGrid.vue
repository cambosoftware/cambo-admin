<script setup>
defineProps({
    columns: {
        type: Number,
        default: 4,
        validator: (v) => [1, 2, 3, 4, 5, 6].includes(v)
    },
    gap: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    }
})

const columnClasses = {
    1: 'grid-cols-1',
    2: 'grid-cols-1 sm:grid-cols-2',
    3: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    4: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
    5: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-5',
    6: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6'
}

const gapClasses = {
    sm: 'gap-4',
    md: 'gap-6',
    lg: 'gap-8'
}
</script>

<template>
    <div :class="['grid', columnClasses[columns], gapClasses[gap]]">
        <slot />
    </div>
</template>
