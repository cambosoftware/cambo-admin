<script setup>
import { computed, ref, nextTick } from 'vue'
import { XMarkIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Ajouter un tag...'
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
    max: {
        type: Number,
        default: null
    },
    allowDuplicates: {
        type: Boolean,
        default: false
    },
    separator: {
        type: [String, Array],
        default: () => [',', 'Enter']
    },
    suggestions: {
        type: Array,
        default: () => []
    },
    validateTag: {
        type: Function,
        default: null
    }
})

const emit = defineEmits(['update:modelValue', 'add', 'remove', 'focus', 'blur'])

const inputRef = ref(null)
const inputValue = ref('')
const focused = ref(false)
const showSuggestions = ref(false)

const hasError = computed(() => !!props.error)

const atMax = computed(() => props.max !== null && props.modelValue.length >= props.max)

const filteredSuggestions = computed(() => {
    if (!inputValue.value || !props.suggestions.length) return []
    const search = inputValue.value.toLowerCase()
    return props.suggestions.filter(s => {
        const label = typeof s === 'string' ? s : s.label || s
        return label.toLowerCase().includes(search) && !props.modelValue.includes(label)
    })
})

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'min-h-[30px] text-xs',
        md: 'min-h-[38px] text-sm',
        lg: 'min-h-[46px] text-base'
    }
    return sizes[props.size]
})

const tagSizes = computed(() => {
    const sizes = {
        sm: 'px-1.5 py-0.5 text-xs',
        md: 'px-2 py-0.5 text-xs',
        lg: 'px-2.5 py-1 text-sm'
    }
    return sizes[props.size]
})

const separators = computed(() => {
    return Array.isArray(props.separator) ? props.separator : [props.separator]
})

const addTag = (tag) => {
    const trimmed = tag.trim()
    if (!trimmed) return
    if (atMax.value) return
    if (!props.allowDuplicates && props.modelValue.includes(trimmed)) return
    if (props.validateTag && !props.validateTag(trimmed)) return

    emit('update:modelValue', [...props.modelValue, trimmed])
    emit('add', trimmed)
    inputValue.value = ''
    showSuggestions.value = false
}

const removeTag = (index) => {
    if (props.disabled) return
    const removed = props.modelValue[index]
    const newTags = [...props.modelValue]
    newTags.splice(index, 1)
    emit('update:modelValue', newTags)
    emit('remove', removed)
}

const onKeydown = (e) => {
    if (separators.value.includes(e.key)) {
        if (e.key !== 'Enter' || inputValue.value) {
            e.preventDefault()
            addTag(inputValue.value)
        }
    } else if (e.key === 'Backspace' && !inputValue.value && props.modelValue.length) {
        removeTag(props.modelValue.length - 1)
    } else if (e.key === 'Escape') {
        showSuggestions.value = false
    }
}

const onInput = (e) => {
    inputValue.value = e.target.value
    showSuggestions.value = filteredSuggestions.value.length > 0

    // Check for separator characters in the input
    const separatorChars = separators.value.filter(s => s !== 'Enter' && s.length === 1)
    for (const sep of separatorChars) {
        if (e.target.value.includes(sep)) {
            const parts = e.target.value.split(sep)
            parts.forEach(part => addTag(part))
            inputValue.value = ''
            break
        }
    }
}

const onFocus = (e) => {
    focused.value = true
    if (filteredSuggestions.value.length) showSuggestions.value = true
    emit('focus', e)
}

const onBlur = (e) => {
    focused.value = false
    // Add current input as tag on blur
    if (inputValue.value.trim()) {
        addTag(inputValue.value)
    }
    setTimeout(() => { showSuggestions.value = false }, 150)
    emit('blur', e)
}

const selectSuggestion = (suggestion) => {
    const label = typeof suggestion === 'string' ? suggestion : suggestion.label || suggestion
    addTag(label)
    nextTick(() => inputRef.value?.focus())
}

const focusInput = () => {
    if (!props.disabled) inputRef.value?.focus()
}

defineExpose({ focus: focusInput, inputRef })
</script>

<template>
    <div class="relative">
        <!-- Tag input container -->
        <div
            :class="[
                'flex flex-wrap items-center gap-1.5 rounded-lg border bg-white px-2 py-1.5 transition-colors duration-150 cursor-text',
                sizeClasses,
                disabled ? 'bg-gray-50 cursor-not-allowed' : '',
                hasError
                    ? 'border-red-300 focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/20'
                    : 'border-gray-300 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20'
            ]"
            @click="focusInput"
        >
            <!-- Tags -->
            <span
                v-for="(tag, index) in modelValue"
                :key="index"
                :class="[
                    'inline-flex items-center gap-1 rounded-md bg-indigo-50 text-indigo-700 font-medium',
                    tagSizes
                ]"
            >
                {{ tag }}
                <button
                    v-if="!disabled"
                    type="button"
                    class="inline-flex items-center text-indigo-400 hover:text-indigo-600 cursor-pointer"
                    tabindex="-1"
                    @click.stop="removeTag(index)"
                >
                    <XMarkIcon class="h-3 w-3" />
                </button>
            </span>

            <!-- Input -->
            <input
                ref="inputRef"
                type="text"
                :value="inputValue"
                :placeholder="modelValue.length === 0 ? placeholder : ''"
                :disabled="disabled || atMax"
                class="flex-1 min-w-[80px] border-0 bg-transparent p-0 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-0"
                :class="size === 'sm' ? 'text-xs' : size === 'lg' ? 'text-base' : 'text-sm'"
                @input="onInput"
                @keydown="onKeydown"
                @focus="onFocus"
                @blur="onBlur"
            />
        </div>

        <!-- Suggestions dropdown -->
        <div
            v-if="showSuggestions && filteredSuggestions.length"
            class="absolute z-50 mt-1 w-full rounded-lg border border-gray-200 bg-white py-1 shadow-lg"
        >
            <button
                v-for="suggestion in filteredSuggestions"
                :key="typeof suggestion === 'string' ? suggestion : suggestion.label"
                type="button"
                class="w-full px-3 py-1.5 text-left text-sm text-gray-700 hover:bg-gray-50 cursor-pointer"
                @mousedown.prevent="selectSuggestion(suggestion)"
            >
                {{ typeof suggestion === 'string' ? suggestion : suggestion.label }}
            </button>
        </div>
    </div>
</template>
