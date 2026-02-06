<script setup>
import { computed } from 'vue'
import Badge from '@/Components/UI/Badge.vue'

const props = defineProps({
    value: {
        type: [String, Number],
        default: null
    },
    statuses: {
        type: Object,
        default: () => ({})
        // { active: { label: 'Actif', variant: 'success' }, ... }
    },
    format: {
        type: String,
        default: 'badge',
        validator: (v) => ['badge', 'dot', 'text'].includes(v)
    },
    emptyText: {
        type: String,
        default: '-'
    }
})

// Default status mappings
const defaultStatuses = {
    active: { label: 'Actif', variant: 'success' },
    inactive: { label: 'Inactif', variant: 'secondary' },
    pending: { label: 'En attente', variant: 'warning' },
    approved: { label: 'Approuvé', variant: 'success' },
    rejected: { label: 'Rejeté', variant: 'danger' },
    draft: { label: 'Brouillon', variant: 'secondary' },
    published: { label: 'Publié', variant: 'success' },
    archived: { label: 'Archivé', variant: 'secondary' },
    processing: { label: 'En cours', variant: 'info' },
    completed: { label: 'Terminé', variant: 'success' },
    cancelled: { label: 'Annulé', variant: 'danger' },
    failed: { label: 'Échoué', variant: 'danger' },
    paid: { label: 'Payé', variant: 'success' },
    unpaid: { label: 'Non payé', variant: 'warning' },
    overdue: { label: 'En retard', variant: 'danger' }
}

const statusConfig = computed(() => {
    if (!props.value) return null

    const key = String(props.value).toLowerCase()
    const customStatus = props.statuses[props.value] || props.statuses[key]
    const defaultStatus = defaultStatuses[key]

    if (customStatus) return customStatus
    if (defaultStatus) return defaultStatus

    // Fallback: use value as label
    return { label: props.value, variant: 'secondary' }
})

const dotColorClass = computed(() => {
    if (!statusConfig.value) return 'bg-gray-400'
    const colors = {
        primary: 'bg-primary-500',
        secondary: 'bg-gray-400',
        success: 'bg-emerald-500',
        danger: 'bg-red-500',
        warning: 'bg-amber-500',
        info: 'bg-sky-500'
    }
    return colors[statusConfig.value.variant] || 'bg-gray-400'
})

const textColorClass = computed(() => {
    if (!statusConfig.value) return 'text-gray-500'
    const colors = {
        primary: 'text-primary-600',
        secondary: 'text-gray-600',
        success: 'text-emerald-600',
        danger: 'text-red-600',
        warning: 'text-amber-600',
        info: 'text-sky-600'
    }
    return colors[statusConfig.value.variant] || 'text-gray-600'
})
</script>

<template>
    <span v-if="!statusConfig" class="text-gray-400">
        {{ emptyText }}
    </span>

    <!-- Badge format -->
    <Badge
        v-else-if="format === 'badge'"
        :variant="statusConfig.variant"
        size="sm"
    >
        {{ statusConfig.label }}
    </Badge>

    <!-- Dot format -->
    <span v-else-if="format === 'dot'" class="inline-flex items-center gap-1.5">
        <span :class="['inline-block h-2 w-2 rounded-full', dotColorClass]" />
        <span class="text-sm text-gray-700">{{ statusConfig.label }}</span>
    </span>

    <!-- Text format -->
    <span v-else-if="format === 'text'" :class="['text-sm font-medium', textColorClass]">
        {{ statusConfig.label }}
    </span>
</template>
