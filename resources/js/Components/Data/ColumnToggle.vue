<script setup>
import { ref } from 'vue'
import { ViewColumnsIcon } from '@heroicons/vue/20/solid'
import Dropdown from '@/Components/Overlays/Dropdown.vue'

const props = defineProps({
    columns: {
        type: Array,
        required: true
        // [{ key: 'name', label: 'Nom', visible: true }, ...]
    },
    modelValue: {
        type: Array,
        default: () => []
        // Array of visible column keys
    }
})

const emit = defineEmits(['update:modelValue'])

const isVisible = (key) => {
    return props.modelValue.length === 0 || props.modelValue.includes(key)
}

const toggleColumn = (key) => {
    let newValue = [...props.modelValue]

    // If no columns specified, all are visible by default
    if (newValue.length === 0) {
        newValue = props.columns.map(c => c.key)
    }

    const index = newValue.indexOf(key)
    if (index === -1) {
        newValue.push(key)
    } else {
        // Don't allow hiding all columns
        if (newValue.length > 1) {
            newValue.splice(index, 1)
        }
    }

    emit('update:modelValue', newValue)
}

const showAll = () => {
    emit('update:modelValue', props.columns.map(c => c.key))
}
</script>

<template>
    <Dropdown align="right">
        <template #trigger>
            <button
                type="button"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
            >
                <ViewColumnsIcon class="h-4 w-4" />
                <span>Colonnes</span>
            </button>
        </template>

        <div class="p-2 w-48">
            <div class="text-xs font-medium text-gray-500 uppercase px-2 py-1">
                Afficher les colonnes
            </div>

            <div class="mt-1 space-y-1">
                <label
                    v-for="column in columns"
                    :key="column.key"
                    class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-100 cursor-pointer"
                >
                    <input
                        type="checkbox"
                        :checked="isVisible(column.key)"
                        class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                        @change="toggleColumn(column.key)"
                    />
                    <span class="text-sm text-gray-700">{{ column.label }}</span>
                </label>
            </div>

            <div class="border-t border-gray-200 mt-2 pt-2">
                <button
                    type="button"
                    class="w-full text-left px-2 py-1.5 text-sm text-primary-600 hover:bg-primary-50 rounded"
                    @click="showAll"
                >
                    Afficher tout
                </button>
            </div>
        </div>
    </Dropdown>
</template>
