<script setup>
import { ref, watch } from 'vue'
import { MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Rechercher...'
    },
    debounce: {
        type: Number,
        default: 300
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    }
})

const emit = defineEmits(['update:modelValue', 'search'])

const localValue = ref(props.modelValue)
let debounceTimer = null

watch(() => props.modelValue, (val) => {
    localValue.value = val
})

const handleInput = (event) => {
    localValue.value = event.target.value

    if (debounceTimer) clearTimeout(debounceTimer)

    debounceTimer = setTimeout(() => {
        emit('update:modelValue', localValue.value)
        emit('search', localValue.value)
    }, props.debounce)
}

const clear = () => {
    localValue.value = ''
    emit('update:modelValue', '')
    emit('search', '')
}

const sizeClasses = {
    sm: 'text-xs py-1.5 pl-8 pr-8',
    md: 'text-sm py-2 pl-9 pr-9',
    lg: 'text-base py-2.5 pl-10 pr-10'
}

const iconSizeClasses = {
    sm: 'h-4 w-4',
    md: 'h-5 w-5',
    lg: 'h-5 w-5'
}

const iconLeftClasses = {
    sm: 'left-2',
    md: 'left-2.5',
    lg: 'left-3'
}

const iconRightClasses = {
    sm: 'right-2',
    md: 'right-2.5',
    lg: 'right-3'
}
</script>

<template>
    <div class="relative">
        <MagnifyingGlassIcon
            :class="[
                'absolute top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none',
                iconLeftClasses[size],
                iconSizeClasses[size]
            ]"
        />
        <input
            type="text"
            :value="localValue"
            :placeholder="placeholder"
            :class="[
                'block w-full rounded-lg border-gray-300 shadow-sm',
                'focus:border-indigo-500 focus:ring-indigo-500',
                'placeholder:text-gray-400',
                sizeClasses[size]
            ]"
            @input="handleInput"
        />
        <button
            v-if="localValue"
            type="button"
            :class="[
                'absolute top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600',
                iconRightClasses[size]
            ]"
            @click="clear"
        >
            <XMarkIcon :class="iconSizeClasses[size]" />
        </button>
    </div>
</template>
