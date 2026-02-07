<script setup>
import { computed, ref } from 'vue'
import { CloudArrowUpIcon, DocumentIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    },
    accept: {
        type: String,
        default: null
    },
    multiple: {
        type: Boolean,
        default: true
    },
    maxSize: {
        type: Number,
        default: null // MB
    },
    maxFiles: {
        type: Number,
        default: null
    },
    disabled: {
        type: Boolean,
        default: false
    },
    error: {
        type: [String, Boolean],
        default: null
    },
    description: {
        type: String,
        default: null
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'error'])

const inputRef = ref(null)
const isDragOver = ref(false)

const hasError = computed(() => !!props.error)

const acceptedTypes = computed(() => {
    if (!props.accept) return null
    return props.accept.split(',').map(t => t.trim())
})

function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' o'
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' Ko'
    return (bytes / (1024 * 1024)).toFixed(1) + ' Mo'
}

function isImage(file) {
    return file.type.startsWith('image/')
}

function validateFiles(files) {
    const errors = []

    // Max files
    if (props.maxFiles && props.modelValue.length + files.length > props.maxFiles) {
        errors.push(`Maximum ${props.maxFiles} fichiers autorisés`)
        return errors
    }

    // Max size
    if (props.maxSize) {
        const maxBytes = props.maxSize * 1024 * 1024
        files.forEach(f => {
            if (f.size > maxBytes) {
                errors.push(`"${f.name}" dépasse ${props.maxSize} Mo`)
            }
        })
    }

    // Accept types
    if (acceptedTypes.value) {
        files.forEach(f => {
            const matches = acceptedTypes.value.some(type => {
                if (type.startsWith('.')) return f.name.toLowerCase().endsWith(type)
                if (type.endsWith('/*')) return f.type.startsWith(type.replace('/*', '/'))
                return f.type === type
            })
            if (!matches) errors.push(`"${f.name}" n'est pas un type de fichier accepté`)
        })
    }

    return errors
}

function addFiles(fileList) {
    const files = Array.from(fileList)
    const errors = validateFiles(files)
    if (errors.length) {
        emit('error', errors.join(', '))
        return
    }

    const newFiles = props.multiple ? [...props.modelValue, ...files] : [files[0]]
    emit('update:modelValue', newFiles)
    emit('change', newFiles)
}

function removeFile(index) {
    const newFiles = props.modelValue.filter((_, i) => i !== index)
    emit('update:modelValue', newFiles)
    emit('change', newFiles)
}

function onDrop(e) {
    isDragOver.value = false
    if (props.disabled) return
    addFiles(e.dataTransfer.files)
}

function onDragOver(e) {
    if (props.disabled) return
    isDragOver.value = true
}

function onDragLeave() {
    isDragOver.value = false
}

function openPicker() {
    if (props.disabled) return
    inputRef.value?.click()
}

function onFileChange(e) {
    addFiles(e.target.files)
    e.target.value = ''
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

        <!-- Drop zone -->
        <div
            :class="[
                'rounded-lg border-2 border-dashed p-6 text-center transition-colors',
                disabled ? 'bg-gray-50 cursor-not-allowed' : 'cursor-pointer',
                isDragOver ? 'border-indigo-400 bg-indigo-50' : '',
                hasError
                    ? 'border-red-300 bg-red-50'
                    : !isDragOver ? 'border-gray-300 hover:border-gray-400' : ''
            ]"
            @click="openPicker"
            @drop.prevent="onDrop"
            @dragover.prevent="onDragOver"
            @dragleave="onDragLeave"
        >
            <CloudArrowUpIcon :class="['mx-auto h-10 w-10', isDragOver ? 'text-indigo-500' : hasError ? 'text-red-400' : 'text-gray-400']" />
            <p class="mt-2 text-sm font-medium text-gray-700">
                <span class="text-indigo-600">Cliquez pour sélectionner</span> ou glissez-déposez
            </p>
            <p v-if="description" class="mt-1 text-xs text-gray-500">
                {{ description }}
            </p>
            <p v-else class="mt-1 text-xs text-gray-500">
                <template v-if="accept">{{ accept }}</template>
                <template v-if="maxSize"> &bull; Max {{ maxSize }} Mo</template>
                <template v-if="maxFiles"> &bull; Max {{ maxFiles }} fichiers</template>
            </p>
        </div>

        <!-- File list -->
        <div v-if="modelValue.length" class="mt-3 space-y-1.5">
            <div
                v-for="(file, index) in modelValue"
                :key="index"
                class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2"
            >
                <!-- Thumbnail for images -->
                <div v-if="isImage(file)" class="h-8 w-8 flex-shrink-0 overflow-hidden rounded">
                    <img
                        :src="URL.createObjectURL(file)"
                        class="h-full w-full object-cover"
                        alt=""
                    />
                </div>
                <DocumentIcon v-else class="h-5 w-5 text-gray-400 flex-shrink-0" />

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate">{{ file.name }}</p>
                    <p class="text-xs text-gray-500">{{ formatFileSize(file.size) }}</p>
                </div>
                <button
                    v-if="!disabled"
                    type="button"
                    class="text-gray-400 hover:text-red-500 cursor-pointer"
                    @click.stop="removeFile(index)"
                >
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </div>
        </div>
    </div>
</template>
