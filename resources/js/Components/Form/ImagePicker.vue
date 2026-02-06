<script setup>
import { computed, ref, watch } from 'vue'
import { PhotoIcon, XMarkIcon, CameraIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: [File, null],
        default: null
    },
    accept: {
        type: String,
        default: 'image/*'
    },
    maxSize: {
        type: Number,
        default: 5 // MB
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
    previewUrl: {
        type: String,
        default: null
    },
    ratio: {
        type: String,
        default: 'square',
        validator: (v) => ['square', '16/9', '4/3', 'auto'].includes(v)
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'error'])

const inputRef = ref(null)
const preview = ref(props.previewUrl)

const hasError = computed(() => !!props.error)

const previewSrc = computed(() => {
    if (props.modelValue && props.modelValue instanceof File) return preview.value
    return props.previewUrl || null
})

const containerSize = computed(() => {
    const sizes = {
        sm: 'h-24 w-24',
        md: 'h-32 w-32',
        lg: 'h-40 w-40'
    }
    if (props.ratio === 'square') return sizes[props.size]
    if (props.ratio === '16/9') {
        const h = { sm: 'h-20', md: 'h-28', lg: 'h-36' }
        return `${h[props.size]} w-full`
    }
    if (props.ratio === '4/3') {
        const h = { sm: 'h-24', md: 'h-32', lg: 'h-40' }
        return `${h[props.size]} w-full`
    }
    return 'h-32 w-full'
})

watch(() => props.modelValue, (file) => {
    if (file && file instanceof File) {
        const reader = new FileReader()
        reader.onload = (e) => { preview.value = e.target.result }
        reader.readAsDataURL(file)
    } else if (!file) {
        preview.value = props.previewUrl
    }
})

function openPicker() {
    if (props.disabled) return
    inputRef.value?.click()
}

function onFileChange(e) {
    const file = e.target.files[0]
    if (!file) return

    // Validate type
    if (!file.type.startsWith('image/')) {
        emit('error', 'Le fichier doit être une image')
        return
    }

    // Validate size
    if (props.maxSize) {
        const maxBytes = props.maxSize * 1024 * 1024
        if (file.size > maxBytes) {
            emit('error', `L'image dépasse la taille maximale de ${props.maxSize} Mo`)
            return
        }
    }

    emit('update:modelValue', file)
    emit('change', file)
    e.target.value = ''
}

function clear() {
    emit('update:modelValue', null)
    emit('change', null)
    preview.value = props.previewUrl
}
</script>

<template>
    <div>
        <input
            ref="inputRef"
            type="file"
            :accept="accept"
            class="sr-only"
            @change="onFileChange"
        />

        <!-- Preview / Picker -->
        <div
            :class="[
                'relative flex items-center justify-center rounded-lg border-2 border-dashed transition-colors overflow-hidden',
                containerSize,
                disabled ? 'bg-gray-50 cursor-not-allowed' : 'cursor-pointer hover:border-gray-400',
                hasError
                    ? 'border-red-300 bg-red-50'
                    : previewSrc ? 'border-transparent' : 'border-gray-300 bg-gray-50'
            ]"
            @click="openPicker"
        >
            <!-- Image preview -->
            <img
                v-if="previewSrc"
                :src="previewSrc"
                class="h-full w-full object-cover"
                alt="Preview"
            />

            <!-- Empty state -->
            <div v-else class="text-center p-3">
                <PhotoIcon :class="['mx-auto mb-1', size === 'sm' ? 'h-6 w-6' : size === 'lg' ? 'h-10 w-10' : 'h-8 w-8', hasError ? 'text-red-400' : 'text-gray-400']" />
                <p :class="[size === 'sm' ? 'text-xs' : 'text-sm', 'text-gray-500']">
                    Cliquer pour choisir
                </p>
            </div>

            <!-- Hover overlay (when has preview) -->
            <div
                v-if="previewSrc && !disabled"
                class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 hover:opacity-100 transition-opacity"
            >
                <CameraIcon class="h-8 w-8 text-white" />
            </div>
        </div>

        <!-- Remove button -->
        <button
            v-if="previewSrc && !disabled"
            type="button"
            class="mt-1.5 flex items-center gap-1 text-xs text-red-600 hover:text-red-700 cursor-pointer"
            @click.stop="clear"
        >
            <XMarkIcon class="h-3.5 w-3.5" />
            Supprimer
        </button>
    </div>
</template>
