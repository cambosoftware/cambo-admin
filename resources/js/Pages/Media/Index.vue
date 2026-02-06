<script setup>
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import SearchInput from '@/Components/Form/SearchInput.vue'
import Select from '@/Components/Form/Select.vue'
import Modal from '@/Components/Overlays/Modal.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import Input from '@/Components/Form/Input.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Pagination from '@/Components/Data/Pagination.vue'
import EmptyState from '@/Components/Feedback/EmptyState.vue'
import ProgressBar from '@/Components/Feedback/ProgressBar.vue'
import {
    FolderIcon,
    FolderPlusIcon,
    CloudArrowUpIcon,
    DocumentIcon,
    PhotoIcon,
    VideoCameraIcon,
    MusicalNoteIcon,
    ArchiveBoxIcon,
    TrashIcon,
    PencilIcon,
    ArrowDownTrayIcon,
    EyeIcon,
    HomeIcon,
    ChevronRightIcon,
    Squares2X2Icon,
    ListBulletIcon,
    CheckIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    currentFolder: Object,
    folders: Array,
    files: Object,
    breadcrumb: Array,
    storageStats: Object,
    filters: Object,
})

// State
const viewMode = ref('grid') // 'grid' or 'list'
const selectedFiles = ref([])
const search = ref(props.filters?.search || '')
const typeFilter = ref(props.filters?.type || '')

// Modals
const uploadModal = ref(false)
const newFolderModal = ref(false)
const previewModal = ref(false)
const deleteModal = ref(false)

// Form data
const newFolderName = ref('')
const previewFile = ref(null)
const fileToDelete = ref(null)

// Upload state
const uploading = ref(false)
const uploadProgress = ref(0)
const uploadFiles = ref([])

// Computed
const hasFiles = computed(() => props.files?.data?.length > 0 || props.folders?.length > 0)
const hasSelection = computed(() => selectedFiles.value.length > 0)

// File type icons
const getFileIcon = (type) => {
    const icons = {
        image: PhotoIcon,
        video: VideoCameraIcon,
        audio: MusicalNoteIcon,
        document: DocumentIcon,
        archive: ArchiveBoxIcon,
    }
    return icons[type] || DocumentIcon
}

// File type colors
const getTypeColor = (type) => {
    const colors = {
        image: 'text-green-500 bg-green-100 dark:bg-green-900/30',
        video: 'text-purple-500 bg-purple-100 dark:bg-purple-900/30',
        audio: 'text-pink-500 bg-pink-100 dark:bg-pink-900/30',
        document: 'text-blue-500 bg-blue-100 dark:bg-blue-900/30',
        archive: 'text-amber-500 bg-amber-100 dark:bg-amber-900/30',
    }
    return colors[type] || 'text-gray-500 bg-gray-100 dark:bg-gray-700'
}

// Type filter options
const typeOptions = [
    { value: '', label: 'Tous les types' },
    { value: 'image', label: 'Images' },
    { value: 'video', label: 'Vidéos' },
    { value: 'audio', label: 'Audio' },
    { value: 'document', label: 'Documents' },
    { value: 'archive', label: 'Archives' },
]

// Actions
const navigateToFolder = (folderId) => {
    router.get('/media', { folder: folderId }, { preserveState: true })
}

const applyFilters = () => {
    router.get('/media', {
        folder: props.currentFolder?.id,
        search: search.value || undefined,
        type: typeFilter.value || undefined,
    }, { preserveState: true })
}

const toggleFileSelection = (file) => {
    const index = selectedFiles.value.findIndex(f => f.id === file.id)
    if (index > -1) {
        selectedFiles.value.splice(index, 1)
    } else {
        selectedFiles.value.push(file)
    }
}

const selectAll = () => {
    if (selectedFiles.value.length === props.files.data.length) {
        selectedFiles.value = []
    } else {
        selectedFiles.value = [...props.files.data]
    }
}

const openPreview = (file) => {
    previewFile.value = file
    previewModal.value = true
}

