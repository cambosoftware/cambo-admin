<script setup>
import { computed } from 'vue'
import Badge from '@/Components/UI/Badge.vue'

const props = defineProps({
    value: {
        type: Array,
        default: () => []
    },
    labelKey: {
        type: String,
        default: null
    },
    max: {
        type: Number,
        default: 3
    },
    variant: {
        type: String,
        default: 'secondary'
    },
    emptyText: {
        type: String,
        default: '-'
    },
    separator: {
        type: String,
        default: null // null = badges, ',' = comma separated, etc.
    }
})

const items = computed(() => {
    if (!Array.isArray(props.value)) return []
    return props.value.map(item => {
        if (typeof item === 'string' || typeof item === 'number') return String(item)
        if (props.labelKey && item[props.labelKey]) return String(item[props.labelKey])
        return String(item.label || item.name || item.title || item)
    })
})

const visibleItems = computed(() => items.value.slice(0, props.max))
const hiddenCount = computed(() => Math.max(0, items.value.length - props.max))
const hasMore = computed(() => hiddenCount.value > 0)
</script>

<template>
    <span v-if="items.length === 0" class="text-gray-400">
        {{ emptyText }}
    </span>

    <!-- Separator mode (comma, etc.) -->
    <span v-else-if="separator" class="text-sm">
        {{ items.join(separator + ' ') }}
    </span>

    <!-- Badge mode -->
    <div v-else class="flex flex-wrap gap-1">
        <Badge
            v-for="(item, index) in visibleItems"
            :key="index"
            :variant="variant"
            size="sm"
        >
            {{ item }}
        </Badge>
        <Badge
            v-if="hasMore"
            variant="secondary"
            size="sm"
            :title="items.slice(max).join(', ')"
        >
            +{{ hiddenCount }}
        </Badge>
    </div>
</template>
