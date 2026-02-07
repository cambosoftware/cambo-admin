<script setup>
import { computed } from 'vue'

const props = defineProps({
    selectedCount: {
        type: Number,
        required: true
    },
    totalCount: {
        type: Number,
        required: true
    },
    disabled: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['select-all', 'clear-all'])

const isAllSelected = computed(() =>
    props.totalCount > 0 && props.selectedCount === props.totalCount
)

const isIndeterminate = computed(() =>
    props.selectedCount > 0 && props.selectedCount < props.totalCount
)

const handleChange = (event) => {
    if (event.target.checked) {
        emit('select-all')
    } else {
        emit('clear-all')
    }
}
</script>

<template>
    <div class="flex items-center">
        <input
            type="checkbox"
            :checked="isAllSelected"
            :indeterminate="isIndeterminate"
            :disabled="disabled || totalCount === 0"
            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 disabled:opacity-50"
            @change="handleChange"
        />
    </div>
</template>