const createFolder = () => {
    router.post('/media/folders', {
        name: newFolderName.value,
        parent_id: props.currentFolder?.id,
    }, {
        onSuccess: () => {
            newFolderModal.value = false
            newFolderName.value = ''
        }
    })
}

const confirmDelete = (file) => {
    fileToDelete.value = file
    deleteModal.value = true
}

const deleteFile = () => {
    if (fileToDelete.value) {
        router.delete(`/media/${fileToDelete.value.id}`, {
            onSuccess: () => {
                deleteModal.value = false
                fileToDelete.value = null
            }
        })
    }
}

const bulkDelete = () => {
    router.post('/media/bulk-delete', {
        ids: selectedFiles.value.map(f => f.id)
    }, {
        onSuccess: () => {
            selectedFiles.value = []
        }
    })
}

// File upload
const handleFileSelect = (event) => {
    uploadFiles.value = Array.from(event.target.files)
    if (uploadFiles.value.length > 0) {
        uploadModal.value = true
    }
}

const startUpload = async () => {
    if (uploadFiles.value.length === 0) return

    uploading.value = true
    uploadProgress.value = 0

    const formData = new FormData()
    uploadFiles.value.forEach(file => {
        formData.append('files[]', file)
    })
    if (props.currentFolder?.id) {
        formData.append('folder_id', props.currentFolder.id)
    }

    try {
        await router.post('/media/upload', formData, {
            forceFormData: true,
            onProgress: (progress) => {
                uploadProgress.value = progress.percentage
            },
            onSuccess: () => {
                uploadModal.value = false
                uploadFiles.value = []
            },
            onFinish: () => {
                uploading.value = false
                uploadProgress.value = 0
            }
        })
    } catch (error) {
        uploading.value = false
    }
}

const removeUploadFile = (index) => {
    uploadFiles.value.splice(index, 1)
}

// Debounced search
let searchTimeout
const onSearchInput = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(applyFilters, 500)
}
</script>

