<script setup>
import { computed } from 'vue'
import { ChevronLeftIcon, ChevronRightIcon, ChevronDoubleLeftIcon, ChevronDoubleRightIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    currentPage: {
        type: Number,
        required: true
    },
    totalPages: {
        type: Number,
        required: true
    },
    totalItems: {
        type: Number,
        default: null
    },
    perPage: {
        type: Number,
        default: null
    },
    perPageOptions: {
        type: Array,
        default: () => [10, 25, 50, 100]
    },
    showPerPage: {
        type: Boolean,
        default: true
    },
    showInfo: {
        type: Boolean,
        default: true
    },
    showFirstLast: {
        type: Boolean,
        default: true
    },
    maxVisiblePages: {
        type: Number,
        default: 5
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    },
    simple: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:currentPage', 'update:perPage'])

const from = computed(() => {
    if (!props.totalItems || !props.perPage) return null
    return (props.currentPage - 1) * props.perPage + 1
})

const to = computed(() => {
    if (!props.totalItems || !props.perPage) return null
    return Math.min(props.currentPage * props.perPage, props.totalItems)
})

const pages = computed(() => {
    if (props.simple) return []

    const total = props.totalPages
    const current = props.currentPage
    const maxVisible = props.maxVisiblePages

    if (total <= maxVisible) {
        return Array.from({ length: total }, (_, i) => i + 1)
    }

    const pages = []
    const half = Math.floor(maxVisible / 2)

    let start = Math.max(1, current - half)
    let end = Math.min(total, start + maxVisible - 1)

    if (end - start < maxVisible - 1) {
        start = Math.max(1, end - maxVisible + 1)
    }

    // First page
    if (start > 1) {
        pages.push(1)
        if (start > 2) pages.push('...')
    }

    // Middle pages
    for (let i = start; i <= end; i++) {
        if (i !== 1 && i !== total) {
            pages.push(i)
        }
    }

    // Last page
    if (end < total) {
        if (end < total - 1) pages.push('...')
        pages.push(total)
    }

    return pages
})

const canGoPrevious = computed(() => props.currentPage > 1)
const canGoNext = computed(() => props.currentPage < props.totalPages)

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'h-7 min-w-7 text-xs',
        md: 'h-8 min-w-8 text-sm',
        lg: 'h-10 min-w-10 text-base'
    }
    return sizes[props.size]
})

const goToPage = (page) => {
    if (page >= 1 && page <= props.totalPages && page !== props.currentPage) {
        emit('update:currentPage', page)
    }
}

const changePerPage = (value) => {
    emit('update:perPage', parseInt(value))
    emit('update:currentPage', 1)
}
</script>

<template>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-3">
        <!-- Info & Per page -->
        <div class="flex items-center gap-4 text-sm text-gray-600">
            <!-- Items info -->
            <span v-if="showInfo && from !== null">
                {{ from }}-{{ to }} sur {{ totalItems }}
            </span>

            <!-- Per page selector -->
            <div v-if="showPerPage && perPage" class="flex items-center gap-2">
                <span class="text-gray-500">Afficher</span>
                <select
                    :value="perPage"
                    class="rounded-md border-gray-300 text-sm py-1 pr-8 focus:border-primary-500 focus:ring-primary-500"
                    @change="changePerPage($event.target.value)"
                >
                    <option v-for="option in perPageOptions" :key="option" :value="option">
                        {{ option }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Pagination controls -->
        <nav class="flex items-center gap-1">
            <!-- First page -->
            <button
                v-if="showFirstLast && !simple"
                type="button"
                :disabled="!canGoPrevious"
                :class="[
                    'inline-flex items-center justify-center rounded-md transition-colors',
                    sizeClasses,
                    canGoPrevious
                        ? 'text-gray-700 hover:bg-gray-100'
                        : 'text-gray-300 cursor-not-allowed'
                ]"
                @click="goToPage(1)"
            >
                <ChevronDoubleLeftIcon class="h-4 w-4" />
            </button>

            <!-- Previous -->
            <button
                type="button"
                :disabled="!canGoPrevious"
                :class="[
                    'inline-flex items-center justify-center rounded-md transition-colors',
                    sizeClasses,
                    canGoPrevious
                        ? 'text-gray-700 hover:bg-gray-100'
                        : 'text-gray-300 cursor-not-allowed'
                ]"
                @click="goToPage(currentPage - 1)"
            >
                <ChevronLeftIcon class="h-4 w-4" />
            </button>

            <!-- Simple mode: just show current/total -->
            <template v-if="simple">
                <span class="px-3 text-sm text-gray-600">
                    Page {{ currentPage }} / {{ totalPages }}
                </span>
            </template>

            <!-- Page numbers -->
            <template v-else>
                <template v-for="page in pages" :key="page">
                    <span
                        v-if="page === '...'"
                        :class="['inline-flex items-center justify-center text-gray-400', sizeClasses]"
                    >
                        ...
                    </span>
                    <button
                        v-else
                        type="button"
                        :class="[
                            'inline-flex items-center justify-center rounded-md font-medium transition-colors',
                            sizeClasses,
                            page === currentPage
                                ? 'bg-primary-500 text-white'
                                : 'text-gray-700 hover:bg-gray-100'
                        ]"
                        @click="goToPage(page)"
                    >
                        {{ page }}
                    </button>
                </template>
            </template>

            <!-- Next -->
            <button
                type="button"
                :disabled="!canGoNext"
                :class="[
                    'inline-flex items-center justify-center rounded-md transition-colors',
                    sizeClasses,
                    canGoNext
                        ? 'text-gray-700 hover:bg-gray-100'
                        : 'text-gray-300 cursor-not-allowed'
                ]"
                @click="goToPage(currentPage + 1)"
            >
                <ChevronRightIcon class="h-4 w-4" />
            </button>

            <!-- Last page -->
            <button
                v-if="showFirstLast && !simple"
                type="button"
                :disabled="!canGoNext"
                :class="[
                    'inline-flex items-center justify-center rounded-md transition-colors',
                    sizeClasses,
                    canGoNext
                        ? 'text-gray-700 hover:bg-gray-100'
                        : 'text-gray-300 cursor-not-allowed'
                ]"
                @click="goToPage(totalPages)"
            >
                <ChevronDoubleRightIcon class="h-4 w-4" />
            </button>
        </nav>
    </div>
</template>
