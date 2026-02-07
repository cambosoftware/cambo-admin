<script setup>
import { computed, ref } from 'vue'
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
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
    error: {
        type: [String, Boolean],
        default: null
    },
    toggleable: {
        type: Boolean,
        default: true
    },
    icon: {
        type: [Object, Function],
        default: null
    },
    strengthMeter: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue', 'focus', 'blur'])

const inputRef = ref(null)
const visible = ref(false)

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

const strength = computed(() => {
    if (!props.strengthMeter || !props.modelValue) return 0
    let score = 0
    const v = props.modelValue
    if (v.length >= 8) score++
    if (/[a-z]/.test(v) && /[A-Z]/.test(v)) score++
    if (/\d/.test(v)) score++
    if (/[^a-zA-Z0-9]/.test(v)) score++
    return score
})

const strengthLabel = computed(() => {
    const labels = ['', 'Faible', 'Moyen', 'Bon', 'Fort']
    return labels[strength.value]
})

const strengthColor = computed(() => {
    const colors = ['', 'bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500']
    return colors[strength.value]
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
    props.icon ? (props.size === 'sm' ? 'pl-8' : props.size === 'lg' ? 'pl-11' : 'pl-9') : '',
    props.toggleable ? (props.size === 'sm' ? 'pr-8' : props.size === 'lg' ? 'pr-11' : 'pr-9') : ''
])

const toggleVisibility = () => {
    visible.value = !visible.value
    inputRef.value?.focus()
}

const focus = () => inputRef.value?.focus()

defineExpose({ focus, inputRef })
</script>

<template>
    <div>
        <div class="relative">
            <!-- Left icon -->
            <div
                v-if="icon"
                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
            >
                <component :is="icon" :class="[iconSizes, hasError ? 'text-red-400' : 'text-gray-400']" />
            </div>

            <!-- Input -->
            <input
                ref="inputRef"
                :type="visible ? 'text' : 'password'"
                :value="modelValue"
                :placeholder="placeholder"
                :disabled="disabled"
                :class="inputClasses"
                @input="$emit('update:modelValue', $event.target.value)"
                @focus="$emit('focus', $event)"
                @blur="$emit('blur', $event)"
            />

            <!-- Toggle visibility -->
            <button
                v-if="toggleable"
                type="button"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 cursor-pointer"
                tabindex="-1"
                @click="toggleVisibility"
            >
                <component :is="visible ? EyeSlashIcon : EyeIcon" :class="iconSizes" />
            </button>
        </div>

        <!-- Strength meter -->
        <div v-if="strengthMeter && modelValue" class="mt-1.5">
            <div class="flex gap-1">
                <div
                    v-for="i in 4"
                    :key="i"
                    :class="[
                        'h-1 flex-1 rounded-full transition-colors duration-200',
                        i <= strength ? strengthColor : 'bg-gray-200'
                    ]"
                />
            </div>
            <p :class="[
                'mt-0.5 text-xs',
                strength <= 1 ? 'text-red-600' : strength === 2 ? 'text-orange-600' : strength === 3 ? 'text-yellow-600' : 'text-green-600'
            ]">
                {{ strengthLabel }}
            </p>
        </div>
    </div>
</template>
