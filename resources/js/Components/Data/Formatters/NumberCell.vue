<script setup>
import { computed } from 'vue'

const props = defineProps({
    value: {
        type: [Number, String],
        default: null
    },
    locale: {
        type: String,
        default: 'fr-FR'
    },
    emptyText: {
        type: String,
        default: '-'
    },
    decimals: {
        type: Number,
        default: null
    },
    compact: {
        type: Boolean,
        default: false
    },
    unit: {
        type: String,
        default: null
    },
    prefix: {
        type: String,
        default: ''
    },
    suffix: {
        type: String,
        default: ''
    },
    colored: {
        type: Boolean,
        default: false
    }
})

const numericValue = computed(() => {
    if (props.value === null || props.value === undefined) return null
    const num = typeof props.value === 'string' ? parseFloat(props.value) : props.value
    return isNaN(num) ? null : num
})

const formattedNumber = computed(() => {
    if (numericValue.value === null) return props.emptyText

    const options = {
        ...(props.decimals !== null && {
            minimumFractionDigits: props.decimals,
            maximumFractionDigits: props.decimals
        }),
        ...(props.compact && { notation: 'compact', compactDisplay: 'short' }),
        ...(props.unit && { style: 'unit', unit: props.unit })
    }

    const formatted = new Intl.NumberFormat(props.locale, options).format(numericValue.value)
    return `${props.prefix}${formatted}${props.suffix}`
})

const colorClass = computed(() => {
    if (!props.colored || numericValue.value === null) return ''
    if (numericValue.value > 0) return 'text-emerald-600'
    if (numericValue.value < 0) return 'text-red-600'
    return ''
})
</script>

<template>
    <span :class="['whitespace-nowrap tabular-nums', colorClass]">
        {{ formattedNumber }}
    </span>
</template>
