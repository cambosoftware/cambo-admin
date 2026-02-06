<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
    modelValue: {
        type: Number,
        default: 0
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
    showValue: {
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
    marks: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const hasError = computed(() => !!props.error)

const percentage = computed(() => {
    return ((props.modelValue - props.min) / (props.max - props.min)) * 100
})

const displayValue = computed(() => {
    if (props.formatValue) return props.formatValue(props.modelValue)
    return props.modelValue
})

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
        primary: 'bg-primary-500',
        success: 'bg-green-500',
        danger: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    }
    return colors[props.color]
})

const thumbColorClasses = computed(() => {
    const colors = {
        primary: 'border-primary-500 focus-visible:ring-primary-500/20',
        success: 'border-green-500 focus-visible:ring-green-500/20',
        danger: 'border-red-500 focus-visible:ring-red-500/20',
        warning: 'border-yellow-500 focus-visible:ring-yellow-500/20',
        info: 'border-blue-500 focus-visible:ring-blue-500/20'
    }
    return colors[props.color]
})

const onInput = (e) => {
    const val = Number(e.target.value)
    emit('update:modelValue', val)
    emit('change', val)
}

const markPosition = (mark) => {
    const val = typeof mark === 'object' ? mark.value : mark
    return ((val - props.min) / (props.max - props.min)) * 100
}
</script>

<template>
    <div :class="['w-full', disabled ? 'opacity-50' : '']">
        <!-- Value display -->
        <div v-if="showValue" class="mb-1 text-right">
            <span :class="[
                'inline-block font-medium tabular-nums',
                size === 'sm' ? 'text-xs' : size === 'lg' ? 'text-base' : 'text-sm',
                hasError ? 'text-red-600' : 'text-gray-700'
            ]">
                {{ displayValue }}
            </span>
        </div>

        <!-- Slider -->
        <div class="relative flex items-center">
            <!-- Track background -->
            <div :class="['relative w-full rounded-full bg-gray-200', trackHeight]">
                <!-- Filled track -->
                <div
                    :class="['absolute rounded-full', trackHeight, hasError ? 'bg-red-500' : colorClasses]"
                    :style="{ width: percentage + '%' }"
                />
            </div>

            <!-- Native range input (invisible, layered on top) -->
            <input
                type="range"
                :value="modelValue"
                :min="min"
                :max="max"
                :step="step"
                :disabled="disabled"
                class="absolute inset-0 w-full cursor-pointer opacity-0"
                :class="disabled ? 'cursor-not-allowed' : ''"
                @input="onInput"
            />

            <!-- Custom thumb visual -->
            <div
                class="pointer-events-none absolute"
                :style="{ left: percentage + '%', transform: 'translateX(-50%)' }"
            >
                <div :class="[
                    'rounded-full border-2 bg-white shadow-sm',
                    thumbSize,
                    hasError ? 'border-red-500' : thumbColorClasses
                ]" />
            </div>
        </div>

        <!-- Marks -->
        <div v-if="marks.length" class="relative mt-1" :class="trackHeight">
            <div
                v-for="(mark, i) in marks"
                :key="i"
                class="absolute -translate-x-1/2"
                :style="{ left: markPosition(mark) + '%' }"
            >
                <span class="text-xs text-gray-500">
                    {{ typeof mark === 'object' ? mark.label : mark }}
                </span>
            </div>
        </div>

        <!-- Min/Max labels -->
        <div v-if="showMinMax" class="mt-1 flex justify-between">
            <span :class="['text-gray-500', size === 'sm' ? 'text-xs' : 'text-sm']">{{ min }}</span>
            <span :class="['text-gray-500', size === 'sm' ? 'text-xs' : 'text-sm']">{{ max }}</span>
        </div>
    </div>
</template>
