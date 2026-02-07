<script setup>
import { ref, computed } from 'vue'
import {
    FolderIcon,
    DocumentIcon,
    PhotoIcon,
    FilmIcon,
    MusicalNoteIcon,
    DocumentTextIcon,
    ArchiveBoxIcon,
    CodeBracketIcon,
    TableCellsIcon,
    PresentationChartBarIcon,
    ChevronRightIcon,
    HomeIcon,
    Squares2X2Icon,
    ListBulletIcon,
    ArrowUpTrayIcon,
    FolderPlusIcon,
    MagnifyingGlassIcon,
    EllipsisVerticalIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    /**
     * Array of files and folders
     * Each item: { id, name, type: 'file'|'folder', size?, modified?, mimeType?, thumbnail?, children? }
     */
    items: {
        type: Array,
        default: () => []
    },
    /**
     * Current path (array of folder names or objects with id/name)
     */
    path: {
        type: Array,
        default: () => []
    },
    /**
     * View mode: 'grid' or 'list'
     */
    viewMode: {
        type: String,
        default: 'grid',
        validator: (v) => ['grid', 'list'].includes(v)
    },
    /**
     * Allow file uploads
     */
    allowUpload: {
        type: Boolean,
        default: true
    },
    /**
     * Allow creating folders
     */
    allowCreateFolder: {
        type: Boolean,
        default: true
    },
    /**
     * Allow multiple selection
     */
    multiSelect: {
        type: Boolean,
        default: true
    },
    /**
     * Loading state
     */
    loading: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits([
    'item-click',
    'item-double-click',
    'item-select',
    'folder-open',
    'path-change',
    'upload',
    'create-folder',
    'item-menu',
    'search'
])

// State
const view = ref(props.viewMode)
const selectedItems = ref(new Set())
const searchQuery = ref('')

// File type icons
const fileIcons = {
    folder: FolderIcon,
    image: PhotoIcon,
    video: FilmIcon,
    audio: MusicalNoteIcon,
    document: DocumentTextIcon,
    archive: ArchiveBoxIcon,
    code: CodeBracketIcon,
    spreadsheet: TableCellsIcon,
    presentation: PresentationChartBarIcon,
    default: DocumentIcon
}

// Get file type from mime type or extension
const getFileType = (item) => {
    if (item.type === 'folder') return 'folder'

    const mimeType = item.mimeType || ''
    const name = item.name.toLowerCase()

    if (mimeType.startsWith('image/') || /\.(jpg|jpeg|png|gif|webp|svg|bmp)$/.test(name)) return 'image'
    if (mimeType.startsWith('video/') || /\.(mp4|mov|avi|mkv|webm)$/.test(name)) return 'video'
    if (mimeType.startsWith('audio/') || /\.(mp3|wav|ogg|flac|aac)$/.test(name)) return 'audio'
    if (/\.(pdf|doc|docx|txt|rtf|odt)$/.test(name)) return 'document'
    if (/\.(zip|rar|7z|tar|gz)$/.test(name)) return 'archive'
    if (/\.(js|ts|vue|jsx|tsx|py|php|java|c|cpp|html|css|json|xml)$/.test(name)) return 'code'
    if (/\.(xls|xlsx|csv)$/.test(name)) return 'spreadsheet'
    if (/\.(ppt|pptx|key)$/.test(name)) return 'presentation'

    return 'default'
}

const getFileIcon = (item) => fileIcons[getFileType(item)]

// Format file size
const formatSize = (bytes) => {
    if (!bytes) return '-'
    const units = ['o', 'Ko', 'Mo', 'Go', 'To']
    let i = 0
    while (bytes >= 1024 && i < units.length - 1) {
        bytes /= 1024
        i++
    }
    return `${bytes.toFixed(i === 0 ? 0 : 1)} ${units[i]}`
}

// Filter items based on search
const filteredItems = computed(() => {
    if (!searchQuery.value.trim()) return props.items
    const q = searchQuery.value.toLowerCase()
    return props.items.filter(item => item.name.toLowerCase().includes(q))
})

// Sort items (folders first, then alphabetically)
const sortedItems = computed(() => {
    return [...filteredItems.value].sort((a, b) => {
        if (a.type === 'folder' && b.type !== 'folder') return -1
        if (a.type !== 'folder' && b.type === 'folder') return 1
        return a.name.localeCompare(b.name)
    })
})

// Selection
const isSelected = (item) => selectedItems.value.has(item.id)

const toggleSelect = (item, event) => {
    if (!props.multiSelect) {
        selectedItems.value.clear()
        selectedItems.value.add(item.id)
    } else if (event?.ctrlKey || event?.metaKey) {
        if (selectedItems.value.has(item.id)) {
            selectedItems.value.delete(item.id)
        } else {
            selectedItems.value.add(item.id)
        }
    } else if (event?.shiftKey && selectedItems.value.size > 0) {
        // Range selection
        const items = sortedItems.value
        const lastSelected = [...selectedItems.value].pop()
        const lastIndex = items.findIndex(i => i.id === lastSelected)
        const currentIndex = items.findIndex(i => i.id === item.id)
        const [start, end] = [Math.min(lastIndex, currentIndex), Math.max(lastIndex, currentIndex)]
        for (let i = start; i <= end; i++) {
            selectedItems.value.add(items[i].id)
        }
    } else {
        selectedItems.value.clear()
        selectedItems.value.add(item.id)
    }

    emit('item-select', [...selectedItems.value])
}

const clearSelection = () => {
    selectedItems.value.clear()
    emit('item-select', [])
}

// Event handlers
const onItemClick = (item, event) => {
    toggleSelect(item, event)
    emit('item-click', item)
}

const onItemDoubleClick = (item) => {
    if (item.type === 'folder') {
        emit('folder-open', item)
    }
    emit('item-double-click', item)
}

const onPathClick = (index) => {
    emit('path-change', index)
}

const onUpload = () => {
    emit('upload')
}

const onCreateFolder = () => {
    emit('create-folder')
}

const onItemMenu = (event, item) => {
    event.preventDefault()
    emit('item-menu', { item, event })
}

const onSearch = () => {
    emit('search', searchQuery.value)
}
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Toolbar -->
        <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-1 text-sm overflow-x-auto">
                <button
                    type="button"
                    class="flex items-center gap-1 px-2 py-1 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                    @click="onPathClick(-1)"
                >
                    <HomeIcon class="h-4 w-4" />
                </button>
                <template v-for="(folder, index) in path" :key="index">
                    <ChevronRightIcon class="h-4 w-4 text-gray-400 flex-shrink-0" />
                    <button
                        type="button"
                        class="px-2 py-1 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded truncate max-w-[120px]"
                        :title="typeof folder === 'string' ? folder : folder.name"
                        @click="onPathClick(index)"
                    >
                        {{ typeof folder === 'string' ? folder : folder.name }}
                    </button>
                </template>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-2">
                <!-- Search -->
                <div class="relative">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Rechercher..."
                        class="w-40 sm:w-56 pl-8 pr-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        @keyup.enter="onSearch"
                    />
                    <MagnifyingGlassIcon class="absolute left-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                </div>

                <!-- View toggle -->
                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                    <button
                        type="button"
                        :class="[
                            'p-1.5',
                            view === 'grid'
                                ? 'bg-gray-100 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400'
                                : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'
                        ]"
                        @click="view = 'grid'"
                    >
                        <Squares2X2Icon class="h-4 w-4" />
                    </button>
                    <button
                        type="button"
                        :class="[
                            'p-1.5',
                            view === 'list'
                                ? 'bg-gray-100 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400'
                                : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'
                        ]"
                        @click="view = 'list'"
                    >
                        <ListBulletIcon class="h-4 w-4" />
                    </button>
                </div>

                <!-- Upload -->
                <button
                    v-if="allowUpload"
                    type="button"
                    class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors"
                    @click="onUpload"
                >
                    <ArrowUpTrayIcon class="h-4 w-4" />
                    <span class="hidden sm:inline">Téléverser</span>
                </button>

                <!-- New folder -->
                <button
                    v-if="allowCreateFolder"
                    type="button"
                    class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
                    @click="onCreateFolder"
                >
                    <FolderPlusIcon class="h-4 w-4" />
                    <span class="hidden sm:inline">Nouveau dossier</span>
                </button>
            </div>
        </div>

        <!-- Loading overlay -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <svg class="animate-spin h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
        </div>

        <!-- Grid view -->
        <div
            v-else-if="view === 'grid'"
            class="p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3"
            @click.self="clearSelection"
        >
            <div
                v-for="item in sortedItems"
                :key="item.id"
                :class="[
                    'group relative flex flex-col items-center p-3 rounded-lg cursor-pointer transition-colors',
                    isSelected(item)
                        ? 'bg-indigo-100 dark:bg-indigo-900/30 ring-2 ring-indigo-500'
                        : 'hover:bg-gray-100 dark:hover:bg-gray-700/50'
                ]"
                @click="(e) => onItemClick(item, e)"
                @dblclick="() => onItemDoubleClick(item)"
                @contextmenu="(e) => onItemMenu(e, item)"
            >
                <!-- Thumbnail or icon -->
                <div class="relative w-16 h-16 flex items-center justify-center">
                    <img
                        v-if="item.thumbnail"
                        :src="item.thumbnail"
                        :alt="item.name"
                        class="max-w-full max-h-full object-contain rounded"
                    />
                    <component
                        v-else
                        :is="getFileIcon(item)"
                        :class="[
                            'h-12 w-12',
                            item.type === 'folder' ? 'text-amber-500' : 'text-gray-400 dark:text-gray-500'
                        ]"
                    />
                </div>

                <!-- Name -->
                <p class="mt-2 text-sm text-center text-gray-900 dark:text-white truncate w-full" :title="item.name">
                    {{ item.name }}
                </p>

                <!-- Menu button -->
                <button
                    type="button"
                    class="absolute top-1 right-1 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded opacity-0 group-hover:opacity-100 hover:bg-gray-200 dark:hover:bg-gray-600"
                    @click.stop="(e) => onItemMenu(e, item)"
                >
                    <EllipsisVerticalIcon class="h-4 w-4" />
                </button>
            </div>

            <!-- Empty state -->
            <div
                v-if="sortedItems.length === 0"
                class="col-span-full py-12 text-center text-gray-500 dark:text-gray-400"
            >
                <FolderIcon class="h-12 w-12 mx-auto text-gray-300 dark:text-gray-600" />
                <p class="mt-2">Ce dossier est vide</p>
            </div>
        </div>

        <!-- List view -->
        <div v-else class="overflow-x-auto" @click.self="clearSelection">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Nom</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden sm:table-cell">Taille</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Modifié</th>
                        <th class="px-4 py-2 w-10"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr
                        v-for="item in sortedItems"
                        :key="item.id"
                        :class="[
                            'group cursor-pointer transition-colors',
                            isSelected(item)
                                ? 'bg-indigo-50 dark:bg-indigo-900/20'
                                : 'hover:bg-gray-50 dark:hover:bg-gray-700/50'
                        ]"
                        @click="(e) => onItemClick(item, e)"
                        @dblclick="() => onItemDoubleClick(item)"
                        @contextmenu="(e) => onItemMenu(e, item)"
                    >
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-3">
                                <component
                                    :is="getFileIcon(item)"
                                    :class="[
                                        'h-5 w-5 flex-shrink-0',
                                        item.type === 'folder' ? 'text-amber-500' : 'text-gray-400 dark:text-gray-500'
                                    ]"
                                />
                                <span class="text-sm text-gray-900 dark:text-white truncate">{{ item.name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                            {{ item.type === 'folder' ? '-' : formatSize(item.size) }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">
                            {{ item.modified || '-' }}
                        </td>
                        <td class="px-4 py-2">
                            <button
                                type="button"
                                class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded opacity-0 group-hover:opacity-100"
                                @click.stop="(e) => onItemMenu(e, item)"
                            >
                                <EllipsisVerticalIcon class="h-4 w-4" />
                            </button>
                        </td>
                    </tr>

                    <!-- Empty state -->
                    <tr v-if="sortedItems.length === 0">
                        <td colspan="4" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                            <FolderIcon class="h-12 w-12 mx-auto text-gray-300 dark:text-gray-600" />
                            <p class="mt-2">Ce dossier est vide</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Selection info -->
        <div
            v-if="selectedItems.size > 0"
            class="flex items-center justify-between px-4 py-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50"
        >
            <span class="text-sm text-gray-600 dark:text-gray-400">
                {{ selectedItems.size }} élément{{ selectedItems.size > 1 ? 's' : '' }} sélectionné{{ selectedItems.size > 1 ? 's' : '' }}
            </span>
            <button
                type="button"
                class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline"
                @click="clearSelection"
            >
                Désélectionner
            </button>
        </div>
    </div>
</template>
