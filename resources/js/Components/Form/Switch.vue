<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false
    },
    label: {
        type: String,
        default: null
    },
    description: {
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
    activeColor: {
        type: String,
        default: 'primary',
        validator: (v) => ['primary', 'success', 'danger', 'warning', 'info'].includes(v)
    },
    labelPosition: {
        type: String,
        default: 'right',
        validator: (v) => ['left', 'right'].includes(v)
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const trackSizes = computed(() => {
    const sizes = { sm: 'h-4 w-7', md: 'h-5 w-9', lg: 'h-6 w-11' }
    return sizes[props.size]
})

const dotSizes = computed(() => {
    const sizes = { sm: 'h-3 w-3', md: 'h-4 w-4', lg: 'h-5 w-5' }
    return sizes[props.size]
})

const translateClasses = computed(() => {
    const sizes = { sm: 'translate-x-3', md: 'translate-x-4', lg: 'translate-x-5' }
    return sizes[props.size]
})

const labelSize = computed(() => {
    const sizes = { sm: 'text-xs', md: 'text-sm', lg: 'text-base' }
    return sizes[props.size]
})

const activeColorClass = computed(() => {
    const colors = {
        primary: 'bg-indigo-600',
        success: 'bg-emerald-500',
        danger: 'bg-red-500',
        warning: 'bg-amber-500',
        info: 'bg-sky-500'
    }
    return colors[props.activeColor]
})

const toggle = () => {
    if (props.disabled) return
    const val = !props.modelValue
    emit('update:modelValue', val)
    emit('change', val)
}
</script>

<template>
    <label
        :class="[
            'inline-flex items-center gap-3 select-none',
            labelPosition === 'left' ? 'flex-row-reverse' : '',
            disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
        ]"
    >
        <!-- Switch track -->
        <button
            type="button"
            :class="[
                'relative inline-flex flex-shrink-0 rounded-full border-2 border-transparent transition-colors duration-200 cursor-pointer',
                'focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2',
                trackSizes,
                modelValue ? activeColorClass : 'bg-gray-200',
                disabled ? 'cursor-not-allowed' : ''
            ]"
            role="switch"
            :aria-checked="modelValue"
            @click="toggle"
        >
            <span
                :class="[
                    'pointer-events-none inline-block rounded-full bg-white shadow ring-0 transition-transform duration-200',
                    dotSizes,
                    modelValue ? translateClasses : 'translate-x-0'
                ]"
            />
        </button>

        <!-- Label -->
        <span v-if="label || $slots.default">
            <span :class="['block font-medium text-gray-900', labelSize]">
                <slot>{{ label }}</slot>
            </span>
            <span v-if="description" class="block text-xs text-gray-500 mt-0.5">
                {{ description }}
            </span>
        </span>
    </label>
</template>