<template>
    <AdminLayout title="Gestionnaire de fichiers">
        <PageHeader
            title="Gestionnaire de fichiers"
            :subtitle="`${storageStats.file_count} fichiers - ${storageStats.total_size_human}`"
        >
            <template #actions>
                <div class="flex items-center gap-2">
                    <!-- View toggle -->
                    <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                        <button
                            :class="['p-2 rounded-md transition-colors', viewMode === 'grid' ? 'bg-white dark:bg-gray-600 shadow-sm' : 'text-gray-500']"
                            @click="viewMode = 'grid'"
                        >
                            <Squares2X2Icon class="h-5 w-5" />
                        </button>
                        <button
                            :class="['p-2 rounded-md transition-colors', viewMode === 'list' ? 'bg-white dark:bg-gray-600 shadow-sm' : 'text-gray-500']"
                            @click="viewMode = 'list'"
                        >
                            <ListBulletIcon class="h-5 w-5" />
                        </button>
                    </div>

                    <Button variant="secondary" :icon-left="FolderPlusIcon" @click="newFolderModal = true">
                        Nouveau dossier
                    </Button>

                    <label class="cursor-pointer">
                        <Button variant="primary" :icon-left="CloudArrowUpIcon" as="span">
                            Uploader
                        </Button>
                        <input
                            type="file"
                            multiple
                            class="hidden"
                            @change="handleFileSelect"
                        />
                    </label>
                </div>
            </template>
        </PageHeader>

        <div class="flex gap-6">
            <!-- Main content -->
            <div class="flex-1">
                <!-- Toolbar -->
                <Card class="mb-4">
                    <div class="p-4 flex items-center gap-4">
                        <!-- Breadcrumb -->
                        <nav class="flex items-center gap-1 text-sm flex-1">
                            <button
                                class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                                @click="navigateToFolder(null)"
                            >
                                <HomeIcon class="h-5 w-5 text-gray-500" />
                            </button>
                            <template v-for="(crumb, index) in breadcrumb" :key="crumb.id">
                                <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                                <button
                                    :class="[
                                        'px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700',
                                        index === breadcrumb.length - 1 ? 'font-medium text-gray-900 dark:text-white' : 'text-gray-500'
                                    ]"
                                    @click="navigateToFolder(crumb.id)"
                                >
                                    {{ crumb.name }}
                                </button>
                            </template>
                        </nav>

                        <!-- Filters -->
                        <SearchInput
                            v-model="search"
                            placeholder="Rechercher..."
                            size="sm"
                            class="w-64"
                            @input="onSearchInput"
                        />

                        <Select
                            v-model="typeFilter"
                            :options="typeOptions"
                            size="sm"
                            class="w-40"
                            @change="applyFilters"
                        />
                    </div>

                    <!-- Selection toolbar -->
                    <div
                        v-if="hasSelection"
                        class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 bg-primary-50 dark:bg-primary-900/20 flex items-center gap-4"
                    >
                        <span class="text-sm text-primary-700 dark:text-primary-300">
                            {{ selectedFiles.length }} fichier(s) sélectionné(s)
                        </span>
                        <Button variant="danger" size="xs" :icon-left="TrashIcon" @click="bulkDelete">
                            Supprimer
                        </Button>
                        <button
                            class="text-sm text-gray-500 hover:text-gray-700"
                            @click="selectedFiles = []"
                        >
                            Annuler
                        </button>
                    </div>
                </Card>

                <!-- Files grid/list -->
                <div v-if="hasFiles">
                    <!-- Grid view -->
                    <div v-if="viewMode === 'grid'" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <!-- Folders -->
                        <div
                            v-for="folder in folders"
                            :key="'folder-' + folder.id"
                            class="group relative bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 cursor-pointer hover:border-primary-500 transition-colors"
                            @click="navigateToFolder(folder.id)"
                        >
                            <FolderIcon :class="['h-12 w-12 mx-auto mb-2', folder.color ? `text-[${folder.color}]` : 'text-amber-500']" />
                            <p class="text-sm font-medium text-center text-gray-900 dark:text-white truncate">
                                {{ folder.name }}
                            </p>
                            <p class="text-xs text-center text-gray-500">
                                {{ folder.files_count }} fichiers
                            </p>
                        </div>

                        <!-- Files -->
                        <div
                            v-for="file in files.data"
                            :key="file.id"
                            :class="[
                                'group relative bg-white dark:bg-gray-800 rounded-xl border overflow-hidden cursor-pointer transition-all',
                                selectedFiles.find(f => f.id === file.id)
                                    ? 'border-primary-500 ring-2 ring-primary-500/20'
                                    : 'border-gray-200 dark:border-gray-700 hover:border-gray-300'
                            ]"
                            @click="toggleFileSelection(file)"
                        >
                            <!-- Selection checkbox -->
                            <div
                                :class="[
                                    'absolute top-2 left-2 z-10 w-5 h-5 rounded border-2 flex items-center justify-center transition-colors',
                                    selectedFiles.find(f => f.id === file.id)
                                        ? 'bg-primary-500 border-primary-500'
                                        : 'bg-white/80 border-gray-300 opacity-0 group-hover:opacity-100'
                                ]"
                            >
                                <CheckIcon v-if="selectedFiles.find(f => f.id === file.id)" class="h-3 w-3 text-white" />
                            </div>

                            <!-- Preview -->
                            <div class="aspect-square bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <img
                                    v-if="file.type === 'image'"
                                    :src="file.thumbnail_url || file.url"
                                    :alt="file.name"
                                    class="w-full h-full object-cover"
                                />
                                <div v-else :class="['p-4 rounded-full', getTypeColor(file.type)]">
                                    <component :is="getFileIcon(file.type)" class="h-8 w-8" />
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="p-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ file.name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ file.human_size }}
                                </p>
                            </div>

                            <!-- Actions (on hover) -->
                            <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button
                                    class="p-1.5 bg-white dark:bg-gray-700 rounded-lg shadow hover:bg-gray-50"
                                    @click.stop="openPreview(file)"
                                >
                                    <EyeIcon class="h-4 w-4 text-gray-600" />
                                </button>
                                <a
                                    :href="`/media/${file.id}/download`"
                                    class="p-1.5 bg-white dark:bg-gray-700 rounded-lg shadow hover:bg-gray-50"
                                    @click.stop
                                >
                                    <ArrowDownTrayIcon class="h-4 w-4 text-gray-600" />
                                </a>
                                <button
                                    class="p-1.5 bg-white dark:bg-gray-700 rounded-lg shadow hover:bg-red-50"
                                    @click.stop="confirmDelete(file)"
                                >
                                    <TrashIcon class="h-4 w-4 text-red-500" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- List view -->
                    <Card v-else>
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="w-10 px-4 py-3">
                                        <input
                                            type="checkbox"
                                            class="rounded"
                                            :checked="selectedFiles.length === files.data.length"
                                            @change="selectAll"
                                        />
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Taille</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="w-24"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Folders -->
                                <tr
                                    v-for="folder in folders"
                                    :key="'folder-' + folder.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer"
                                    @click="navigateToFolder(folder.id)"
                                >
                                    <td class="px-4 py-3"></td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <FolderIcon class="h-8 w-8 text-amber-500" />
                                            <span class="font-medium text-gray-900 dark:text-white">{{ folder.name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">Dossier</td>
                                    <td class="px-4 py-3 text-gray-500">{{ folder.files_count }} fichiers</td>
                                    <td class="px-4 py-3 text-gray-500">-</td>
                                    <td></td>
                                </tr>

                                <!-- Files -->
                                <tr
                                    v-for="file in files.data"
                                    :key="file.id"
                                    :class="[
                                        'hover:bg-gray-50 dark:hover:bg-gray-700/50',
                                        selectedFiles.find(f => f.id === file.id) ? 'bg-primary-50 dark:bg-primary-900/20' : ''
                                    ]"
                                >
                                    <td class="px-4 py-3">
                                        <input
                                            type="checkbox"
                                            class="rounded"
                                            :checked="!!selectedFiles.find(f => f.id === file.id)"
                                            @change="toggleFileSelection(file)"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div :class="['p-2 rounded-lg', getTypeColor(file.type)]">
                                                <component :is="getFileIcon(file.type)" class="h-5 w-5" />
                                            </div>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ file.name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <Badge variant="secondary" size="sm">{{ file.extension }}</Badge>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ file.human_size }}</td>
                                    <td class="px-4 py-3 text-gray-500 text-sm">
                                        {{ new Date(file.created_at).toLocaleDateString('fr-FR') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1">
                                            <button class="p-1.5 hover:bg-gray-100 rounded" @click="openPreview(file)">
                                                <EyeIcon class="h-4 w-4 text-gray-500" />
                                            </button>
                                            <a :href="`/media/${file.id}/download`" class="p-1.5 hover:bg-gray-100 rounded">
                                                <ArrowDownTrayIcon class="h-4 w-4 text-gray-500" />
                                            </a>
                                            <button class="p-1.5 hover:bg-red-50 rounded" @click="confirmDelete(file)">
                                                <TrashIcon class="h-4 w-4 text-red-500" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </Card>

                    <!-- Pagination -->
                    <div v-if="files?.meta?.last_page > 1" class="mt-6">
                        <Pagination :links="files.links" :meta="files.meta" />
                    </div>
                </div>

                <!-- Empty state -->
                <EmptyState
                    v-else
                    :icon="FolderIcon"
                    title="Dossier vide"
                    description="Ce dossier ne contient aucun fichier."
                >
                    <template #actions>
                        <label class="cursor-pointer">
                            <Button variant="primary" :icon-left="CloudArrowUpIcon" as="span">
                                Uploader des fichiers
                            </Button>
                            <input type="file" multiple class="hidden" @change="handleFileSelect" />
                        </label>
                    </template>
                </EmptyState>
            </div>
        </div>

        <!-- Upload Modal -->
        <Modal :show="uploadModal" title="Uploader des fichiers" @close="uploadModal = false">
            <div class="space-y-4">
                <div v-if="!uploading" class="space-y-2">
                    <div
                        v-for="(file, index) in uploadFiles"
                        :key="index"
                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                    >
                        <div class="flex items-center gap-3">
                            <component :is="getFileIcon(file.type?.split('/')[0])" class="h-8 w-8 text-gray-400" />
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ file.name }}</p>
                                <p class="text-xs text-gray-500">{{ (file.size / 1024 / 1024).toFixed(2) }} Mo</p>
                            </div>
                        </div>
                        <button @click="removeUploadFile(index)">
                            <XMarkIcon class="h-5 w-5 text-gray-400 hover:text-red-500" />
                        </button>
                    </div>
                </div>

                <div v-else>
                    <p class="text-sm text-gray-600 mb-2">Upload en cours...</p>
                    <ProgressBar :value="uploadProgress" :max="100" color="primary" />
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-3">
                    <Button variant="secondary" @click="uploadModal = false" :disabled="uploading">
                        Annuler
                    </Button>
                    <Button variant="primary" @click="startUpload" :disabled="uploading || uploadFiles.length === 0">
                        {{ uploading ? 'Upload...' : 'Uploader' }}
                    </Button>
                </div>
            </template>
        </Modal>

        <!-- New Folder Modal -->
        <Modal :show="newFolderModal" title="Nouveau dossier" @close="newFolderModal = false">
            <FormGroup label="Nom du dossier">
                <Input v-model="newFolderName" placeholder="Mon dossier" />
            </FormGroup>

            <template #footer>
                <div class="flex justify-end gap-3">
                    <Button variant="secondary" @click="newFolderModal = false">Annuler</Button>
                    <Button variant="primary" @click="createFolder" :disabled="!newFolderName">Créer</Button>
                </div>
            </template>
        </Modal>

        <!-- Preview Modal -->
        <Modal :show="previewModal" :title="previewFile?.name" size="lg" @close="previewModal = false">
            <div v-if="previewFile" class="space-y-4">
                <!-- Image preview -->
                <div v-if="previewFile.type === 'image'" class="flex justify-center">
                    <img :src="previewFile.url" :alt="previewFile.name" class="max-h-[60vh] rounded-lg" />
                </div>

                <!-- Video preview -->
                <div v-else-if="previewFile.type === 'video'" class="flex justify-center">
                    <video :src="previewFile.url" controls class="max-h-[60vh] rounded-lg" />
                </div>

                <!-- Audio preview -->
                <div v-else-if="previewFile.type === 'audio'" class="flex justify-center py-8">
                    <audio :src="previewFile.url" controls class="w-full" />
                </div>

                <!-- Other files -->
                <div v-else class="py-8 text-center">
                    <component :is="getFileIcon(previewFile.type)" class="h-24 w-24 mx-auto text-gray-400 mb-4" />
                    <p class="text-gray-500">Aperçu non disponible</p>
                </div>

                <!-- File info -->
                <div class="grid grid-cols-2 gap-4 text-sm border-t pt-4">
                    <div>
                        <span class="text-gray-500">Taille:</span>
                        <span class="ml-2 text-gray-900 dark:text-white">{{ previewFile.human_size }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Type:</span>
                        <span class="ml-2 text-gray-900 dark:text-white">{{ previewFile.mime_type }}</span>
                    </div>
                    <div v-if="previewFile.metadata?.width">
                        <span class="text-gray-500">Dimensions:</span>
                        <span class="ml-2 text-gray-900 dark:text-white">
                            {{ previewFile.metadata.width }} x {{ previewFile.metadata.height }}
                        </span>
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-between">
                    <a :href="`/media/${previewFile?.id}/download`">
                        <Button variant="secondary" :icon-left="ArrowDownTrayIcon">
                            Télécharger
                        </Button>
                    </a>
                    <Button variant="primary" @click="previewModal = false">Fermer</Button>
                </div>
            </template>
        </Modal>

        <!-- Delete Confirmation -->
        <ConfirmModal
            :show="deleteModal"
            title="Supprimer le fichier"
            :message="`Êtes-vous sûr de vouloir supprimer '${fileToDelete?.name}' ?`"
            confirm-text="Supprimer"
            variant="danger"
            @close="deleteModal = false"
            @confirm="deleteFile"
        />
    </AdminLayout>
</template>
