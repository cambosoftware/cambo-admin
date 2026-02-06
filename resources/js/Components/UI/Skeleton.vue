<script setup>
import { computed } from 'vue'

const props = defineProps({
    variant: {
        type: String,
        default: 'text',
        validator: (v) => ['text', 'circular', 'rectangular', 'rounded'].includes(v)
    },
    width: {
        type: String,
        default: null
    },
    height: {
        type: String,
        default: null
    },
    lines: {
        type: Number,
        default: 1
    }
})

const variantClasses = computed(() => {
    const variants = {
        text: 'rounded h-4',
        circular: 'rounded-full',
        rectangular: 'rounded-none',
        rounded: 'rounded-lg'
    }
    return variants[props.variant]
})

const style = computed(() => {
    const s = {}
    if (props.width) s.width = props.width
    if (props.height) s.height = props.height
    if (props.variant === 'circular' && !props.height) {
        s.height = props.width || '2.5rem'
        s.width = props.width || '2.5rem'
    }
    return s
})
</script>

<template>
    <div v-if="lines > 1" class="space-y-2">
        <div
            v-for="i in lines"
            :key="i"
            :class="[
                'bg-gray-200 animate-pulse',
                variantClasses
            ]"
            :style="{
                ...style,
                width: i === lines ? '75%' : (style.width || '100%')
            }"
        />
    </div>
    <div
        v-else
        :class="[
            'bg-gray-200 animate-pulse',
            variantClasses
        ]"
        :style="style"
    />
</template>
