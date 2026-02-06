<script setup>
import { computed } from 'vue'

const props = defineProps({
    value: {
        type: [Number, String],
        default: 0
    },
    max: {
        type: Number,
        default: 100
    },
    showLabel: {
        type: Boolean,
        default: true
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['xs', 'sm', 'md'].includes(v)
    },
    variant: {
        type: String,
        default: 'auto',
        validator: (v) => ['auto', 'primary', 'success', 'warning', 'danger', 'info'].includes(v)
    },
    width: {
        type: String,
        default: '100px'
    }
})

const percentage = computed(() => {
    const num = typeof props.value === 'string' ? parseFloat(props.value) : props.value
    if (isNaN(num)) return 0
    return Math.min(100, Math.max(0, (num / props.max) * 100))
})

const barColor = computed(() => {
    if (props.variant !== 'auto') {
        const colors = {
            primary: 'bg-primary-500',
            success: 'bg-emerald-500',
            warning: 'bg-amber-500',
            danger: 'bg-red-500',
            info: 'bg-sky-500'
        }
        return colors[props.variant]
    }

    // Auto color based on percentage
    if (percentage.value >= 80) return 'bg-emerald-500'
    if (percentage.value >= 50) return 'bg-amber-500'
    return 'bg-red-500'
})

const sizeClasses = {
    xs: 'h-1',
    sm: 'h-1.5',
    md: 'h-2'
}
</script>

<template>
    <div class="flex items-center gap-2">
        <div
            class="overflow-hidden rounded-full bg-gray-200"
            :class="sizeClasses[size]"
            :style="{ width }"
        >
            <div
                :class="['h-full rounded-full transition-all', barColor]"
                :style="{ width: percentage + '%' }"
            />
        </div>
        <span v-if="showLabel" class="text-xs text-gray-600 tabular-nums">
            {{ Math.round(percentage) }}%
        </span>
    </div>
</template>
