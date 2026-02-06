<script setup>
import { ref, computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Tabs from '@/Components/Containers/Tabs.vue'
import Tab from '@/Components/Containers/Tab.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Select from '@/Components/Form/Select.vue'
import Checkbox from '@/Components/Form/Checkbox.vue'
import FilePicker from '@/Components/Form/FilePicker.vue'
import Alert from '@/Components/Feedback/Alert.vue'
import ProgressBar from '@/Components/Feedback/ProgressBar.vue'
import Table from '@/Components/Data/Table.vue'
import TableHead from '@/Components/Data/TableHead.vue'
import TableBody from '@/Components/Data/TableBody.vue'
import TableRow from '@/Components/Data/TableRow.vue'
import TableCell from '@/Components/Data/TableCell.vue'
import Spinner from '@/Components/UI/Spinner.vue'
import {
    ArrowDownTrayIcon,
    ArrowUpTrayIcon,
    DocumentArrowDownIcon,
    TableCellsIcon,
    DocumentIcon,
    CheckCircleIcon,
    ExclamationCircleIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    resource: String,
    columns: Array,
    availableResources: Array,
})

// State
const activeTab = ref('export')
const selectedResource = ref(props.resource)
const exportFormat = ref('csv')
const selectedColumns = ref(props.columns.map(c => c.key))

// Import state
const importFile = ref(null)
const importPreview = ref(null)
const importMapping = ref({})
const importing = ref(false)
const importResult = ref(null)

// Export state
const exporting = ref(false)
const exportResult = ref(null)

// Computed
const availableColumns = computed(() => {
    // Get columns for selected resource
    return props.columns
})

const formatOptions = [
    { value: 'csv', label: 'CSV', icon: TableCellsIcon },
    { value: 'excel', label: 'Excel', icon: TableCellsIcon },
    { value: 'pdf', label: 'PDF', icon: DocumentIcon },
]

// Methods
const handleResourceChange = (value) => {
    router.get('/import-export', { resource: value }, {
        preserveState: true,
        only: ['columns'],
    })
}

const toggleColumn = (key) => {
    const index = selectedColumns.value.indexOf(key)
    if (index > -1) {
        selectedColumns.value.splice(index, 1)
    } else {
        selectedColumns.value.push(key)
    }
}

const selectAllColumns = () => {
    selectedColumns.value = props.columns.map(c => c.key)
}

const deselectAllColumns = () => {
    selectedColumns.value = []
}

const doExport = async () => {
    if (selectedColumns.value.length === 0) {
        alert('Sélectionnez au moins une colonne')
        return
    }

    exporting.value = true
    exportResult.value = null

    try {
        const response = await fetch('/import-export/export', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                resource: selectedResource.value,
                format: exportFormat.value,
                columns: selectedColumns.value,
            }),
        })

        const data = await response.json()

        if (data.success) {
            exportResult.value = { success: true, message: 'Export réussi !' }
            // Trigger download
            window.location.href = data.download_url
        } else {
            exportResult.value = { success: false, message: 'Erreur lors de l\'export' }
        }
    } catch (error) {
        exportResult.value = { success: false, message: error.message }
    } finally {
        exporting.value = false
    }
}

const handleFileUpload = async (file) => {
    if (!file) return

    importFile.value = file
    importPreview.value = null
    importResult.value = null

    const formData = new FormData()
    formData.append('file', file)

    try {
        const response = await fetch('/import-export/upload', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData,
        })

        const data = await response.json()

        if (data.success) {
            importPreview.value = data
            // Initialize mapping with auto-detected columns
            const mapping = {}
            data.headers.forEach(header => {
                const matchingCol = props.columns.find(c =>
                    c.label.toLowerCase() === header.toLowerCase() ||
                    c.key.toLowerCase() === header.toLowerCase()
                )
                if (matchingCol) {
                    mapping[matchingCol.key] = header
                }
            })
            importMapping.value = mapping
        }
    } catch (error) {
        alert('Erreur lors du chargement du fichier')
    }
}

const updateMapping = (targetColumn, sourceHeader) => {
    importMapping.value[targetColumn] = sourceHeader
}

