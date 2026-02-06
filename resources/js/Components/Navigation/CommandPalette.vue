<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue'
import { router } from '@inertiajs/vue3'
import { MagnifyingGlassIcon, ArrowRightIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    /**
     * Array of command groups
     * Each group: { name: string, items: CommandItem[] }
     * CommandItem: { id, label, description?, icon?, shortcut?, action?, href?, keywords? }
     */
    groups: {
        type: Array,
        default: () => []
    },
    /**
     * Placeholder text for search input
     */
    placeholder: {
        type: String,
        default: 'Rechercher une commande...'
    },
    /**
     * Show recent searches
     */
    showRecent: {
        type: Boolean,
        default: true
    },
    /**
     * Maximum recent items to store
     */
    maxRecent: {
        type: Number,
        default: 5
    }
})

const emit = defineEmits(['select', 'close'])

const show = ref(false)
const query = ref('')
const selectedIndex = ref(0)
const inputRef = ref(null)
const recentItems = ref([])

// Storage key for recent items
const RECENT_KEY = 'cambo-command-palette-recent'

// Load recent items from localStorage
onMounted(() => {
    const stored = localStorage.getItem(RECENT_KEY)
    if (stored) {
        try {
            recentItems.value = JSON.parse(stored)
        } catch (e) {
            recentItems.value = []
        }
    }

    // Global keyboard listener
    document.addEventListener('keydown', onGlobalKeydown)
})

onUnmounted(() => {
    document.removeEventListener('keydown', onGlobalKeydown)
})

// Global keydown handler
const onGlobalKeydown = (e) => {
    // Cmd+K or Ctrl+K to open
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault()
        open()
    }
}

// Flatten all items for navigation
const flatItems = computed(() => {
    const items = []
    filteredGroups.value.forEach(group => {
        group.items.forEach(item => {
            items.push({ ...item, group: group.name })
        })
    })
    return items
})

// Filter groups and items based on query
const filteredGroups = computed(() => {
    if (!query.value.trim()) {
        // Show recent items when no query
        if (props.showRecent && recentItems.value.length > 0) {
            return [
                { name: 'Récents', items: recentItems.value.slice(0, props.maxRecent) },
                ...props.groups
            ]
        }
        return props.groups
    }

    const q = query.value.toLowerCase()
    return props.groups
        .map(group => ({
            ...group,
            items: group.items.filter(item => {
                const searchText = [
                    item.label,
                    item.description,
                    ...(item.keywords || [])
                ].filter(Boolean).join(' ').toLowerCase()
                return searchText.includes(q)
            })
        }))
        .filter(group => group.items.length > 0)
})

// Keep selected index in bounds
watch(flatItems, () => {
    if (selectedIndex.value >= flatItems.value.length) {
        selectedIndex.value = Math.max(0, flatItems.value.length - 1)
    }
})

// Reset query and selection when opening
watch(show, (val) => {
    if (val) {
        query.value = ''
        selectedIndex.value = 0
        nextTick(() => inputRef.value?.focus())
        document.body.style.overflow = 'hidden'
    } else {
        document.body.style.overflow = ''
    }
})

// Methods
const open = () => {
    show.value = true
}

const close = () => {
    show.value = false
    emit('close')
}

const selectItem = (item) => {
    // Add to recent
    addToRecent(item)

    // Execute action or navigate
    if (item.action) {
        item.action()
    } else if (item.href) {
        router.visit(item.href)
    }

    emit('select', item)
    close()
}

const addToRecent = (item) => {
    // Remove if already exists
    recentItems.value = recentItems.value.filter(r => r.id !== item.id)
    // Add to front
    recentItems.value.unshift({ ...item, group: undefined })
    // Keep max items
    recentItems.value = recentItems.value.slice(0, props.maxRecent)
    // Save to localStorage
    localStorage.setItem(RECENT_KEY, JSON.stringify(recentItems.value))
}

const onKeydown = (e) => {
    switch (e.key) {
        case 'ArrowDown':
            e.preventDefault()
            selectedIndex.value = Math.min(selectedIndex.value + 1, flatItems.value.length - 1)
            scrollToSelected()
            break
        case 'ArrowUp':
            e.preventDefault()
            selectedIndex.value = Math.max(selectedIndex.value - 1, 0)
            scrollToSelected()
            break
        case 'Enter':
            e.preventDefault()
            if (flatItems.value[selectedIndex.value]) {
                selectItem(flatItems.value[selectedIndex.value])
            }
            break
        case 'Escape':
            e.preventDefault()
            close()
            break
    }
}

