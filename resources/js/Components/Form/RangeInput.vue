<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [0, 100]
    },
    min: {
        type: Number,
        default: 0
    },
    max: {
        type: Number,
        default: 100
    },
    step: {
        type: Number,
        default: 1
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    },
    disabled: {
        type: Boolean,
        default: false
    },
    error: {
        type: [String, Boolean],
        default: null
    },
    showValues: {
        type: Boolean,
        default: false
    },
    showMinMax: {
        type: Boolean,
        default: false
    },
    formatValue: {
        type: Function,
        default: null
    },
    color: {
        type: String,
        default: 'primary',
        validator: (v) => ['primary', 'success', 'danger', 'warning', 'info'].includes(v)
    },
    minGap: {
        type: Number,
        default: 0
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const trackRef = ref(null)
const dragging = ref(null)

const hasError = computed(() => !!props.error)

const lowValue = computed(() => props.modelValue[0] ?? props.min)
const highValue = computed(() => props.modelValue[1] ?? props.max)

const lowPercent = computed(() => ((lowValue.value - props.min) / (props.max - props.min)) * 100)
const highPercent = computed(() => ((highValue.value - props.min) / (props.max - props.min)) * 100)

const format = (val) => {
    if (props.formatValue) return props.formatValue(val)
    return val
}

const trackHeight = computed(() => {
    const sizes = { sm: 'h-1', md: 'h-1.5', lg: 'h-2' }
    return sizes[props.size]
})

const thumbSize = computed(() => {
    const sizes = { sm: 'h-3.5 w-3.5', md: 'h-4 w-4', lg: 'h-5 w-5' }
    return sizes[props.size]
})

const colorClasses = computed(() => {
    const colors = {
        primary: 'bg-indigo-500',
        success: 'bg-green-500',
        danger: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    }
    return colors[props.color]
})

const thumbColorClasses = computed(() => {
    const colors = {
        primary: 'border-indigo-500',
        success: 'border-green-500',
        danger: 'border-red-500',
        warning: 'border-yellow-500',
        info: 'border-blue-500'
    }
    return colors[props.color]
})

const clampToStep = (val) => {
    const steps = Math.round((val - props.min) / props.step)
    return Math.min(Math.max(props.min + steps * props.step, props.min), props.max)
}

const updateValue = (index, rawVal) => {
    const val = clampToStep(rawVal)
    const newValues = [...props.modelValue]
    newValues[index] = val

    // Ensure min gap
    if (index === 0 && val > highValue.value - props.minGap) {
        newValues[0] = highValue.value - props.minGap
    } else if (index === 1 && val < lowValue.value + props.minGap) {
        newValues[1] = lowValue.value + props.minGap
    }

    // Prevent crossing
    if (newValues[0] > newValues[1]) return

    emit('update:modelValue', newValues)
    emit('change', newValues)
}

const onPointerDown = (index, e) => {
    if (props.disabled) return
    dragging.value = index
    document.addEventListener('pointermove', onPointerMove)
    document.addEventListener('pointerup', onPointerUp)
}

const onPointerMove = (e) => {
    if (dragging.value === null || !trackRef.value) return
    const rect = trackRef.value.getBoundingClientRect()
    const percent = Math.min(Math.max((e.clientX - rect.left) / rect.width, 0), 1)
    const val = props.min + percent * (props.max - props.min)
    updateValue(dragging.value, val)
}

const onPointerUp = () => {
    dragging.value = null
    document.removeEventListener('pointermove', onPointerMove)
    document.removeEventListener('pointerup', onPointerUp)
}

const onTrackClick = (e) => {
    if (props.disabled || !trackRef.value) return
    const rect = trackRef.value.getBoundingClientRect()
    const percent = (e.clientX - rect.left) / rect.width
    const val = clampToStep(props.min + percent * (props.max - props.min))

    // Move whichever thumb is closer
    const distLow = Math.abs(val - lowValue.value)
    const distHigh = Math.abs(val - highValue.value)
    updateValue(distLow <= distHigh ? 0 : 1, val)
}

const onKeydown = (index, e) => {
    const current = props.modelValue[index]
    if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
        e.preventDefault()
        updateValue(index, current + props.step)
    } else if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
        e.preventDefault()
        updateValue(index, current - props.step)
    }
}
</script>

<template>
    <div :class="['w-full', disabled ? 'opacity-50' : '']">
        <!-- Values display -->
        <div v-if="showValues" class="mb-1 flex justify-between">
            <span :class="['font-medium tabular-nums', size === 'sm' ? 'text-xs' : 'text-sm', hasError ? 'text-red-600' : 'text-gray-700']">
                {{ format(lowValue) }}
            </span>
            <span :class="['font-medium tabular-nums', size === 'sm' ? 'text-xs' : 'text-sm', hasError ? 'text-red-600' : 'text-gray-700']">
                {{ format(highValue) }}
            </span>
        </div>

        <!-- Track -->
        <div
            ref="trackRef"
            :class="['relative flex items-center cursor-pointer', disabled ? 'cursor-not-allowed' : '']"
            style="padding: 8px 0;"
            @click="onTrackClick"
        >
            <!-- Track background -->
            <div :class="['relative w-full rounded-full bg-gray-200', trackHeight]">
                <!-- Filled track -->
                <div
                    :class="['absolute rounded-full', trackHeight, hasError ? 'bg-red-500' : colorClasses]"
                    :style="{ left: lowPercent + '%', width: (highPercent - lowPercent) + '%' }"
                />
            </div>

            <!-- Low thumb -->
            <div
                :class="[
                    'absolute rounded-full border-2 bg-white shadow-sm cursor-grab focus:outline-none focus:ring-2 focus:ring-offset-0',
                    thumbSize,
                    hasError ? 'border-red-500 focus:ring-red-500/20' : thumbColorClasses + ' focus:ring-indigo-500/20',
                    dragging === 0 ? 'cursor-grabbing' : ''
                ]"
                :style="{ left: lowPercent + '%', transform: 'translateX(-50%)' }"
                tabindex="0"
                role="slider"
                :aria-valuemin="min"
                :aria-valuemax="highValue"
                :aria-valuenow="lowValue"
                @pointerdown.stop="onPointerDown(0, $event)"
                @keydown="onKeydown(0, $event)"
            />

            <!-- High thumb -->
            <div
                :class="[
                    'absolute rounded-full border-2 bg-white shadow-sm cursor-grab focus:outline-none focus:ring-2 focus:ring-offset-0',
                    thumbSize,
                    hasError ? 'border-red-500 focus:ring-red-500/20' : thumbColorClasses + ' focus:ring-indigo-500/20',
                    dragging === 1 ? 'cursor-grabbing' : ''
                ]"
                :style="{ left: highPercent + '%', transform: 'translateX(-50%)' }"
                tabindex="0"
                role="slider"
                :aria-valuemin="lowValue"
                :aria-valuemax="max"
                :aria-valuenow="highValue"
                @pointerdown.stop="onPointerDown(1, $event)"
                @keydown="onKeydown(1, $event)"
            />
        </div>

        <!-- Min/Max labels -->
        <div v-if="showMinMax" class="flex justify-between">
            <span :class="['text-gray-500', size === 'sm' ? 'text-xs' : 'text-sm']">{{ min }}</span>
            <span :class="['text-gray-500', size === 'sm' ? 'text-xs' : 'text-sm']">{{ max }}</span>
        </div>
    </div>
</template>
