<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'

// Sub-components
import Table from './Table.vue'
import TableHead from './TableHead.vue'
import TableBody from './TableBody.vue'
import TableRow from './TableRow.vue'
import TableCell from './TableCell.vue'
import SortableHeader from './SortableHeader.vue'
import Pagination from './Pagination.vue'
import TableSearch from './TableSearch.vue'
import TableFilters from './TableFilters.vue'
import ColumnToggle from './ColumnToggle.vue'
import ExportDropdown from './ExportDropdown.vue'
import BulkActions from './BulkActions.vue'
import RowActions from './RowActions.vue'
import SelectAllCheckbox from './SelectAllCheckbox.vue'
import PerPageSelect from './PerPageSelect.vue'

// UI components
import Card from '@/Components/Containers/Card.vue'
import EmptyState from '@/Components/Feedback/EmptyState.vue'

const props = defineProps({
    // Data from QueryBuilder
    resource: {
        type: Object,
        required: true
        // { data: [], meta: { columns, filters, search, sort, pagination, actions, bulk_actions } }
    },
    // Manual columns definition (overrides meta.columns)
    columns: {
        type: Array,
        default: null
    },
    // Features toggles
    searchable: {
        type: Boolean,
        default: true
    },
    filterable: {
        type: Boolean,
        default: true
    },
    sortable: {
        type: Boolean,
        default: true
    },
    selectable: {
        type: Boolean,
        default: true
    },
    exportable: {
        type: Boolean,
        default: false
    },
    // UI options
    striped: {
        type: Boolean,
        default: false
    },
    hoverable: {
        type: Boolean,
        default: true
    },
    bordered: {
        type: Boolean,
        default: false
    },
    compact: {
        type: Boolean,
        default: false
    },
    stickyHeader: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    },
    // Empty state
    emptyTitle: {
        type: String,
        default: 'Aucune donnée'
    },
    emptyDescription: {
        type: String,
        default: 'Aucun élément ne correspond à vos critères.'
    },
    // Row key
    rowKey: {
        type: String,
        default: 'id'
    },
    // Custom row class
    rowClass: {
        type: Function,
        default: null
    }
})

const emit = defineEmits([
    'search',
    'filter',
    'sort',
    'page-change',
    'per-page-change',
    'select',
    'select-all',
    'row-click',
    'row-action',
    'bulk-action',
    'export'
])

// State
const selectedRows = ref([])
const visibleColumns = ref([])
const search = ref(props.resource.meta?.search?.value || '')
const filters = ref({})
const currentSort = ref(props.resource.meta?.sort?.column || null)
const currentDirection = ref(props.resource.meta?.sort?.direction || 'asc')

// Computed
const data = computed(() => props.resource.data || [])
const meta = computed(() => props.resource.meta || {})

const computedColumns = computed(() => {
    const cols = props.columns || meta.value.columns || []
    if (visibleColumns.value.length === 0) return cols
    return cols.filter(col => visibleColumns.value.includes(col.key))
})

const hasFilters = computed(() =>
    props.filterable && meta.value.filters && Object.keys(meta.value.filters).length > 0
)

const hasActions = computed(() =>
    meta.value.actions && meta.value.actions.length > 0
)

const hasBulkActions = computed(() =>
    meta.value.bulk_actions && meta.value.bulk_actions.length > 0
)

const pagination = computed(() => meta.value.pagination || null)

const isEmpty = computed(() => data.value.length === 0)

const allSelected = computed(() =>
    data.value.length > 0 && selectedRows.value.length === data.value.length
)

// Methods
const handleSearch = (value) => {
    search.value = value
    emit('search', value)
    // If using Inertia, reload with search param
    reloadData({ search: value, page: 1 })
}

const handleFilter = (newFilters) => {
    filters.value = newFilters
    emit('filter', newFilters)
    reloadData({ filter: newFilters, page: 1 })
}

const handleSort = ({ column, direction }) => {
    currentSort.value = column
    currentDirection.value = direction
    emit('sort', { column, direction })
    const sortParam = direction === 'desc' ? `-${column}` : column
    reloadData({ sort: sortParam })
}

const handlePageChange = (page) => {
    emit('page-change', page)
    reloadData({ page })
}

const handlePerPageChange = (perPage) => {
    emit('per-page-change', perPage)
    reloadData({ per_page: perPage, page: 1 })
}

const handleRowClick = (row, index) => {
    emit('row-click', { row, index })
}

const handleRowAction = (payload) => {
    emit('row-action', payload)
}

const handleBulkAction = (action) => {
    emit('bulk-action', { action, rows: selectedRows.value })
}

const handleExport = (payload) => {
    emit('export', {
        ...payload,
        rows: payload.selection ? selectedRows.value : null
    })
}

const toggleRowSelection = (row) => {
    const key = row[props.rowKey]
    const index = selectedRows.value.findIndex(r => r[props.rowKey] === key)
    if (index === -1) {
        selectedRows.value.push(row)
    } else {
        selectedRows.value.splice(index, 1)
    }
    emit('select', selectedRows.value)
}

const isRowSelected = (row) => {
    return selectedRows.value.some(r => r[props.rowKey] === row[props.rowKey])
}

const selectAll = () => {
    selectedRows.value = [...data.value]
    emit('select-all', selectedRows.value)
}

const clearSelection = () => {
    selectedRows.value = []
    emit('select', selectedRows.value)
}

