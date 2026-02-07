<script setup>
import { ArrowDownTrayIcon, DocumentTextIcon, TableCellsIcon, DocumentIcon } from '@heroicons/vue/20/solid'
import Dropdown from '@/Components/Overlays/Dropdown.vue'
import DropdownItem from '@/Components/Overlays/DropdownItem.vue'
import DropdownDivider from '@/Components/Overlays/DropdownDivider.vue'

const props = defineProps({
    formats: {
        type: Array,
        default: () => ['csv', 'excel', 'pdf']
    },
    loading: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    selectedCount: {
        type: Number,
        default: 0
    }
})

const emit = defineEmits(['export'])

const formatIcons = {
    csv: DocumentTextIcon,
    excel: TableCellsIcon,
    pdf: DocumentIcon
}

const formatLabels = {
    csv: 'CSV',
    excel: 'Excel',
    pdf: 'PDF'
}

const handleExport = (format, selection = false) => {
    emit('export', { format, selection })
}
</script>

<template>
    <Dropdown align="right" :disabled="disabled || loading">
        <template #trigger>
            <button
                type="button"
                :disabled="disabled || loading"
                :class="[
                    'inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg',
                    'bg-white border border-gray-300 text-gray-700',
                    'hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2',
                    'disabled:opacity-50 disabled:cursor-not-allowed'
                ]"
            >
                <svg
                    v-if="loading"
                    class="animate-spin h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <ArrowDownTrayIcon v-else class="h-4 w-4" />
                <span>Exporter</span>
            </button>
        </template>

        <div class="py-1 min-w-40">
            <div class="px-3 py-1.5 text-xs font-medium text-gray-500 uppercase">
                Exporter tout
            </div>
            <DropdownItem
                v-for="format in formats"
                :key="format"
                :icon="formatIcons[format]"
                @click="handleExport(format, false)"
            >
                {{ formatLabels[format] }}
            </DropdownItem>

            <template v-if="selectedCount > 0">
                <DropdownDivider />
                <div class="px-3 py-1.5 text-xs font-medium text-gray-500 uppercase">
                    Exporter la sélection ({{ selectedCount }})
                </div>
                <DropdownItem
                    v-for="format in formats"
                    :key="`selection-${format}`"
                    :icon="formatIcons[format]"
                    @click="handleExport(format, true)"
                >
                    {{ formatLabels[format] }}
                </DropdownItem>
            </template>
        </div>
    </Dropdown>
</template>
