<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: null
    },
    rows: {
        type: Number,
        default: 3
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
    readonly: {
        type: Boolean,
        default: false
    },
    error: {
        type: [String, Boolean],
        default: null
    },
    resize: {
        type: String,
        default: 'vertical',
        validator: (v) => ['none', 'vertical', 'horizontal', 'both'].includes(v)
    },
    maxlength: {
        type: Number,
        default: null
    },
    showCount: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue', 'focus', 'blur'])

const textareaRef = ref(null)

const hasError = computed(() => !!props.error)

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'px-2.5 py-1.5 text-xs',
        md: 'px-3 py-2 text-sm',
        lg: 'px-4 py-2.5 text-base'
    }
    return sizes[props.size]
})

const resizeClass = computed(() => {
    const map = { none: 'resize-none', vertical: 'resize-y', horizontal: 'resize-x', both: 'resize' }
    return map[props.resize]
})

const textareaClasses = computed(() => [
    'block w-full rounded-lg border bg-white transition-colors duration-150',
    'placeholder:text-gray-400',
    'focus:outline-none focus:ring-2 focus:ring-offset-0',
    'disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed',
    hasError.value
        ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500/20'
        : 'border-gray-300 text-gray-900 focus:border-primary-500 focus:ring-primary-500/20',
    sizeClasses.value,
    resizeClass.value
])

const charCount = computed(() => (props.modelValue ?? '').length)

const focus = () => textareaRef.value?.focus()

defineExpose({ focus, textareaRef })
</script>

<template>
    <div>
        <textarea
            ref="textareaRef"
            :value="modelValue"
            :placeholder="placeholder"
            :rows="rows"
            :disabled="disabled"
            :readonly="readonly"
            :maxlength="maxlength"
            :class="textareaClasses"
            @input="$emit('update:modelValue', $event.target.value)"
            @focus="$emit('focus', $event)"
            @blur="$emit('blur', $event)"
        />
        <div v-if="showCount" class="mt-1 text-xs text-gray-400 text-right">
            {{ charCount }}<span v-if="maxlength"> / {{ maxlength }}</span>
        </div>
    </div>
</template>
