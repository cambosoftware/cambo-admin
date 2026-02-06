<script setup>
import { computed } from 'vue'

const props = defineProps({
    text: {
        type: String,
        required: true
    },
    query: {
        type: String,
        default: ''
    },
    highlightClass: {
        type: String,
        default: 'bg-yellow-200 text-yellow-900'
    },
    caseSensitive: {
        type: Boolean,
        default: false
    }
})

const parts = computed(() => {
    if (!props.query || !props.text) {
        return [{ text: props.text, highlighted: false }]
    }

    const flags = props.caseSensitive ? 'g' : 'gi'
    const escapedQuery = props.query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
    const regex = new RegExp(`(${escapedQuery})`, flags)
    const segments = props.text.split(regex)

    return segments
        .filter(segment => segment !== '')
        .map(segment => ({
            text: segment,
            highlighted: regex.test(segment)
        }))
})
</script>

<template>
    <span>
        <template v-for="(part, index) in parts" :key="index">
            <mark v-if="part.highlighted" :class="['rounded-sm', highlightClass]">
                {{ part.text }}
            </mark>
            <template v-else>{{ part.text }}</template>
        </template>
    </span>
</template>
