<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean, null],
        default: null
    },
    options: {
        type: Array,
        default: () => []
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
    optionLabel: {
        type: String,
        default: 'label'
    },
    optionValue: {
        type: String,
        default: 'value'
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const hasError = computed(() => !!props.error)

const normalizedOptions = computed(() =>
    props.options.map(opt => {
        if (typeof opt === 'string' || typeof opt === 'number') {
            return { label: String(opt), value: opt }
        }
        return {
            label: opt[props.optionLabel] ?? opt.label ?? String(opt[props.optionValue]),
            value: opt[props.optionValue] ?? opt.value,
            icon: opt.icon ?? null,
            disabled: opt.disabled ?? false
        }
    })
)

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'px-2.5 py-1 text-xs',
        md: 'px-3 py-1.5 text-sm',
        lg: 'px-4 py-2 text-base'
    }
    return sizes[props.size]
})

const select = (opt) => {
    if (props.disabled || opt.disabled) return
    emit('update:modelValue', opt.value)
    emit('change', opt.value)
}
</script>

<template>
    <div
        :class="[
            'inline-flex rounded-lg p-0.5',
            hasError ? 'bg-red-100 ring-1 ring-red-300' : 'bg-gray-100'
        ]"
        role="radiogroup"
    >
        <button
            v-for="opt in normalizedOptions"
            :key="opt.value"
            type="button"
            :class="[
                'inline-flex items-center gap-1.5 rounded-md font-medium transition-all duration-150',
                sizeClasses,
                String(modelValue) === String(opt.value)
                    ? 'bg-white text-gray-900 shadow-sm'
                    : 'text-gray-500 hover:text-gray-700',
                opt.disabled || disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
            ]"
            role="radio"
            :aria-checked="String(modelValue) === String(opt.value)"
            @click="select(opt)"
        >
            <component v-if="opt.icon" :is="opt.icon" class="h-4 w-4" />
            {{ opt.label }}
        </button>
    </div>
</template>