const scrollToSelected = () => {
    nextTick(() => {
        const selected = document.querySelector('[data-selected="true"]')
        selected?.scrollIntoView({ block: 'nearest' })
    })
}

// Expose open method for external use
defineExpose({ open, close })
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-start justify-center pt-[20vh]"
            >
                <!-- Overlay -->
                <div
                    class="absolute inset-0 bg-black/50 dark:bg-black/70"
                    @click="close"
                />

                <!-- Palette -->
                <Transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="opacity-0 scale-95 -translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 -translate-y-4"
                    appear
                >
                    <div
                        v-if="show"
                        class="relative w-full max-w-xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl dark:shadow-gray-900/50 overflow-hidden"
                        @keydown="onKeydown"
                    >
                        <!-- Search input -->
                        <div class="flex items-center gap-3 px-4 border-b border-gray-200 dark:border-gray-700">
                            <MagnifyingGlassIcon class="h-5 w-5 text-gray-400 dark:text-gray-500 flex-shrink-0" />
                            <input
                                ref="inputRef"
                                v-model="query"
                                type="text"
                                :placeholder="placeholder"
                                class="flex-1 py-4 bg-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none"
                            />
                            <kbd class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 rounded">
                                ESC
                            </kbd>
                        </div>

                        <!-- Results -->
                        <div class="max-h-80 overflow-y-auto">
                            <template v-if="filteredGroups.length > 0">
                                <div v-for="group in filteredGroups" :key="group.name" class="py-2">
                                    <!-- Group header -->
                                    <div class="px-4 py-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ group.name }}
                                    </div>

                                    <!-- Group items -->
                                    <button
                                        v-for="(item, index) in group.items"
                                        :key="item.id"
                                        type="button"
                                        :data-selected="flatItems.findIndex(f => f.id === item.id) === selectedIndex"
                                        :class="[
                                            'w-full flex items-center gap-3 px-4 py-2.5 text-left transition-colors',
                                            flatItems.findIndex(f => f.id === item.id) === selectedIndex
                                                ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-900 dark:text-primary-100'
                                                : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50'
                                        ]"
                                        @click="selectItem(item)"
                                        @mouseenter="selectedIndex = flatItems.findIndex(f => f.id === item.id)"
                                    >
                                        <!-- Icon -->
                                        <component
                                            v-if="item.icon"
                                            :is="item.icon"
                                            class="h-5 w-5 flex-shrink-0 text-gray-400 dark:text-gray-500"
                                        />
                                        <div
                                            v-else
                                            class="h-5 w-5 flex-shrink-0 rounded bg-gray-200 dark:bg-gray-600"
                                        />

                                        <!-- Label & description -->
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium truncate">{{ item.label }}</div>
                                            <div v-if="item.description" class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                {{ item.description }}
                                            </div>
                                        </div>

                                        <!-- Shortcut or arrow -->
                                        <div class="flex-shrink-0">
                                            <kbd
                                                v-if="item.shortcut"
                                                class="inline-flex items-center gap-1 px-2 py-0.5 text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 rounded"
                                            >
                                                {{ item.shortcut }}
                                            </kbd>
                                            <ArrowRightIcon v-else class="h-4 w-4 text-gray-400 dark:text-gray-500" />
                                        </div>
                                    </button>
                                </div>
                            </template>

                            <!-- No results -->
                            <div v-else class="py-12 text-center">
                                <p class="text-gray-500 dark:text-gray-400">Aucun résultat pour "{{ query }}"</p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-between px-4 py-2 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-500">
                            <div class="flex items-center gap-4">
                                <span class="flex items-center gap-1">
                                    <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">↑</kbd>
                                    <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">↓</kbd>
                                    naviguer
                                </span>
                                <span class="flex items-center gap-1">
                                    <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">↵</kbd>
                                    sélectionner
                                </span>
                            </div>
                            <span class="flex items-center gap-1">
                                <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">⌘</kbd>
                                <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">K</kbd>
                                ouvrir
                            </span>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
