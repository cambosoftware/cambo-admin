<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    type: {
        type: String,
        default: 'text',
        validator: (v) => ['text', 'email', 'password', 'number', 'tel', 'url', 'search', 'date', 'time', 'datetime-local'].includes(v)
    },
    placeholder: {
        type: String,
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
    readonly: {
        type: Boolean,
        default: false
    },
    error: {
        type: [String, Boolean],
        default: null
    },
    icon: {
        type: [Object, Function],
        default: null
    },
    iconRight: {
        type: [Object, Function],
        default: null
    },
    prefix: {
        type: String,
        default: null
    },
    suffix: {
        type: String,
        default: null
    },
    clearable: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue', 'clear', 'focus', 'blur'])

const inputRef = ref(null)

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

const hasError = computed(() => !!props.error)

const inputClasses = computed(() => [
    'block w-full rounded-lg border bg-white transition-colors duration-150',
    'dark:bg-gray-800',
    'placeholder:text-gray-400 dark:placeholder:text-gray-500',
    'focus:outline-none focus:ring-2 focus:ring-offset-0',
    'disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed dark:disabled:bg-gray-900 dark:disabled:text-gray-600',
    hasError.value
        ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500/20 dark:border-red-500 dark:text-red-400'
        : 'border-gray-300 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500/20 dark:border-gray-600 dark:text-white dark:focus:border-indigo-400',
    sizeClasses.value,
    props.icon ? (props.size === 'sm' ? 'pl-8' : props.size === 'lg' ? 'pl-11' : 'pl-9') : '',
    props.iconRight || (props.clearable && props.modelValue) ? (props.size === 'sm' ? 'pr-8' : props.size === 'lg' ? 'pr-11' : 'pr-9') : '',
    props.prefix ? 'rounded-l-none' : '',
    props.suffix ? 'rounded-r-none' : ''
])

const onInput = (e) => {
    const val = props.type === 'number' ? (e.target.value === '' ? '' : Number(e.target.value)) : e.target.value
    emit('update:modelValue', val)
}

const clear = () => {
    emit('update:modelValue', '')
    emit('clear')
    inputRef.value?.focus()
}

const focus = () => inputRef.value?.focus()

defineExpose({ focus, inputRef })
</script>

<template>
    <div class="flex">
        <!-- Prefix -->
        <span
            v-if="prefix"
            :class="[
                'inline-flex items-center rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500',
                'dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400',
                size === 'sm' ? 'px-2.5 text-xs' : size === 'lg' ? 'px-4 text-base' : 'px-3 text-sm'
            ]"
        >
            {{ prefix }}
        </span>

        <!-- Input wrapper -->
        <div class="relative flex-1">
            <!-- Left icon -->
            <div
                v-if="icon"
                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
            >
                <component :is="icon" :class="[iconSizes, hasError ? 'text-red-400' : 'text-gray-400 dark:text-gray-500']" />
            </div>

            <!-- Input -->
            <input
                ref="inputRef"
                :type="type"
                :value="modelValue"
                :placeholder="placeholder"
                :disabled="disabled"
                :readonly="readonly"
                :class="inputClasses"
                @input="onInput"
                @focus="$emit('focus', $event)"
                @blur="$emit('blur', $event)"
            />

            <!-- Right icon / Clear button -->
            <div
                v-if="iconRight || (clearable && modelValue)"
                class="absolute inset-y-0 right-0 flex items-center pr-3"
            >
                <button
                    v-if="clearable && modelValue"
                    type="button"
                    class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 cursor-pointer"
                    tabindex="-1"
                    @click="clear"
                >
                    <svg :class="iconSizes" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
                <component
                    v-else-if="iconRight"
                    :is="iconRight"
                    :class="[iconSizes, hasError ? 'text-red-400' : 'text-gray-400 dark:text-gray-500']"
                />
            </div>
        </div>

        <!-- Suffix -->
        <span
            v-if="suffix"
            :class="[
                'inline-flex items-center rounded-r-lg border border-l-0 border-gray-300 bg-gray-50 text-gray-500',
                'dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400',
                size === 'sm' ? 'px-2.5 text-xs' : size === 'lg' ? 'px-4 text-base' : 'px-3 text-sm'
            ]"
        >
            {{ suffix }}
        </span>
    </div>
</template>
