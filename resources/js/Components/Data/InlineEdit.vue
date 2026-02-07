<script setup>
import { ref, watch, nextTick } from 'vue'
import { PencilIcon, CheckIcon, XMarkIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    type: {
        type: String,
        default: 'text',
        validator: (v) => ['text', 'number', 'email', 'url', 'select', 'textarea'].includes(v)
    },
    options: {
        type: Array,
        default: () => []
        // For select type: [{ value: 'a', label: 'Option A' }, ...]
    },
    placeholder: {
        type: String,
        default: 'Cliquez pour modifier'
    },
    emptyText: {
        type: String,
        default: '-'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    },
    saveOnBlur: {
        type: Boolean,
        default: true
    },
    showButtons: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['update:modelValue', 'save', 'cancel'])

const isEditing = ref(false)
const localValue = ref(props.modelValue)
const inputRef = ref(null)

watch(() => props.modelValue, (val) => {
    localValue.value = val
})

const startEdit = () => {
    if (props.disabled || props.loading) return
    isEditing.value = true
    localValue.value = props.modelValue
    nextTick(() => {
        inputRef.value?.focus()
        inputRef.value?.select?.()
    })
}

const save = () => {
    if (localValue.value !== props.modelValue) {
        emit('update:modelValue', localValue.value)
        emit('save', localValue.value)
    }
    isEditing.value = false
}

const cancel = () => {
    localValue.value = props.modelValue
    isEditing.value = false
    emit('cancel')
}

const handleBlur = () => {
    if (props.saveOnBlur && !props.showButtons) {
        save()
    }
}

const handleKeydown = (event) => {
    if (event.key === 'Enter' && props.type !== 'textarea') {
        save()
    } else if (event.key === 'Escape') {
        cancel()
    }
}

const displayValue = () => {
    if (props.type === 'select' && props.options.length) {
        const option = props.options.find(o =>
            (typeof o === 'object' ? o.value : o) === props.modelValue
        )
        return typeof option === 'object' ? option.label : option || props.modelValue
    }
    return props.modelValue
}
</script>

<template>
    <div class="inline-edit">
        <!-- Edit mode -->
        <div v-if="isEditing" class="flex items-center gap-1">
            <select
                v-if="type === 'select'"
                ref="inputRef"
                v-model="localValue"
                class="text-sm rounded border-gray-300 py-1 px-2 focus:border-indigo-500 focus:ring-indigo-500"
                @blur="handleBlur"
                @keydown="handleKeydown"
            >
                <option
                    v-for="option in options"
                    :key="typeof option === 'object' ? option.value : option"
                    :value="typeof option === 'object' ? option.value : option"
                >
                    {{ typeof option === 'object' ? option.label : option }}
                </option>
            </select>

            <textarea
                v-else-if="type === 'textarea'"
                ref="inputRef"
                v-model="localValue"
                rows="2"
                class="text-sm rounded border-gray-300 py-1 px-2 focus:border-indigo-500 focus:ring-indigo-500"
                @blur="handleBlur"
                @keydown="handleKeydown"
            />

            <input
                v-else
                ref="inputRef"
                v-model="localValue"
                :type="type"
                class="text-sm rounded border-gray-300 py-1 px-2 focus:border-indigo-500 focus:ring-indigo-500"
                @blur="handleBlur"
                @keydown="handleKeydown"
            />

            <template v-if="showButtons">
                <button
                    type="button"
                    class="p-1 text-emerald-600 hover:bg-emerald-50 rounded"
                    @click="save"
                >
                    <CheckIcon class="h-4 w-4" />
                </button>
                <button
                    type="button"
                    class="p-1 text-gray-400 hover:bg-gray-100 rounded"
                    @click="cancel"
                >
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </template>
        </div>

        <!-- Display mode -->
        <div
            v-else
            :class="[
                'group inline-flex items-center gap-1 cursor-pointer rounded px-1 -mx-1',
                disabled ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-100'
            ]"
            @click="startEdit"
        >
            <span v-if="loading" class="text-gray-400">
                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
            <template v-else>
                <span :class="!modelValue ? 'text-gray-400' : ''">
                    {{ displayValue() || emptyText }}
                </span>
                <PencilIcon
                    v-if="!disabled"
                    class="h-3 w-3 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"
                />
            </template>
        </div>
    </div>
</template>
