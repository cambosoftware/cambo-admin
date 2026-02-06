<script setup>
import { computed } from 'vue'

const props = defineProps({
    value: {
        type: [Number, String],
        default: null
    },
    currency: {
        type: String,
        default: 'EUR'
    },
    locale: {
        type: String,
        default: 'fr-FR'
    },
    emptyText: {
        type: String,
        default: '-'
    },
    showSign: {
        type: Boolean,
        default: false
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

const formattedCurrency = computed(() => {
    if (numericValue.value === null) return props.emptyText

    const options = {
        style: 'currency',
        currency: props.currency,
        signDisplay: props.showSign ? 'always' : 'auto'
    }

    return new Intl.NumberFormat(props.locale, options).format(numericValue.value)
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
        {{ formattedCurrency }}
    </span>
</template>
