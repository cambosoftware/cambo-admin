<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, Number, null],
        default: null
    },
    options: {
        type: Array,
        default: () => []
    },
    disabled: {
        type: Boolean,
        default: false
    },
    error: {
        type: [String, Boolean],
        default: null
    },
    cols: {
        type: Number,
        default: 3
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
            description: opt.description ?? null,
            icon: opt.icon ?? null,
            disabled: opt.disabled ?? false
        }
    })
)

const colClasses = computed(() => {
    const map = {
        1: 'grid-cols-1',
        2: 'grid-cols-1 sm:grid-cols-2',
        3: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
        4: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4'
    }
    return map[props.cols] || map[3]
})

const isSelected = (value) => String(props.modelValue) === String(value)

const select = (opt) => {
    if (props.disabled || opt.disabled) return
    emit('update:modelValue', opt.value)
    emit('change', opt.value)
}
</script>

<template>
    <div :class="['grid gap-3', colClasses]" role="radiogroup">
        <div
            v-for="opt in normalizedOptions"
            :key="opt.value"
            :class="[
                'relative rounded-lg border-2 p-4 transition-all',
                opt.disabled || disabled
                    ? 'opacity-50 cursor-not-allowed bg-gray-50'
                    : 'cursor-pointer hover:shadow-md',
                isSelected(opt.value)
                    ? 'border-indigo-500 bg-indigo-50 ring-1 ring-indigo-500'
                    : hasError
                        ? 'border-red-300'
                        : 'border-gray-200'
            ]"
            role="radio"
            :aria-checked="isSelected(opt.value)"
            tabindex="0"
            @click="select(opt)"
            @keydown.space.prevent="select(opt)"
        >
            <!-- Radio indicator -->
            <span :class="[
                'absolute top-3 right-3 h-5 w-5 rounded-full border-2 flex items-center justify-center',
                isSelected(opt.value) ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300'
            ]">
                <span v-if="isSelected(opt.value)" class="h-2 w-2 rounded-full bg-white" />
            </span>

            <!-- Icon -->
            <component
                v-if="opt.icon"
                :is="opt.icon"
                :class="[
                    'h-8 w-8 mb-2',
                    isSelected(opt.value) ? 'text-indigo-600' : 'text-gray-400'
                ]"
            />

            <!-- Content -->
            <div class="pr-6">
                <p :class="[
                    'text-sm font-semibold',
                    isSelected(opt.value) ? 'text-indigo-900' : 'text-gray-900'
                ]">
                    <slot name="label" :option="opt">{{ opt.label }}</slot>
                </p>
                <p v-if="opt.description" class="mt-1 text-xs text-gray-500">
                    {{ opt.description }}
                </p>
            </div>
        </div>
    </div>
</template>
