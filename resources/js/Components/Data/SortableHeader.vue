<script setup>
import { ref, computed } from 'vue'
import { ChevronUpIcon, ChevronDownIcon, ChevronUpDownIcon, FunnelIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    column: {
        type: String,
        required: true
    },
    currentSort: {
        type: String,
        default: null
    },
    currentDirection: {
        type: String,
        default: 'asc',
        validator: (v) => ['asc', 'desc'].includes(v)
    },
    align: {
        type: String,
        default: 'left',
        validator: (v) => ['left', 'center', 'right'].includes(v)
    },
    compact: {
        type: Boolean,
        default: false
    },
    // Filter props
    filterable: {
        type: Boolean,
        default: false
    },
    filterOptions: {
        type: Array,
        default: () => []
        // [{ label: 'Active', value: 'active' }, ...] or ['active', 'pending', ...]
    },
    filterValue: {
        type: [String, Number, Boolean, null],
        default: null
    },
    filterPlaceholder: {
        type: String,
        default: 'Tous'
    }
})

const emit = defineEmits(['sort', 'filter'])

const showFilterDropdown = ref(false)

const isActive = computed(() => props.currentSort === props.column)
const isAsc = computed(() => isActive.value && props.currentDirection === 'asc')
const isDesc = computed(() => isActive.value && props.currentDirection === 'desc')
const hasActiveFilter = computed(() => props.filterValue !== null && props.filterValue !== '' && props.filterValue !== undefined)

const normalizedFilterOptions = computed(() => {
    return props.filterOptions.map(opt => {
        if (typeof opt === 'object' && opt !== null) {
            return { label: opt.label, value: opt.value }
        }
        return { label: String(opt), value: opt }
    })
})

const alignClass = computed(() => {
    const aligns = {
        left: 'justify-start',
        center: 'justify-center',
        right: 'justify-end'
    }
    return aligns[props.align]
})

const paddingClass = computed(() => {
    return props.compact ? 'px-3 py-2' : 'px-4 py-3'
})

const handleSort = () => {
    let direction = 'asc'
    if (isActive.value) {
        direction = props.currentDirection === 'asc' ? 'desc' : 'asc'
    }
    emit('sort', { column: props.column, direction })
}

const handleFilter = (value) => {
    emit('filter', { column: props.column, value: value === '' ? null : value })
    showFilterDropdown.value = false
}

const toggleFilterDropdown = (event) => {
    event.stopPropagation()
    showFilterDropdown.value = !showFilterDropdown.value
}

const closeDropdown = () => {
    showFilterDropdown.value = false
}
</script>

<template>
    <th
        :class="[
            'text-xs font-semibold text-gray-600 uppercase tracking-wider select-none group relative',
            paddingClass
        ]"
    >
        <div :class="['flex items-center gap-1', alignClass]">
            <!-- Sortable part -->
            <button
                type="button"
                class="flex items-center gap-1 hover:text-gray-900 transition-colors cursor-pointer"
                @click="handleSort"
            >
                <slot />
                <span class="flex-shrink-0">
                    <ChevronUpIcon
                        v-if="isAsc"
                        class="h-4 w-4 text-primary-500"
                    />
                    <ChevronDownIcon
                        v-else-if="isDesc"
                        class="h-4 w-4 text-primary-500"
                    />
                    <ChevronUpDownIcon
                        v-else
                        class="h-4 w-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"
                    />
                </span>
            </button>

            <!-- Filter icon -->
            <button
                v-if="filterable"
                type="button"
                :class="[
                    'p-0.5 rounded hover:bg-gray-200 transition-colors cursor-pointer',
                    hasActiveFilter ? 'text-primary-500' : 'text-gray-400 hover:text-gray-600'
                ]"
                @click="toggleFilterDropdown"
            >
                <FunnelIcon class="h-3.5 w-3.5" />
            </button>
        </div>

        <!-- Filter dropdown -->
        <div
            v-if="filterable && showFilterDropdown"
            class="absolute top-full left-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 min-w-[150px]"
            @click.stop
        >
            <div class="py-1">
                <button
                    type="button"
                    :class="[
                        'w-full text-left px-3 py-1.5 text-sm hover:bg-gray-100 transition-colors cursor-pointer',
                        !hasActiveFilter ? 'text-primary-600 font-medium' : 'text-gray-700'
                    ]"
                    @click="handleFilter('')"
                >
                    {{ filterPlaceholder }}
                </button>
                <button
                    v-for="option in normalizedFilterOptions"
                    :key="option.value"
                    type="button"
                    :class="[
                        'w-full text-left px-3 py-1.5 text-sm hover:bg-gray-100 transition-colors cursor-pointer',
                        filterValue === option.value ? 'text-primary-600 font-medium bg-primary-50' : 'text-gray-700'
                    ]"
                    @click="handleFilter(option.value)"
                >
                    {{ option.label }}
                </button>
            </div>
        </div>

        <!-- Backdrop to close dropdown -->
        <div
            v-if="showFilterDropdown"
            class="fixed inset-0 z-40"
            @click="closeDropdown"
        />
    </th>
</template>