const reloadData = (params) => {
    router.reload({
        data: params,
        preserveState: true,
        preserveScroll: true
    })
}

// Get cell value from row using dot notation
const getCellValue = (row, key) => {
    return key.split('.').reduce((obj, k) => obj?.[k], row)
}
</script>

<template>
    <div class="data-table space-y-4">
        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <!-- Left side: Search -->
            <div class="w-full sm:w-auto sm:min-w-64">
                <TableSearch
                    v-if="searchable"
                    v-model="search"
                    @search="handleSearch"
                />
            </div>

            <!-- Right side: Actions -->
            <div class="flex flex-wrap items-center gap-2">
                <slot name="toolbar-actions" />

                <ColumnToggle
                    v-if="computedColumns.length > 0"
                    :columns="columns || meta.columns || []"
                    v-model="visibleColumns"
                />

                <ExportDropdown
                    v-if="exportable"
                    :formats="meta.exports || ['csv', 'excel']"
                    :selected-count="selectedRows.length"
                    @export="handleExport"
                />
            </div>
        </div>

        <!-- Filters -->
        <TableFilters
            v-if="hasFilters"
            :filters="meta.filters"
            v-model="filters"
            @filter="handleFilter"
        />

        <!-- Bulk actions bar -->
        <BulkActions
            v-if="selectable && hasBulkActions"
            :selected-count="selectedRows.length"
            :total-count="data.length"
            :actions="meta.bulk_actions"
            @action="handleBulkAction"
            @select-all="selectAll"
            @clear-selection="clearSelection"
        />

        <!-- Table -->
        <Card :padding="false" bordered>
            <Table
                :striped="striped"
                :hoverable="hoverable"
                :bordered="bordered"
                :loading="loading"
            >
                <TableHead :sticky="stickyHeader">
                    <tr>
                        <!-- Selection checkbox -->
                        <TableCell v-if="selectable" header :compact="compact" width="40px">
                            <SelectAllCheckbox
                                :selected-count="selectedRows.length"
                                :total-count="data.length"
                                @select-all="selectAll"
                                @clear-all="clearSelection"
                            />
                        </TableCell>

                        <!-- Column headers -->
                        <template v-for="column in computedColumns" :key="column.key">
                            <SortableHeader
                                v-if="sortable && column.sortable !== false"
                                :column="column.key"
                                :current-sort="currentSort"
                                :current-direction="currentDirection"
                                :align="column.align || 'left'"
                                :compact="compact"
                                @sort="handleSort"
                            >
                                {{ column.label }}
                            </SortableHeader>
                            <TableCell
                                v-else
                                header
                                :align="column.align || 'left'"
                                :compact="compact"
                                :width="column.width"
                            >
                                {{ column.label }}
                            </TableCell>
                        </template>

                        <!-- Actions column -->
                        <TableCell v-if="hasActions" header :compact="compact" width="100px" align="right">
                            Actions
                        </TableCell>
                    </tr>
                </TableHead>

                <TableBody :striped="striped" :hoverable="hoverable">
                    <!-- Empty state -->
                    <tr v-if="isEmpty && !loading">
                        <td :colspan="computedColumns.length + (selectable ? 1 : 0) + (hasActions ? 1 : 0)">
                            <EmptyState
                                :title="emptyTitle"
                                :description="emptyDescription"
                                class="py-12"
                            />
                        </td>
                    </tr>

                    <!-- Data rows -->
                    <TableRow
                        v-for="(row, index) in data"
                        :key="row[rowKey] || index"
                        :selected="isRowSelected(row)"
                        :clickable="!!$listeners?.['row-click']"
                        :class="rowClass ? rowClass(row, index) : ''"
                        @click="handleRowClick(row, index)"
                    >
                        <!-- Selection checkbox -->
                        <TableCell v-if="selectable" :compact="compact" @click.stop>
                            <input
                                type="checkbox"
                                :checked="isRowSelected(row)"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                @change="toggleRowSelection(row)"
                            />
                        </TableCell>

                        <!-- Data cells -->
                        <TableCell
                            v-for="column in computedColumns"
                            :key="column.key"
                            :align="column.align || 'left'"
                            :compact="compact"
                            :nowrap="column.nowrap"
                        >
                            <slot
                                :name="`cell-${column.key}`"
                                :value="getCellValue(row, column.key)"
                                :row="row"
                                :column="column"
                            >
                                <!-- Custom component -->
                                <component
                                    v-if="column.component"
                                    :is="column.component"
                                    :value="getCellValue(row, column.key)"
                                    :row="row"
                                    v-bind="column.componentProps || {}"
                                />
                                <!-- Default display -->
                                <template v-else>
                                    {{ getCellValue(row, column.key) ?? '-' }}
                                </template>
                            </slot>
                        </TableCell>

                        <!-- Actions cell -->
                        <TableCell v-if="hasActions" :compact="compact" align="right" @click.stop>
                            <RowActions
                                :actions="meta.actions"
                                :row="row"
                                @action="handleRowAction"
                            />
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </Card>

        <!-- Pagination -->
        <Pagination
            v-if="pagination && pagination.total > pagination.per_page"
            :current-page="pagination.current_page"
            :total-pages="pagination.last_page"
            :total-items="pagination.total"
            :per-page="pagination.per_page"
            :per-page-options="pagination.per_page_options || [10, 25, 50, 100]"
            @update:current-page="handlePageChange"
            @update:per-page="handlePerPageChange"
        />
    </div>
</template>
