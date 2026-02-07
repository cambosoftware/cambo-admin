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
    indeterminate: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const hasError = computed(() => !!props.error)

const boxSize = computed(() => {
    const sizes = { sm: 'h-3.5 w-3.5', md: 'h-4 w-4', lg: 'h-5 w-5' }
    return sizes[props.size]
})

const labelSize = computed(() => {
    const sizes = { sm: 'text-xs', md: 'text-sm', lg: 'text-base' }
    return sizes[props.size]
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
            'inline-flex items-center gap-2 select-none',
            disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
        ]"
    >
        <!-- Checkbox -->
        <span
            :class="[
                'relative flex-shrink-0 rounded border flex items-center justify-center transition-colors',
                boxSize,
                modelValue || indeterminate
                    ? 'bg-indigo-600 border-indigo-600'
                    : hasError
                        ? 'border-red-300 bg-white'
                        : 'border-gray-300 bg-white',
                !disabled ? 'cursor-pointer' : ''
            ]"
            @click="toggle"
            role="checkbox"
            :aria-checked="indeterminate ? 'mixed' : modelValue"
            tabindex="0"
            @keydown.space.prevent="toggle"
        >
            <!-- Check icon - always rendered, visibility controlled by opacity -->
            <svg
                :class="[
                    'h-3 w-3 text-white transition-opacity duration-150',
                    modelValue && !indeterminate ? 'opacity-100' : 'opacity-0'
                ]"
                viewBox="0 0 20 20"
                fill="currentColor"
            >
                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
            </svg>
            <!-- Indeterminate icon - positioned absolutely to overlay -->
            <svg
                v-if="indeterminate"
                class="absolute h-3 w-3 text-white"
                viewBox="0 0 20 20"
                fill="currentColor"
            >
                <path fill-rule="evenodd" d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z" clip-rule="evenodd" />
            </svg>
        </span>

        <!-- Label -->
        <span v-if="label || description || $slots.default" class="leading-none" @click="toggle">
            <span :class="['font-medium text-gray-900', labelSize]">
                <slot>{{ label }}</slot>
            </span>
            <span v-if="description" class="block text-xs text-gray-500 mt-1">
                {{ description }}
            </span>
        </span>
    </label>
</template>
