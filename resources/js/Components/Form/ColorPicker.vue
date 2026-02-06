<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { SwatchIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: String,
        default: null
    },
    placeholder: {
        type: String,
        default: 'Choisir une couleur'
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
    clearable: {
        type: Boolean,
        default: true
    },
    swatches: {
        type: Array,
        default: () => [
            '#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16', '#22c55e',
            '#14b8a6', '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1', '#8b5cf6',
            '#a855f7', '#d946ef', '#ec4899', '#f43f5e',
            '#000000', '#374151', '#6b7280', '#9ca3af', '#d1d5db', '#f3f4f6', '#ffffff'
        ]
    },
    showInput: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const containerRef = ref(null)
const isOpen = ref(false)
const hexInput = ref('')

const hasError = computed(() => !!props.error)

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

const previewSize = computed(() => {
    const sizes = { sm: 'h-4 w-4', md: 'h-5 w-5', lg: 'h-6 w-6' }
    return sizes[props.size]
})

function open() {
    if (props.disabled) return
    isOpen.value = true
    hexInput.value = props.modelValue || ''
}

function close() { isOpen.value = false }
function toggle() { isOpen.value ? close() : open() }

function selectColor(color) {
    emit('update:modelValue', color)
    emit('change', color)
    hexInput.value = color
}

function onHexInput(e) {
    let val = e.target.value
    hexInput.value = val
    // Auto-prefix with #
    if (val && !val.startsWith('#')) val = '#' + val
    // Validate hex color
    if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test(val)) {
        emit('update:modelValue', val)
        emit('change', val)
    }
}

function onNativeChange(e) {
    const val = e.target.value
    emit('update:modelValue', val)
    emit('change', val)
    hexInput.value = val
}

function clear() {
    emit('update:modelValue', null)
    emit('change', null)
    hexInput.value = ''
}

function onClickOutside(e) {
    if (containerRef.value && !containerRef.value.contains(e.target)) close()
}

onMounted(() => document.addEventListener('mousedown', onClickOutside))
onBeforeUnmount(() => document.removeEventListener('mousedown', onClickOutside))
</script>

<template>
    <div ref="containerRef" class="relative">
        <!-- Trigger -->
        <div
            :class="[
                'flex items-center rounded-lg border bg-white transition-colors duration-150 cursor-pointer',
                sizeClasses,
                disabled ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : '',
                hasError ? 'border-red-300' : 'border-gray-300',
                isOpen ? (hasError ? 'border-red-500 ring-2 ring-red-500/20' : 'border-primary-500 ring-2 ring-primary-500/20') : ''
            ]"
            tabindex="0"
            @click="toggle"
            @keydown.enter.prevent="toggle"
            @keydown.escape="close"
        >
            <!-- Color preview -->
            <div v-if="modelValue" :class="['rounded border border-gray-200 mr-2 flex-shrink-0', previewSize]" :style="{ backgroundColor: modelValue }" />
            <SwatchIcon v-else :class="[iconSizes, 'mr-2 flex-shrink-0', hasError ? 'text-red-400' : 'text-gray-400']" />

            <span :class="modelValue ? 'text-gray-900 font-mono' : 'text-gray-400'" class="flex-1">
                {{ modelValue || placeholder }}
            </span>

            <button
                v-if="clearable && modelValue && !disabled"
                type="button"
                class="ml-1 text-gray-400 hover:text-gray-600 cursor-pointer"
                tabindex="-1"
                @click.stop="clear"
            >
                <XMarkIcon :class="iconSizes" />
            </button>
        </div>

        <!-- Dropdown -->
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div v-if="isOpen" class="absolute z-50 mt-1 w-64 rounded-lg border border-gray-200 bg-white p-3 shadow-lg">
                <!-- Swatches -->
                <div class="grid grid-cols-8 gap-1.5 mb-3">
                    <button
                        v-for="color in swatches"
                        :key="color"
                        type="button"
                        :class="[
                            'h-6 w-6 rounded-md border transition-transform cursor-pointer hover:scale-110',
                            modelValue === color ? 'ring-2 ring-primary-500 ring-offset-1 border-transparent' : 'border-gray-200',
                            color === '#ffffff' ? 'border-gray-300' : ''
                        ]"
                        :style="{ backgroundColor: color }"
                        @click="selectColor(color)"
                    />
                </div>

                <!-- Native color picker + hex input -->
                <div v-if="showInput" class="flex items-center gap-2">
                    <div class="relative">
                        <input
                            type="color"
                            :value="modelValue || '#000000'"
                            class="h-8 w-8 cursor-pointer rounded border border-gray-200 p-0.5"
                            @input="onNativeChange"
                        />
                    </div>
                    <input
                        type="text"
                        :value="hexInput"
                        placeholder="#000000"
                        class="flex-1 rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-xs font-mono text-gray-900 placeholder:text-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
                        maxlength="7"
                        @input="onHexInput"
                    />
                </div>
            </div>
        </Transition>
    </div>
</template>
