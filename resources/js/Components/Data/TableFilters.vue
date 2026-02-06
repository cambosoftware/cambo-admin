<script setup>
import { computed } from 'vue'
import { FunnelIcon, XMarkIcon } from '@heroicons/vue/20/solid'
import Badge from '@/Components/UI/Badge.vue'

const props = defineProps({
    filters: {
        type: Object,
        default: () => ({})
        // { status: { label: 'Statut', options: [...], value: null }, ... }
    },
    modelValue: {
        type: Object,
        default: () => ({})
        // { status: 'active', role: 'admin' }
    }
})

const emit = defineEmits(['update:modelValue', 'filter'])

const activeFilters = computed(() => {
    return Object.entries(props.modelValue)
        .filter(([_, value]) => value !== null && value !== '' && value !== undefined)
        .map(([key, value]) => {
            const filter = props.filters[key]
            const option = filter?.options?.find(o =>
                (typeof o === 'object' ? o.value : o) === value
            )
            return {
                key,
                label: filter?.label || key,
                value,
                displayValue: typeof option === 'object' ? option.label : option || value
            }
        })
})

const hasActiveFilters = computed(() => activeFilters.value.length > 0)

const updateFilter = (key, value) => {
    const newFilters = { ...props.modelValue }
    if (value === '' || value === null) {
        delete newFilters[key]
    } else {
        newFilters[key] = value
    }
    emit('update:modelValue', newFilters)
    emit('filter', newFilters)
}

const removeFilter = (key) => {
    updateFilter(key, null)
}

const clearAll = () => {
    emit('update:modelValue', {})
    emit('filter', {})
}
</script>

<template>
    <div class="space-y-3">
        <!-- Filter dropdowns -->
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2 text-gray-500">
                <FunnelIcon class="h-4 w-4" />
                <span class="text-sm font-medium">Filtres</span>
            </div>

            <div
                v-for="(filter, key) in filters"
                :key="key"
                class="relative"
            >
                <select
                    :value="modelValue[key] || ''"
                    class="text-sm rounded-lg border-gray-300 py-1.5 pr-8 focus:border-primary-500 focus:ring-primary-500"
                    @change="updateFilter(key, $event.target.value)"
                >
                    <option value="">{{ filter.label }}</option>
                    <option
                        v-for="option in filter.options"
                        :key="typeof option === 'object' ? option.value : option"
                        :value="typeof option === 'object' ? option.value : option"
                    >
                        {{ typeof option === 'object' ? option.label : option }}
                    </option>
                </select>
            </div>

            <button
                v-if="hasActiveFilters"
                type="button"
                class="text-sm text-gray-500 hover:text-gray-700"
                @click="clearAll"
            >
                Effacer tout
            </button>
        </div>

        <!-- Active filters badges -->
        <div v-if="hasActiveFilters" class="flex flex-wrap gap-2">
            <Badge
                v-for="filter in activeFilters"
                :key="filter.key"
                variant="primary"
                removable
                @remove="removeFilter(filter.key)"
            >
                {{ filter.label }}: {{ filter.displayValue }}
            </Badge>
        </div>
    </div>
</template>
