<script setup>
import { computed } from 'vue'

const props = defineProps({
    value: {
        type: [String, Date],
        default: null
    },
    format: {
        type: String,
        default: 'short' // 'short', 'medium', 'long', 'relative', or custom format
    },
    locale: {
        type: String,
        default: 'fr-FR'
    },
    emptyText: {
        type: String,
        default: '-'
    }
})

const formattedDate = computed(() => {
    if (!props.value) return props.emptyText

    const date = props.value instanceof Date ? props.value : new Date(props.value)

    if (isNaN(date.getTime())) return props.emptyText

    if (props.format === 'relative') {
        return formatRelative(date)
    }

    const options = getFormatOptions(props.format)
    return new Intl.DateTimeFormat(props.locale, options).format(date)
})

const getFormatOptions = (format) => {
    const formats = {
        short: { day: '2-digit', month: '2-digit', year: 'numeric' },
        medium: { day: 'numeric', month: 'short', year: 'numeric' },
        long: { day: 'numeric', month: 'long', year: 'numeric' },
        full: { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }
    }
    return formats[format] || formats.short
}

const formatRelative = (date) => {
    const now = new Date()
    const diffMs = now - date
    const diffSecs = Math.floor(diffMs / 1000)
    const diffMins = Math.floor(diffSecs / 60)
    const diffHours = Math.floor(diffMins / 60)
    const diffDays = Math.floor(diffHours / 24)

    if (diffSecs < 60) return 'À l\'instant'
    if (diffMins < 60) return `Il y a ${diffMins} min`
    if (diffHours < 24) return `Il y a ${diffHours}h`
    if (diffDays < 7) return `Il y a ${diffDays}j`
    if (diffDays < 30) return `Il y a ${Math.floor(diffDays / 7)} sem`
    if (diffDays < 365) return `Il y a ${Math.floor(diffDays / 30)} mois`
    return `Il y a ${Math.floor(diffDays / 365)} an${Math.floor(diffDays / 365) > 1 ? 's' : ''}`
}

const fullDate = computed(() => {
    if (!props.value) return null
    const date = props.value instanceof Date ? props.value : new Date(props.value)
    if (isNaN(date.getTime())) return null
    return new Intl.DateTimeFormat(props.locale, {
        dateStyle: 'full',
        timeStyle: 'short'
    }).format(date)
})
</script>

<template>
    <span :title="fullDate" class="whitespace-nowrap">
        {{ formattedDate }}
    </span>
</template>
