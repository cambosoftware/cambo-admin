<script setup>
import { computed, ref, onMounted, onUnmounted, nextTick } from 'vue'

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    },
    options: {
        type: Array,
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Sélectionnez...'
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
    },
    max: {
        type: Number,
        default: null
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const open = ref(false)
const search = ref('')
const wrapperRef = ref(null)
const inputRef = ref(null)

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

const selectedValues = computed(() => new Set(props.modelValue.map(String)))

const selectedOptions = computed(() =>
    normalizedOptions.value.filter(opt => selectedValues.value.has(String(opt.value)))
)

const hasError = computed(() => !!props.error)
const canAddMore = computed(() => !props.max || props.modelValue.length < props.max)

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'px-2.5 py-1 text-xs min-h-[30px]',
        md: 'px-3 py-1.5 text-sm min-h-[38px]',
        lg: 'px-4 py-2 text-base min-h-[46px]'
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

const toggleOption = (opt) => {
    if (opt.disabled) return
    const current = [...props.modelValue]
    const idx = current.findIndex(v => String(v) === String(opt.value))
    if (idx >= 0) {
        current.splice(idx, 1)
    } else if (canAddMore.value) {
        current.push(opt.value)
    }
    emit('update:modelValue', current)
    emit('change', current)
}

const removeTag = (value) => {
    const current = props.modelValue.filter(v => String(v) !== String(value))
    emit('update:modelValue', current)
    emit('change', current)
}

const openDropdown = () => {
    if (props.disabled) return
    open.value = true
    search.value = ''
    nextTick(() => inputRef.value?.focus())
}

const closeDropdown = () => {
    open.value = false
    search.value = ''
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
                'flex flex-wrap items-center gap-1 rounded-lg border bg-white transition-colors duration-150 cursor-text',
                'focus-within:ring-2 focus-within:ring-offset-0',
                hasError
                    ? 'border-red-300 focus-within:border-red-500 focus-within:ring-red-500/20'
                    : 'border-gray-300 focus-within:border-primary-500 focus-within:ring-primary-500/20',
                disabled ? 'bg-gray-50 cursor-not-allowed' : '',
                sizeClasses
            ]"
            @click="openDropdown"
        >
            <!-- Tags -->
            <span
                v-for="opt in selectedOptions"
                :key="opt.value"
                :class="[
                    'inline-flex items-center gap-1 rounded bg-primary-50 text-primary-700 font-medium',
                    tagSizes
                ]"
            >
                {{ opt.label }}
                <button
                    v-if="!disabled"
                    type="button"
                    class="hover:text-primary-900 cursor-pointer"
                    @click.stop="removeTag(opt.value)"
                >
                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
            </span>

            <!-- Search input -->
            <input
                ref="inputRef"
                type="text"
                :value="search"
                :placeholder="selectedOptions.length === 0 ? placeholder : ''"
                :disabled="disabled"
                class="flex-1 min-w-[60px] border-none bg-transparent outline-none p-0 text-inherit placeholder:text-gray-400"
                @input="search = $event.target.value"
                @focus="openDropdown"
                @keydown.escape="closeDropdown"
                @keydown.backspace="!search && selectedOptions.length > 0 && removeTag(selectedOptions[selectedOptions.length - 1].value)"
            />
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
                    v-for="opt in filteredOptions"
                    :key="opt.value"
                    :class="[
                        'flex items-center gap-2 px-3 py-2 text-sm cursor-pointer transition-colors',
                        opt.disabled ? 'text-gray-400 cursor-not-allowed' : 'hover:bg-gray-50',
                        selectedValues.has(String(opt.value)) ? 'bg-primary-50' : ''
                    ]"
                    role="option"
                    :aria-selected="selectedValues.has(String(opt.value))"
                    @click="toggleOption(opt)"
                >
                    <!-- Checkbox indicator -->
                    <span :class="[
                        'flex-shrink-0 h-4 w-4 rounded border flex items-center justify-center',
                        selectedValues.has(String(opt.value))
                            ? 'bg-primary-600 border-primary-600'
                            : 'border-gray-300'
                    ]">
                        <svg v-if="selectedValues.has(String(opt.value))" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                    </span>
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
