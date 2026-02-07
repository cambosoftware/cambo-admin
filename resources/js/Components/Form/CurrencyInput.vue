<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: [Number, null],
        default: null
    },
    currency: {
        type: String,
        default: 'EUR'
    },
    currencySymbol: {
        type: String,
        default: null
    },
    locale: {
        type: String,
        default: 'fr-FR'
    },
    placeholder: {
        type: String,
        default: '0,00'
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
    min: {
        type: Number,
        default: null
    },
    max: {
        type: Number,
        default: null
    },
    precision: {
        type: Number,
        default: 2
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'focus', 'blur'])

const inputRef = ref(null)
const displayValue = ref('')
const focused = ref(false)

const hasError = computed(() => !!props.error)

const symbol = computed(() => {
    if (props.currencySymbol) return props.currencySymbol
    const symbols = { EUR: '€', USD: '$', GBP: '£', JPY: '¥', CHF: 'CHF', CAD: 'CA$', KHR: '៛' }
    return symbols[props.currency] || props.currency
})

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'py-1.5 text-xs',
        md: 'py-2 text-sm',
        lg: 'py-2.5 text-base'
    }
    return sizes[props.size]
})

function formatNumber(value) {
    if (value === null || value === undefined || isNaN(value)) return ''
    return new Intl.NumberFormat(props.locale, {
        minimumFractionDigits: props.precision,
        maximumFractionDigits: props.precision
    }).format(value)
}

function parseNumber(str) {
    if (!str) return null
    // Remove non-numeric characters except comma, dot, minus
    const cleaned = str.replace(/[^\d,.\-]/g, '')
    // Handle French format (comma as decimal)
    const normalized = cleaned.replace(/\./g, '').replace(',', '.')
    const num = parseFloat(normalized)
    return isNaN(num) ? null : num
}

// Init display value
watch(() => props.modelValue, (val) => {
    if (!focused.value) {
        displayValue.value = formatNumber(val)
    }
}, { immediate: true })

function onFocus(e) {
    focused.value = true
    // Show raw number on focus for easier editing
    if (props.modelValue !== null) {
        displayValue.value = props.modelValue.toFixed(props.precision).replace('.', ',')
    }
    emit('focus', e)
}

function onBlur(e) {
    focused.value = false
    let val = parseNumber(displayValue.value)

    if (val !== null) {
        if (props.min !== null) val = Math.max(val, props.min)
        if (props.max !== null) val = Math.min(val, props.max)
        val = Number(val.toFixed(props.precision))
    }

    emit('update:modelValue', val)
    emit('change', val)
    displayValue.value = formatNumber(val)
    emit('blur', e)
}

function onInput(e) {
    displayValue.value = e.target.value
}

const focus = () => inputRef.value?.focus()
defineExpose({ focus, inputRef })
</script>

<template>
    <div class="flex">
        <!-- Currency symbol -->
        <span
            :class="[
                'inline-flex items-center rounded-l-lg border border-r-0 bg-gray-50 text-gray-500 font-medium',
                hasError ? 'border-red-300' : 'border-gray-300',
                size === 'sm' ? 'px-2.5 text-xs' : size === 'lg' ? 'px-4 text-base' : 'px-3 text-sm'
            ]"
        >
            {{ symbol }}
        </span>

        <!-- Input -->
        <input
            ref="inputRef"
            type="text"
            inputmode="decimal"
            :value="displayValue"
            :placeholder="placeholder"
            :disabled="disabled"
            :class="[
                'block w-full rounded-r-lg border bg-white transition-colors duration-150 text-right',
                'placeholder:text-gray-400',
                'focus:outline-none focus:ring-2 focus:ring-offset-0',
                'disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed',
                hasError
                    ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500/20'
                    : 'border-gray-300 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500/20',
                sizeClasses,
                size === 'sm' ? 'px-2.5' : size === 'lg' ? 'px-4' : 'px-3'
            ]"
            @input="onInput"
            @focus="onFocus"
            @blur="onBlur"
        />
    </div>
</template>
