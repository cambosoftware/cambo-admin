<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'

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
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const wrapperRef = ref(null)
const open = ref(false)
const highlightIndex = ref(-1)

const hasError = computed(() => !!props.error)

const normalizedOptions = computed(() =>
    props.options.map(opt => {
        if (typeof opt === 'string' || typeof opt === 'number') {
            return { label: String(opt), value: opt }
        }
        return {
            label: opt[props.optionLabel] ?? opt.label ?? opt.name ?? String(opt[props.optionValue]),
            value: opt[props.optionValue] ?? opt.value ?? opt.id,
            disabled: opt.disabled ?? false,
            group: opt.group ?? null
        }
    })
)

const selectedOption = computed(() =>
    normalizedOptions.value.find(opt => String(opt.value) === String(props.modelValue))
)

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'px-2.5 py-1.5 text-xs',
        md: 'px-3 py-2 text-sm',
        lg: 'px-4 py-2.5 text-base'
    }
    return sizes[props.size]
})

const toggle = () => {
    if (props.disabled) return
    open.value = !open.value
    if (open.value) {
        const idx = normalizedOptions.value.findIndex(opt => String(opt.value) === String(props.modelValue))
        highlightIndex.value = idx >= 0 ? idx : 0
    }
}

const selectOption = (opt) => {
    if (opt.disabled) return
    emit('update:modelValue', opt.value)
    emit('change', opt.value)
    open.value = false
}

const clearSelection = () => {
    emit('update:modelValue', null)
    emit('change', null)
}

const onKeydown = (e) => {
    if (!open.value) {
        if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(e.key)) {
            e.preventDefault()
            toggle()
        }
        return
    }

    if (e.key === 'Escape') {
        open.value = false
    } else if (e.key === 'ArrowDown') {
        e.preventDefault()
        highlightIndex.value = Math.min(highlightIndex.value + 1, normalizedOptions.value.length - 1)
    } else if (e.key === 'ArrowUp') {
        e.preventDefault()
        highlightIndex.value = Math.max(highlightIndex.value - 1, 0)
    } else if (e.key === 'Enter') {
        e.preventDefault()
        if (highlightIndex.value >= 0 && normalizedOptions.value[highlightIndex.value]) {
            selectOption(normalizedOptions.value[highlightIndex.value])
        }
    }
}

const onClickOutside = (e) => {
    if (wrapperRef.value && !wrapperRef.value.contains(e.target)) {
        open.value = false
    }
}

onMounted(() => document.addEventListener('mousedown', onClickOutside))
onBeforeUnmount(() => document.removeEventListener('mousedown', onClickOutside))
</script>

<template>
    <div ref="wrapperRef" class="relative">
        <!-- Trigger -->
        <div
            :class="[
                'flex items-center rounded-lg border bg-white transition-colors duration-150 cursor-pointer',
                hasError
                    ? 'border-red-300'
                    : 'border-gray-300',
                open ? (hasError ? 'border-red-500 ring-2 ring-red-500/20' : 'border-indigo-500 ring-2 ring-indigo-500/20') : '',
                disabled ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : '',
                sizeClasses
            ]"
            tabindex="0"
            @click="toggle"
            @keydown="onKeydown"
        >
            <span :class="['flex-1 truncate', !selectedOption ? 'text-gray-400' : 'text-gray-900']">
                {{ selectedOption?.label ?? placeholder }}
            </span>

            <div class="flex items-center gap-1 ml-2">
                <!-- Clear -->
                <button
                    v-if="clearable && modelValue != null"
                    type="button"
                    class="text-gray-400 hover:text-gray-600 cursor-pointer"
                    tabindex="-1"
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
                    v-for="(opt, index) in normalizedOptions"
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
            </ul>
        </Transition>
    </div>
</template>
