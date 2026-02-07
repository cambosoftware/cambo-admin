<script setup>
import { computed, ref } from 'vue'
import { DocumentIcon, XMarkIcon, ArrowUpTrayIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: [File, Array, null],
        default: null
    },
    accept: {
        type: String,
        default: null
    },
    multiple: {
        type: Boolean,
        default: false
    },
    maxSize: {
        type: Number,
        default: null // in MB
    },
    maxFiles: {
        type: Number,
        default: null
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    },
    disabled: {
        type: Boolean,
        default: false
    },
    error: {
        type: [String, Boolean],
        default: null
    },
    placeholder: {
        type: String,
        default: 'Choisir un fichier'
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'error'])

const inputRef = ref(null)
const hasError = computed(() => !!props.error)

const files = computed(() => {
    if (!props.modelValue) return []
    if (Array.isArray(props.modelValue)) return props.modelValue
    return [props.modelValue]
})

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'px-2.5 py-1.5 text-xs',
        md: 'px-3 py-2 text-sm',
        lg: 'px-4 py-2.5 text-base'
    }
    return sizes[props.size]
})

const iconSizes = computed(() => {
    const sizes = { sm: 'h-3.5 w-3.5', md: 'h-4 w-4', lg: 'h-5 w-5' }
    return sizes[props.size]
})

function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' o'
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' Ko'
    return (bytes / (1024 * 1024)).toFixed(1) + ' Mo'
}

function openPicker() {
    if (props.disabled) return
    inputRef.value?.click()
}

function onFileChange(e) {
    const fileList = Array.from(e.target.files)
    if (!fileList.length) return

    // Validate size
    if (props.maxSize) {
        const maxBytes = props.maxSize * 1024 * 1024
        const oversized = fileList.find(f => f.size > maxBytes)
        if (oversized) {
            emit('error', `Le fichier "${oversized.name}" dépasse la taille maximale de ${props.maxSize} Mo`)
            return
        }
    }

    // Validate count
    if (props.maxFiles && props.multiple) {
        const total = files.value.length + fileList.length
        if (total > props.maxFiles) {
            emit('error', `Maximum ${props.maxFiles} fichiers autorisés`)
            return
        }
    }

    if (props.multiple) {
        const newFiles = [...files.value, ...fileList]
        emit('update:modelValue', newFiles)
        emit('change', newFiles)
    } else {
        emit('update:modelValue', fileList[0])
        emit('change', fileList[0])
    }

    // Reset input
    e.target.value = ''
}

function removeFile(index) {
    if (props.multiple) {
        const newFiles = files.value.filter((_, i) => i !== index)
        emit('update:modelValue', newFiles.length ? newFiles : null)
        emit('change', newFiles.length ? newFiles : null)
    } else {
        emit('update:modelValue', null)
        emit('change', null)
    }
}

function clear() {
    emit('update:modelValue', props.multiple ? [] : null)
    emit('change', props.multiple ? [] : null)
}
</script>

<template>
    <div>
        <input
            ref="inputRef"
            type="file"
            :accept="accept"
            :multiple="multiple"
            class="sr-only"
            @change="onFileChange"
        />

        <!-- Trigger -->
        <div
            v-if="files.length === 0"
            :class="[
                'flex items-center rounded-lg border bg-white transition-colors duration-150 cursor-pointer',
                sizeClasses,
                disabled ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : '',
                hasError
                    ? 'border-red-300 hover:border-red-400'
                    : 'border-gray-300 hover:border-gray-400'
            ]"
            @click="openPicker"
        >
            <ArrowUpTrayIcon :class="[iconSizes, 'mr-2 flex-shrink-0', hasError ? 'text-red-400' : 'text-gray-400']" />
            <span class="text-gray-400">{{ placeholder }}</span>
        </div>

        <!-- File list -->
        <div v-else class="space-y-1.5">
            <div
                v-for="(file, index) in files"
                :key="index"
                :class="[
                    'flex items-center gap-2 rounded-lg border bg-white px-3 py-2',
                    hasError ? 'border-red-300' : 'border-gray-200'
                ]"
            >
                <DocumentIcon class="h-5 w-5 text-gray-400 flex-shrink-0" />
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate">{{ file.name }}</p>
                    <p class="text-xs text-gray-500">{{ formatFileSize(file.size) }}</p>
                </div>
                <button
                    v-if="!disabled"
                    type="button"
                    class="text-gray-400 hover:text-red-500 cursor-pointer"
                    @click="removeFile(index)"
                >
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </div>

            <!-- Add more (multiple) -->
            <button
                v-if="multiple && (!maxFiles || files.length < maxFiles)"
                type="button"
                :class="[
                    'flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-700 cursor-pointer',
                    disabled ? 'opacity-50 cursor-not-allowed' : ''
                ]"
                :disabled="disabled"
                @click="openPicker"
            >
                <ArrowUpTrayIcon class="h-4 w-4" />
                Ajouter un fichier
            </button>
        </div>
    </div>
</template>
