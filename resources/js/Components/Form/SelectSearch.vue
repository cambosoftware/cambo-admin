<script setup>
import { computed, ref, watch, onMounted, onUnmounted, nextTick } from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, Number, null],
        default: null
    },
    options: {
        type: Array,
        default: () => []
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
    clearable: {
        type: Boolean,
        default: false
    },
    optionLabel: {
        type: String,
        default: 'label'
    },
    optionValue: {
        type: String,
        default: 'value'
    },
    noResultsText: {
        type: String,
        default: 'Aucun résultat'
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'search'])

const open = ref(false)
const search = ref('')
const wrapperRef = ref(null)
const inputRef = ref(null)
const highlightIndex = ref(-1)

const normalizedOptions = computed(() =>
    props.options.map(opt => {
        if (typeof opt === 'string' || typeof opt === 'number') {
            return { label: String(opt), value: opt }
        }
        return {
            label: opt[props.optionLabel] ?? opt.label ?? opt.name ?? String(opt[props.optionValue]),
            value: opt[props.optionValue] ?? opt.value ?? opt.id,
            disabled: opt.disabled ?? false
        }
    })
)

const filteredOptions = computed(() => {
    if (!search.value) return normalizedOptions.value
    const q = search.value.toLowerCase()
    return normalizedOptions.value.filter(opt => opt.label.toLowerCase().includes(q))
})

const selectedOption = computed(() =>
    normalizedOptions.value.find(opt => String(opt.value) === String(props.modelValue))
)

const hasError = computed(() => !!props.error)

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'px-2.5 py-1.5 text-xs',
        md: 'px-3 py-2 text-sm',
        lg: 'px-4 py-2.5 text-base'
    }
    return sizes[props.size]
})

const inputClasses = computed(() => [
    'block w-full rounded-lg border bg-white transition-colors duration-150',
    'placeholder:text-gray-400',
    'focus:outline-none focus:ring-2 focus:ring-offset-0',
    'disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed',
    'pr-10',
    hasError.value
        ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500/20'
        : 'border-gray-300 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500/20',
    sizeClasses.value
])

const openDropdown = () => {
    if (props.disabled) return
    open.value = true
    search.value = ''
    highlightIndex.value = -1
    nextTick(() => inputRef.value?.focus())
}

const closeDropdown = () => {
    open.value = false
    search.value = ''
}

const selectOption = (opt) => {
    if (opt.disabled) return
    emit('update:modelValue', opt.value)
    emit('change', opt.value)
    closeDropdown()
}

const clearSelection = () => {
    emit('update:modelValue', null)
    emit('change', null)
}

const onKeydown = (e) => {
    if (!open.value) {
        if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(e.key)) {
            e.preventDefault()
            openDropdown()
        }
        return
    }

    if (e.key === 'Escape') {
        closeDropdown()
    } else if (e.key === 'ArrowDown') {
        e.preventDefault()
        highlightIndex.value = Math.min(highlightIndex.value + 1, filteredOptions.value.length - 1)
    } else if (e.key === 'ArrowUp') {
        e.preventDefault()
        highlightIndex.value = Math.max(highlightIndex.value - 1, 0)
    } else if (e.key === 'Enter') {
        e.preventDefault()
        if (highlightIndex.value >= 0 && filteredOptions.value[highlightIndex.value]) {
            selectOption(filteredOptions.value[highlightIndex.value])
        }
    }
}

const onSearchInput = (e) => {
    search.value = e.target.value
    highlightIndex.value = 0
    emit('search', search.value)
}

const onClickOutside = (e) => {
    if (wrapperRef.value && !wrapperRef.value.contains(e.target)) {
        closeDropdown()
    }
}

onMounted(() => document.addEventListener('click', onClickOutside))
onUnmounted(() => document.removeEventListener('click', onClickOutside))
</script>

<template>
    <div ref="wrapperRef" class="relative">
        <!-- Trigger -->
        <div
            :class="[
                'flex items-center rounded-lg border bg-white transition-colors duration-150 cursor-pointer',
                'focus-within:ring-2 focus-within:ring-offset-0',
                hasError
                    ? 'border-red-300 focus-within:border-red-500 focus-within:ring-red-500/20'
                    : 'border-gray-300 focus-within:border-indigo-500 focus-within:ring-indigo-500/20',
                disabled ? 'bg-gray-50 cursor-not-allowed' : '',
                sizeClasses
            ]"
            @click="openDropdown"
        >
            <input
                v-if="open"
                ref="inputRef"
                type="text"
                :value="search"
                :placeholder="selectedOption?.label ?? placeholder"
                class="flex-1 border-none bg-transparent outline-none p-0 text-inherit placeholder:text-gray-400"
                @input="onSearchInput"
                @keydown="onKeydown"
            />
            <span v-else :class="['flex-1 truncate', !selectedOption ? 'text-gray-400' : 'text-gray-900']">
                {{ selectedOption?.label ?? placeholder }}
            </span>

            <div class="flex items-center gap-1 ml-2">
                <!-- Clear -->
                <button
                    v-if="clearable && modelValue != null"
                    type="button"
                    class="text-gray-400 hover:text-gray-600 cursor-pointer"
                    @click.stop="clearSelection"
                >
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
                <!-- Chevron -->
                <svg :class="['h-4 w-4 text-gray-400 transition-transform', open ? 'rotate-180' : '']" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <!-- Dropdown -->
        <Transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <ul
                v-if="open"
                class="absolute z-50 mt-1 w-full max-h-60 overflow-auto rounded-lg bg-white shadow-lg ring-1 ring-black/5 py-1"
                role="listbox"
            >
                <li
                    v-for="(opt, index) in filteredOptions"
                    :key="opt.value"
                    :class="[
                        'px-3 py-2 text-sm cursor-pointer transition-colors',
                        opt.disabled ? 'text-gray-400 cursor-not-allowed' : '',
                        String(opt.value) === String(modelValue) ? 'bg-indigo-50 text-indigo-700 font-medium' : '',
                        index === highlightIndex && !opt.disabled ? 'bg-gray-100' : '',
                        !opt.disabled && String(opt.value) !== String(modelValue) ? 'hover:bg-gray-50' : ''
                    ]"
                    role="option"
                    :aria-selected="String(opt.value) === String(modelValue)"
                    @click="selectOption(opt)"
                    @mouseenter="highlightIndex = index"
                >
                    <slot name="option" :option="opt">
                        {{ opt.label }}
                    </slot>
                </li>
                <li v-if="filteredOptions.length === 0" class="px-3 py-2 text-sm text-gray-500 text-center">
                    {{ noResultsText }}
                </li>
            </ul>
        </Transition>
    </div>
</template>
