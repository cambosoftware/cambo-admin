<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean, null],
        default: null
    },
    value: {
        type: [String, Number, Boolean],
        required: true
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
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const isChecked = computed(() => String(props.modelValue) === String(props.value))
const hasError = computed(() => !!props.error)

const radioSize = computed(() => {
    const sizes = { sm: 'h-3.5 w-3.5', md: 'h-4 w-4', lg: 'h-5 w-5' }
    return sizes[props.size]
})

const dotSize = computed(() => {
    const sizes = { sm: 'h-1.5 w-1.5', md: 'h-2 w-2', lg: 'h-2.5 w-2.5' }
    return sizes[props.size]
})

const labelSize = computed(() => {
    const sizes = { sm: 'text-xs', md: 'text-sm', lg: 'text-base' }
    return sizes[props.size]
})

const select = () => {
    if (props.disabled) return
    emit('update:modelValue', props.value)
    emit('change', props.value)
}
</script>

<template>
    <label
        :class="[
            'inline-flex items-center gap-2 select-none',
            disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
        ]"
    >
        <!-- Radio circle -->
        <span
            :class="[
                'flex-shrink-0 rounded-full border flex items-center justify-center transition-colors',
                radioSize,
                isChecked
                    ? 'border-primary-600 bg-primary-600'
                    : hasError
                        ? 'border-red-300 bg-white'
                        : 'border-gray-300 bg-white',
                !disabled ? 'cursor-pointer' : ''
            ]"
            @click="select"
            role="radio"
            :aria-checked="isChecked"
            tabindex="0"
            @keydown.space.prevent="select"
        >
            <!-- Inner dot - always rendered, visibility controlled by opacity -->
            <span
                :class="[
                    'rounded-full bg-white transition-opacity duration-150',
                    dotSize,
                    isChecked ? 'opacity-100' : 'opacity-0'
                ]"
            />
        </span>

        <!-- Label -->
        <span v-if="label || description || $slots.default" class="leading-none" @click="select">
            <span :class="['font-medium text-gray-900', labelSize]">
                <slot>{{ label }}</slot>
            </span>
            <span v-if="description" class="block text-xs text-gray-500 mt-1">
                {{ description }}
            </span>
        </span>
    </label>
</template>
