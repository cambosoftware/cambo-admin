<script setup>
import { computed } from 'vue'

const props = defineProps({
    value: {
        type: Number,
        default: 0
    },
    max: {
        type: Number,
        default: 100
    },
    variant: {
        type: String,
        default: 'primary',
        validator: (v) => ['primary', 'success', 'warning', 'danger', 'info'].includes(v)
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['xs', 'sm', 'md', 'lg'].includes(v)
    },
    showLabel: {
        type: Boolean,
        default: false
    },
    striped: {
        type: Boolean,
        default: false
    },
    animated: {
        type: Boolean,
        default: false
    },
    indeterminate: {
        type: Boolean,
        default: false
    }
})

const percentage = computed(() => Math.min(100, Math.max(0, (props.value / props.max) * 100)))

const sizeClasses = computed(() => {
    const sizes = { xs: 'h-1', sm: 'h-1.5', md: 'h-2.5', lg: 'h-4' }
    return sizes[props.size]
})

const barColor = computed(() => {
    const colors = {
        primary: 'bg-primary-600',
        success: 'bg-emerald-500',
        warning: 'bg-amber-500',
        danger: 'bg-red-500',
        info: 'bg-sky-500'
    }
    return colors[props.variant]
})
</script>

<template>
    <div>
        <!-- Label -->
        <div v-if="showLabel" class="flex justify-between mb-1">
            <span class="text-sm font-medium text-gray-700">
                <slot name="label">Progression</slot>
            </span>
            <span class="text-sm font-medium text-gray-500">{{ Math.round(percentage) }}%</span>
        </div>

        <!-- Bar -->
        <div :class="['w-full rounded-full bg-gray-200 overflow-hidden', sizeClasses]" role="progressbar" :aria-valuenow="value" :aria-valuemin="0" :aria-valuemax="max">
            <div
                v-if="!indeterminate"
                :class="[
                    'h-full rounded-full transition-all duration-500 ease-out',
                    barColor,
                    striped ? 'bg-stripes' : ''
                ]"
                :style="{ width: percentage + '%' }"
            />
            <div
                v-else
                :class="['h-full rounded-full animate-indeterminate', barColor]"
                style="width: 40%"
            />
        </div>
    </div>
</template>

<style scoped>
.bg-stripes {
    background-image: linear-gradient(
        45deg,
        rgba(255, 255, 255, 0.15) 25%,
        transparent 25%,
        transparent 50%,
        rgba(255, 255, 255, 0.15) 50%,
        rgba(255, 255, 255, 0.15) 75%,
        transparent 75%,
        transparent
    );
    background-size: 1rem 1rem;
}

@keyframes indeterminate {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(350%); }
}

.animate-indeterminate {
    animation: indeterminate 1.5s ease-in-out infinite;
}
</style>
