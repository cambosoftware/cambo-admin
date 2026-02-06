<script setup>
import { computed, ref } from 'vue'
import Tooltip from '@/Components/UI/Tooltip.vue'

const props = defineProps({
    value: {
        type: String,
        default: null
    },
    maxLength: {
        type: Number,
        default: 50
    },
    lines: {
        type: Number,
        default: null
    },
    emptyText: {
        type: String,
        default: '-'
    },
    showTooltip: {
        type: Boolean,
        default: true
    },
    expandable: {
        type: Boolean,
        default: false
    }
})

const isExpanded = ref(false)

const isTruncated = computed(() => {
    if (!props.value) return false
    if (props.lines) return true // Always truncate when using lines
    return props.value.length > props.maxLength
})

const displayText = computed(() => {
    if (!props.value) return props.emptyText
    if (isExpanded.value) return props.value
    if (props.lines) return props.value // CSS handles truncation
    if (!isTruncated.value) return props.value
    return props.value.slice(0, props.maxLength) + '...'
})

const lineClampClass = computed(() => {
    if (!props.lines || isExpanded.value) return ''
    const clamps = {
        1: 'line-clamp-1',
        2: 'line-clamp-2',
        3: 'line-clamp-3',
        4: 'line-clamp-4',
        5: 'line-clamp-5',
        6: 'line-clamp-6'
    }
    return clamps[props.lines] || ''
})
</script>

<template>
    <div class="truncate-cell">
        <!-- With tooltip -->
        <Tooltip
            v-if="showTooltip && isTruncated && !isExpanded"
            :content="value"
            position="top"
        >
            <span :class="lineClampClass">{{ displayText }}</span>
        </Tooltip>

        <!-- Without tooltip -->
        <span v-else :class="lineClampClass">{{ displayText }}</span>

        <!-- Expand/collapse button -->
        <button
            v-if="expandable && isTruncated"
            type="button"
            class="ml-1 text-xs text-primary-600 hover:text-primary-800"
            @click.stop="isExpanded = !isExpanded"
        >
            {{ isExpanded ? 'Réduire' : 'Voir plus' }}
        </button>
    </div>
</template>
