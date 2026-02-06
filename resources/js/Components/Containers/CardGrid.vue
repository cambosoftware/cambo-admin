<script setup>
import { computed } from 'vue'

const props = defineProps({
    cols: {
        type: [Number, Object],
        default: 3
    },
    gap: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    }
})

const gapClasses = computed(() => {
    const gaps = { sm: 'gap-3', md: 'gap-5', lg: 'gap-8' }
    return gaps[props.gap]
})

const colClasses = computed(() => {
    if (typeof props.cols === 'number') {
        const colMap = {
            1: 'grid-cols-1',
            2: 'grid-cols-1 sm:grid-cols-2',
            3: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
            4: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
            5: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5',
            6: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6'
        }
        return colMap[props.cols] || colMap[3]
    }
    return ''
})
</script>

<template>
    <div :class="['grid', colClasses, gapClasses]">
        <slot />
    </div>
</template>
