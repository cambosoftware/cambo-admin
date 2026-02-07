<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowTopRightOnSquareIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    value: {
        type: String,
        default: null
    },
    href: {
        type: String,
        default: null
    },
    label: {
        type: String,
        default: null
    },
    external: {
        type: Boolean,
        default: false
    },
    showIcon: {
        type: Boolean,
        default: true
    },
    truncate: {
        type: Boolean,
        default: true
    },
    maxLength: {
        type: Number,
        default: 30
    },
    emptyText: {
        type: String,
        default: '-'
    }
})

const displayText = computed(() => {
    const text = props.label || props.value || props.href
    if (!text) return props.emptyText
    if (!props.truncate || text.length <= props.maxLength) return text
    return text.slice(0, props.maxLength) + '...'
})

const linkUrl = computed(() => props.href || props.value)

const isExternal = computed(() => {
    if (props.external) return true
    if (!linkUrl.value) return false
    return linkUrl.value.startsWith('http://') || linkUrl.value.startsWith('https://')
})
</script>

<template>
    <span v-if="!linkUrl" class="text-gray-400">
        {{ emptyText }}
    </span>

    <a
        v-else-if="isExternal"
        :href="linkUrl"
        target="_blank"
        rel="noopener noreferrer"
        class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 hover:underline"
        :title="value || href"
    >
        <span :class="truncate ? 'truncate max-w-xs' : ''">{{ displayText }}</span>
        <ArrowTopRightOnSquareIcon v-if="showIcon" class="h-3.5 w-3.5 flex-shrink-0" />
    </a>

    <Link
        v-else
        :href="linkUrl"
        class="text-indigo-600 hover:text-indigo-800 hover:underline"
        :title="value || href"
    >
        <span :class="truncate ? 'truncate max-w-xs' : ''">{{ displayText }}</span>
    </Link>
</template>
