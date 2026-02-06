<script setup>
import { computed } from 'vue'

const props = defineProps({
    value: {
        type: [String, Date],
        default: null
    },
    format: {
        type: String,
        default: 'short' // 'short', 'medium', 'long'
    },
    locale: {
        type: String,
        default: 'fr-FR'
    },
    emptyText: {
        type: String,
        default: '-'
    },
    showSeconds: {
        type: Boolean,
        default: false
    }
})

const formattedDateTime = computed(() => {
    if (!props.value) return props.emptyText

    const date = props.value instanceof Date ? props.value : new Date(props.value)

    if (isNaN(date.getTime())) return props.emptyText

    const dateOptions = getDateOptions(props.format)
    const timeOptions = {
        hour: '2-digit',
        minute: '2-digit',
        ...(props.showSeconds && { second: '2-digit' })
    }

    return new Intl.DateTimeFormat(props.locale, { ...dateOptions, ...timeOptions }).format(date)
})

const getDateOptions = (format) => {
    const formats = {
        short: { day: '2-digit', month: '2-digit', year: 'numeric' },
        medium: { day: 'numeric', month: 'short', year: 'numeric' },
        long: { day: 'numeric', month: 'long', year: 'numeric' }
    }
    return formats[format] || formats.short
}
</script>

<template>
    <span class="whitespace-nowrap">
        {{ formattedDateTime }}
    </span>
</template>
