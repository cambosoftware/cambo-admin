<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue'
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
const filterButtonRef = ref(null)
const dropdownStyle = ref({})

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

const updateDropdownPosition = async () => {
    if (!filterButtonRef.value) return

    await nextTick()

    const rect = filterButtonRef.value.getBoundingClientRect()
    const scrollY = window.scrollY
    const scrollX = window.scrollX

    dropdownStyle.value = {
        position: 'absolute',
        zIndex: 9999,
        top: `${rect.bottom + scrollY + 4}px`,
        left: `${rect.left + scrollX}px`,
        minWidth: '150px'
    }
}

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

const onScroll = () => {
    if (showFilterDropdown.value) {
        updateDropdownPosition()
    }
}

const onResize = () => {
    if (showFilterDropdown.value) {
        updateDropdownPosition()
    }
}

watch(showFilterDropdown, async (isOpen) => {
    if (isOpen) {
        await updateDropdownPosition()
    }
})

onMounted(() => {
    window.addEventListener('scroll', onScroll, true)
    window.addEventListener('resize', onResize)
})

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll, true)
    window.removeEventListener('resize', onResize)
})
</script>

<template>
    <th
        :class="[
            'text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider select-none group relative',
            paddingClass
        ]"
    >
        <div :class="['flex items-center gap-1', alignClass]">
            <!-- Sortable part -->
            <button
                type="button"
                class="flex items-center gap-1 hover:text-gray-900 dark:hover:text-gray-200 transition-colors cursor-pointer"
                @click="handleSort"
            >
                <slot />
                <span class="flex-shrink-0">
                    <ChevronUpIcon
                        v-if="isAsc"
                        class="h-4 w-4 text-indigo-500"
                    />
                    <ChevronDownIcon
                        v-else-if="isDesc"
                        class="h-4 w-4 text-indigo-500"
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
                ref="filterButtonRef"
                type="button"
                :class="[
                    'p-0.5 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer',
                    hasActiveFilter ? 'text-indigo-500' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300'
                ]"
                @click="toggleFilterDropdown"
            >
                <FunnelIcon class="h-3.5 w-3.5" />
            </button>
        </div>

        <!-- Backdrop to close dropdown -->
        <Teleport to="body">
            <div
                v-if="showFilterDropdown"
                class="fixed inset-0 z-[9998]"
                @click="closeDropdown"
            />
        </Teleport>

        <!-- Filter dropdown (teleported to body) -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-150 ease-out"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-if="filterable && showFilterDropdown"
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg overflow-hidden"
                    :style="dropdownStyle"
                    @click.stop
                >
                    <div class="py-1">
                        <button
                            type="button"
                            :class="[
                                'w-full text-left px-3 py-1.5 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer',
                                !hasActiveFilter ? 'text-indigo-600 dark:text-indigo-400 font-medium' : 'text-gray-700 dark:text-gray-300'
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
                                'w-full text-left px-3 py-1.5 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer',
                                filterValue === option.value ? 'text-indigo-600 dark:text-indigo-400 font-medium bg-indigo-50 dark:bg-indigo-900/50' : 'text-gray-700 dark:text-gray-300'
                            ]"
                            @click="handleFilter(option.value)"
                        >
                            {{ option.label }}
                        </button>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </th>
</template>