const doImport = async () => {
    if (!importPreview.value) return

    importing.value = true
    importResult.value = null

    try {
        const response = await fetch('/import-export/import', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                resource: selectedResource.value,
                file_path: importPreview.value.file_path,
                mapping: importMapping.value,
            }),
        })

        const data = await response.json()

        importResult.value = {
            success: data.errors === 0,
            imported: data.imported,
            errors: data.errors,
            errorDetails: data.error_details,
        }
    } catch (error) {
        importResult.value = { success: false, message: error.message }
    } finally {
        importing.value = false
    }
}

const downloadTemplate = () => {
    window.location.href = `/import-export/template?resource=${selectedResource.value}`
}

const resetImport = () => {
    importFile.value = null
    importPreview.value = null
    importMapping.value = {}
    importResult.value = null
}
</script>

<template>
    <AdminLayout title="Import / Export">
        <PageHeader
            title="Import / Export"
            subtitle="Importer et exporter des données"
        >
            <template #actions>
                <Button
                    variant="secondary"
                    :icon-left="DocumentArrowDownIcon"
                    @click="downloadTemplate"
                >
                    Template CSV
                </Button>
            </template>
        </PageHeader>

        <!-- Resource selector -->
        <Card class="mb-6">
            <div class="p-4">
                <FormGroup label="Ressource" class="max-w-xs">
                    <Select
                        v-model="selectedResource"
                        :options="availableResources"
                        @update:modelValue="handleResourceChange"
                    />
                </FormGroup>
            </div>
        </Card>

        <Tabs v-model="activeTab">
            <!-- Export Tab -->
            <Tab name="export" label="Export">
                <template #icon>
                    <ArrowDownTrayIcon class="h-5 w-5" />
                </template>

                <Card>
                    <div class="p-6 space-y-6">
                        <!-- Format selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-3">
                                Format d'export
                            </label>
                            <div class="flex gap-3">
                                <button
                                    v-for="format in formatOptions"
                                    :key="format.value"
                                    :class="[
                                        'flex items-center gap-2 px-4 py-3 rounded-lg border-2 transition-colors',
                                        exportFormat === format.value
                                            ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400'
                                            : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'
                                    ]"
                                    @click="exportFormat = format.value"
                                >
                                    <component :is="format.icon" class="h-5 w-5" />
                                    <span class="font-medium">{{ format.label }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Column selection -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Colonnes à exporter
                                </label>
                                <div class="flex gap-2">
                                    <button
                                        class="text-sm text-primary-600 hover:text-primary-700"
                                        @click="selectAllColumns"
                                    >
                                        Tout sélectionner
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <button
                                        class="text-sm text-gray-600 hover:text-gray-700"
                                        @click="deselectAllColumns"
                                    >
                                        Tout désélectionner
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                <div
                                    v-for="column in availableColumns"
                                    :key="column.key"
                                    :class="[
                                        'flex items-center gap-2 p-3 rounded-lg border cursor-pointer transition-colors',
                                        selectedColumns.includes(column.key)
                                            ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20'
                                            : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800'
                                    ]"
                                    @click="toggleColumn(column.key)"
                                >
                                    <Checkbox
                                        :model-value="selectedColumns.includes(column.key)"
                                        @update:modelValue="toggleColumn(column.key)"
                                    />
                                    <span class="text-sm">{{ column.label }}</span>
                                    <Badge v-if="column.required" variant="danger" size="sm">
                                        Requis
                                    </Badge>
                                </div>
                            </div>
                        </div>

                        <!-- Export result -->
                        <Alert
                            v-if="exportResult"
                            :variant="exportResult.success ? 'success' : 'danger'"
                            dismissible
                            @close="exportResult = null"
                        >
                            {{ exportResult.message }}
                        </Alert>

                        <!-- Export button -->
                        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                            <Button
                                variant="primary"
                                :icon-left="ArrowDownTrayIcon"
                                :loading="exporting"
                                :disabled="selectedColumns.length === 0"
                                @click="doExport"
                            >
                                Exporter
                            </Button>
                        </div>
                    </div>
                </Card>
            </Tab>

            <!-- Import Tab -->
            <Tab name="import" label="Import">
                <template #icon>
                    <ArrowUpTrayIcon class="h-5 w-5" />
                </template>

                <Card>
                    <div class="p-6 space-y-6">
                        <!-- File upload -->
                        <div v-if="!importPreview">
                            <FormGroup label="Fichier CSV à importer">
                                <FilePicker
                                    accept=".csv"
                                    @update:modelValue="handleFileUpload"
                                />
                                <p class="mt-2 text-sm text-gray-500">
                                    Fichier CSV uniquement. Taille max: 10MB
                                </p>
                            </FormGroup>
                        </div>

                        <!-- Preview and mapping -->
                        <template v-else>
                            <!-- File info -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <DocumentIcon class="h-8 w-8 text-gray-400" />
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ importFile?.name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ importPreview.total_rows }} lignes détectées
                                        </p>
                                    </div>
                                </div>
                                <Button variant="secondary" size="sm" @click="resetImport">
                                    Changer de fichier
                                </Button>
                            </div>

                            <!-- Column mapping -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                                    Mapping des colonnes
                                </h3>
                                <div class="space-y-3">
                                    <div
                                        v-for="column in columns"
                                        :key="column.key"
                                        class="flex items-center gap-4"
                                    >
                                        <div class="w-48">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ column.label }}
                                            </span>
                                            <Badge v-if="column.required" variant="danger" size="sm" class="ml-2">
                                                Requis
                                            </Badge>
                                        </div>
                                        <ArrowDownTrayIcon class="h-4 w-4 text-gray-400 rotate-90" />
                                        <Select
                                            :model-value="importMapping[column.key] || ''"
                                            :options="[
                                                { value: '', label: '-- Non mappé --' },
                                                ...importPreview.headers.map(h => ({ value: h, label: h }))
                                            ]"
                                            class="w-64"
                                            @update:modelValue="updateMapping(column.key, $event)"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Preview table -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                                    Aperçu des données ({{ importPreview.preview.length }} premières lignes)
                                </h3>
                                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <Table>
                                        <TableHead>
                                            <TableRow>
                                                <TableCell
                                                    v-for="header in importPreview.headers"
                                                    :key="header"
                                                    header
                                                >
                                                    {{ header }}
                                                </TableCell>
                                            </TableRow>
                                        </TableHead>
                                        <TableBody>
                                            <TableRow
                                                v-for="(row, index) in importPreview.preview"
                                                :key="index"
                                            >
                                                <TableCell
                                                    v-for="header in importPreview.headers"
                                                    :key="header"
                                                >
                                                    {{ row[header] }}
                                                </TableCell>
                                            </TableRow>
                                        </TableBody>
                                    </Table>
                                </div>
                            </div>

                            <!-- Import result -->
                            <Alert
                                v-if="importResult"
                                :variant="importResult.success ? 'success' : 'warning'"
                            >
                                <template #title>
                                    {{ importResult.success ? 'Import terminé' : 'Import avec erreurs' }}
                                </template>
                                <div class="flex items-center gap-4 mt-2">
                                    <div class="flex items-center gap-1 text-green-600">
                                        <CheckCircleIcon class="h-5 w-5" />
                                        <span>{{ importResult.imported }} importés</span>
                                    </div>
                                    <div v-if="importResult.errors > 0" class="flex items-center gap-1 text-red-600">
                                        <ExclamationCircleIcon class="h-5 w-5" />
                                        <span>{{ importResult.errors }} erreurs</span>
                                    </div>
                                </div>

                                <!-- Error details -->
                                <div
                                    v-if="importResult.errorDetails?.length"
                                    class="mt-4 max-h-48 overflow-y-auto"
                                >
                                    <p class="text-sm font-medium mb-2">Détail des erreurs :</p>
                                    <ul class="text-sm space-y-1">
                                        <li
                                            v-for="(error, i) in importResult.errorDetails"
                                            :key="i"
                                            class="text-red-600"
                                        >
                                            Ligne {{ error.row }}: {{ Object.values(error.errors).flat().join(', ') }}
                                        </li>
                                    </ul>
                                </div>
                            </Alert>
                        </template>

                        <!-- Import button -->
                        <div
                            v-if="importPreview"
                            class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700"
                        >
                            <Button
                                variant="primary"
                                :icon-left="ArrowUpTrayIcon"
                                :loading="importing"
                                @click="doImport"
                            >
                                Importer
                            </Button>
                        </div>
                    </div>
                </Card>
            </Tab>
        </Tabs>
    </AdminLayout>
</template>
