<script setup>
import { computed, ref } from 'vue'
import { PlusIcon, MinusIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
    modelValue: {
        type: [Number, String, null],
        default: null
    },
    min: {
        type: Number,
        default: -Infinity
    },
    max: {
        type: Number,
        default: Infinity
    },
    step: {
        type: Number,
        default: 1
    },
    placeholder: {
        type: String,
        default: null
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
    controls: {
        type: Boolean,
        default: true
    },
    controlsPosition: {
        type: String,
        default: 'sides',
        validator: (v) => ['sides', 'right'].includes(v)
    },
    precision: {
        type: Number,
        default: null
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'focus', 'blur'])

const inputRef = ref(null)

const hasError = computed(() => !!props.error)

const numericValue = computed(() => {
    if (props.modelValue === null || props.modelValue === '' || props.modelValue === undefined) return null
    return Number(props.modelValue)
})

const canDecrement = computed(() => {
    if (numericValue.value === null) return true
    return numericValue.value - props.step >= props.min
})

const canIncrement = computed(() => {
    if (numericValue.value === null) return true
    return numericValue.value + props.step <= props.max
})

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'py-1.5 text-xs',
        md: 'py-2 text-sm',
        lg: 'py-2.5 text-base'
    }
    return sizes[props.size]
})

const buttonSizes = computed(() => {
    const sizes = {
        sm: 'h-3 w-3',
        md: 'h-4 w-4',
        lg: 'h-5 w-5'
    }
    return sizes[props.size]
})

const formatValue = (val) => {
    if (val === null || val === undefined) return val
    if (props.precision !== null) {
        return Number(val.toFixed(props.precision))
    }
    return val
}

const clamp = (val) => {
    return Math.min(Math.max(val, props.min), props.max)
}

const setValue = (val) => {
    const formatted = formatValue(clamp(val))
    emit('update:modelValue', formatted)
    emit('change', formatted)
}

const increment = () => {
    if (props.disabled || !canIncrement.value) return
    const current = numericValue.value ?? (props.min !== -Infinity ? props.min - props.step : 0)
    setValue(current + props.step)
}

const decrement = () => {
    if (props.disabled || !canDecrement.value) return
    const current = numericValue.value ?? (props.max !== Infinity ? props.max + props.step : 0)
    setValue(current - props.step)
}

const onInput = (e) => {
    const raw = e.target.value
    if (raw === '' || raw === '-') {
        emit('update:modelValue', raw === '-' ? raw : null)
        return
    }
    const num = Number(raw)
    if (!isNaN(num)) {
        emit('update:modelValue', num)
    }
}

const onBlur = (e) => {
    if (numericValue.value !== null) {
        setValue(numericValue.value)
    }
    emit('blur', e)
}

const onKeydown = (e) => {
    if (e.key === 'ArrowUp') {
        e.preventDefault()
        increment()
    } else if (e.key === 'ArrowDown') {
        e.preventDefault()
        decrement()
    }
}

const focus = () => inputRef.value?.focus()

defineExpose({ focus, inputRef })
</script>

<template>
    <div :class="['inline-flex', controls && controlsPosition === 'sides' ? '' : 'relative']">
        <!-- Left decrement (sides mode) -->
        <button
            v-if="controls && controlsPosition === 'sides'"
            type="button"
            :class="[
                'inline-flex items-center justify-center rounded-l-lg border border-r-0 transition-colors',
                hasError ? 'border-red-300' : 'border-gray-300',
                disabled || !canDecrement
                    ? 'bg-gray-50 text-gray-300 cursor-not-allowed'
                    : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-gray-700 cursor-pointer',
                size === 'sm' ? 'px-2' : size === 'lg' ? 'px-3' : 'px-2.5'
            ]"
            :disabled="disabled || !canDecrement"
            tabindex="-1"
            @click="decrement"
        >
            <MinusIcon :class="buttonSizes" />
        </button>

        <!-- Input -->
        <input
            ref="inputRef"
            type="text"
            inputmode="numeric"
            :value="modelValue"
            :placeholder="placeholder"
            :disabled="disabled"
            :class="[
                'block w-full border bg-white transition-colors duration-150 text-center',
                'placeholder:text-gray-400',
                'focus:outline-none focus:ring-2 focus:ring-offset-0',
                'disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed',
                hasError
                    ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500/20'
                    : 'border-gray-300 text-gray-900 focus:border-primary-500 focus:ring-primary-500/20',
                sizeClasses,
                controls && controlsPosition === 'sides' ? 'rounded-none' : 'rounded-lg',
                controls && controlsPosition === 'right' ? 'pr-10' : 'px-3'
            ]"
            @input="onInput"
            @blur="onBlur"
            @keydown="onKeydown"
            @focus="$emit('focus', $event)"
        />

        <!-- Right increment (sides mode) -->
        <button
            v-if="controls && controlsPosition === 'sides'"
            type="button"
            :class="[
                'inline-flex items-center justify-center rounded-r-lg border border-l-0 transition-colors',
                hasError ? 'border-red-300' : 'border-gray-300',
                disabled || !canIncrement
                    ? 'bg-gray-50 text-gray-300 cursor-not-allowed'
                    : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-gray-700 cursor-pointer',
                size === 'sm' ? 'px-2' : size === 'lg' ? 'px-3' : 'px-2.5'
            ]"
            :disabled="disabled || !canIncrement"
            tabindex="-1"
            @click="increment"
        >
            <PlusIcon :class="buttonSizes" />
        </button>

        <!-- Stacked controls (right mode) -->
        <div
            v-if="controls && controlsPosition === 'right'"
            class="absolute inset-y-0 right-0 flex flex-col border-l"
            :class="hasError ? 'border-red-300' : 'border-gray-300'"
        >
            <button
                type="button"
                :class="[
                    'flex-1 flex items-center justify-center px-1.5 rounded-tr-lg border-b transition-colors',
                    hasError ? 'border-red-300' : 'border-gray-300',
                    disabled || !canIncrement
                        ? 'text-gray-300 cursor-not-allowed'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 cursor-pointer'
                ]"
                :disabled="disabled || !canIncrement"
                tabindex="-1"
                @click="increment"
            >
                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" />
                </svg>
            </button>
            <button
                type="button"
                :class="[
                    'flex-1 flex items-center justify-center px-1.5 rounded-br-lg transition-colors',
                    disabled || !canDecrement
                        ? 'text-gray-300 cursor-not-allowed'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 cursor-pointer'
                ]"
                :disabled="disabled || !canDecrement"
                tabindex="-1"
                @click="decrement"
            >
                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</template>
