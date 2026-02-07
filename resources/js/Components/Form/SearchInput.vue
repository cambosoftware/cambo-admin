<script setup>
import { computed, ref } from 'vue'
import { MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Rechercher...'
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
    loading: {
        type: Boolean,
        default: false
    },
    debounce: {
        type: Number,
        default: 0
    }
})

const emit = defineEmits(['update:modelValue', 'search', 'clear', 'focus', 'blur'])

const inputRef = ref(null)
let debounceTimer = null

const hasError = computed(() => !!props.error)

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'py-1.5 text-xs',
        md: 'py-2 text-sm',
        lg: 'py-2.5 text-base'
    }
    return sizes[props.size]
})

const iconSizes = computed(() => {
    const sizes = { sm: 'h-3.5 w-3.5', md: 'h-4 w-4', lg: 'h-5 w-5' }
    return sizes[props.size]
})

const inputClasses = computed(() => [
    'block w-full rounded-lg border bg-white transition-colors duration-150',
    'placeholder:text-gray-400',
    'focus:outline-none focus:ring-2 focus:ring-offset-0',
    'disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed',
    hasError.value
        ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500/20'
        : 'border-gray-300 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500/20',
    sizeClasses.value,
    props.size === 'sm' ? 'pl-8' : props.size === 'lg' ? 'pl-11' : 'pl-9',
    props.modelValue || props.loading ? (props.size === 'sm' ? 'pr-8' : props.size === 'lg' ? 'pr-11' : 'pr-9') : 'pr-3'
])

const onInput = (e) => {
    const val = e.target.value
    emit('update:modelValue', val)

    if (props.debounce > 0) {
        clearTimeout(debounceTimer)
        debounceTimer = setTimeout(() => {
            emit('search', val)
        }, props.debounce)
    } else {
        emit('search', val)
    }
}

const onKeydown = (e) => {
    if (e.key === 'Enter') {
        clearTimeout(debounceTimer)
        emit('search', props.modelValue)
    }
    if (e.key === 'Escape') {
        clear()
    }
}

const clear = () => {
    emit('update:modelValue', '')
    emit('clear')
    emit('search', '')
    inputRef.value?.focus()
}

const focus = () => inputRef.value?.focus()

defineExpose({ focus, inputRef })
</script>

<template>
    <div class="relative">
        <!-- Search icon -->
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <MagnifyingGlassIcon :class="[iconSizes, hasError ? 'text-red-400' : 'text-gray-400']" />
        </div>

        <!-- Input -->
        <input
            ref="inputRef"
            type="search"
            :value="modelValue"
            :placeholder="placeholder"
            :disabled="disabled"
            :class="inputClasses"
            @input="onInput"
            @keydown="onKeydown"
            @focus="$emit('focus', $event)"
            @blur="$emit('blur', $event)"
        />

        <!-- Loading / Clear -->
        <div
            v-if="loading || modelValue"
            class="absolute inset-y-0 right-0 flex items-center pr-3"
        >
            <!-- Loading spinner -->
            <svg
                v-if="loading"
                :class="[iconSizes, 'animate-spin text-gray-400']"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>

            <!-- Clear button -->
            <button
                v-else-if="modelValue"
                type="button"
                class="text-gray-400 hover:text-gray-600 cursor-pointer"
                tabindex="-1"
                @click="clear"
            >
                <XMarkIcon :class="iconSizes" />
            </button>
        </div>
    </div>
</template>
