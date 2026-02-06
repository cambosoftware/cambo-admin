<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    date: {
        type: [String, Date, Number],
        required: true
    },
    locale: {
        type: String,
        default: 'fr-FR'
    },
    refreshInterval: {
        type: Number,
        default: 60000 // 1 minute
    },
    showTooltip: {
        type: Boolean,
        default: true
    }
})

const now = ref(new Date())
let intervalId = null

onMounted(() => {
    if (props.refreshInterval > 0) {
        intervalId = setInterval(() => {
            now.value = new Date()
        }, props.refreshInterval)
    }
})

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId)
})

const dateObject = computed(() => {
    if (props.date instanceof Date) return props.date
    if (typeof props.date === 'number') return new Date(props.date)
    return new Date(props.date)
})

const relativeTime = computed(() => {
    const date = dateObject.value
    if (isNaN(date.getTime())) return '-'

    const diffMs = now.value - date
    const diffSecs = Math.floor(Math.abs(diffMs) / 1000)
    const diffMins = Math.floor(diffSecs / 60)
    const diffHours = Math.floor(diffMins / 60)
    const diffDays = Math.floor(diffHours / 24)
    const diffWeeks = Math.floor(diffDays / 7)
    const diffMonths = Math.floor(diffDays / 30)
    const diffYears = Math.floor(diffDays / 365)

    const isFuture = diffMs < 0
    const prefix = isFuture ? 'Dans ' : 'Il y a '

    if (diffSecs < 60) return 'À l\'instant'
    if (diffMins < 60) return `${prefix}${diffMins} min`
    if (diffHours < 24) return `${prefix}${diffHours}h`
    if (diffDays < 7) return `${prefix}${diffDays}j`
    if (diffWeeks < 4) return `${prefix}${diffWeeks} sem`
    if (diffMonths < 12) return `${prefix}${diffMonths} mois`
    return `${prefix}${diffYears} an${diffYears > 1 ? 's' : ''}`
})

const fullDate = computed(() => {
    const date = dateObject.value
    if (isNaN(date.getTime())) return null
    return new Intl.DateTimeFormat(props.locale, {
        dateStyle: 'full',
        timeStyle: 'short'
    }).format(date)
})
</script>

<template>
    <time
        :datetime="dateObject.toISOString()"
        :title="showTooltip ? fullDate : undefined"
        class="whitespace-nowrap"
    >
        {{ relativeTime }}
    </time>
</template>
