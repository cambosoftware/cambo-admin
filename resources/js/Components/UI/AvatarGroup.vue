<script setup>
import { computed } from 'vue'
import Avatar from './Avatar.vue'

const props = defineProps({
    items: {
        type: Array,
        default: () => []
    },
    max: {
        type: Number,
        default: 5
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(v)
    }
})

const visibleItems = computed(() => props.items.slice(0, props.max))
const remainingCount = computed(() => Math.max(0, props.items.length - props.max))

const overlapClass = computed(() => {
    const overlaps = {
        xs: '-space-x-1.5',
        sm: '-space-x-2',
        md: '-space-x-2.5',
        lg: '-space-x-3',
        xl: '-space-x-4'
    }
    return overlaps[props.size]
})

const counterSizeClasses = computed(() => {
    const sizes = {
        xs: 'h-6 w-6 text-xs',
        sm: 'h-8 w-8 text-xs',
        md: 'h-10 w-10 text-sm',
        lg: 'h-12 w-12 text-base',
        xl: 'h-16 w-16 text-lg'
    }
    return sizes[props.size]
})
</script>

<template>
    <div :class="['flex items-center', overlapClass]">
        <Avatar
            v-for="(item, index) in visibleItems"
            :key="index"
            :src="item.src || item.image || item.avatar"
            :name="item.name"
            :alt="item.alt || item.name"
            :size="size"
            class="ring-2 ring-white"
        />
        <span
            v-if="remainingCount > 0"
            :class="[
                'inline-flex items-center justify-center rounded-full bg-gray-200 text-gray-600 font-semibold ring-2 ring-white',
                counterSizeClasses
            ]"
        >
            +{{ remainingCount }}
        </span>
    </div>
</template>
