<script setup>
import { ref } from 'vue'
import { ClipboardDocumentIcon, CheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    value: {
        type: String,
        required: true
    },
    label: {
        type: String,
        default: 'Copier'
    },
    successLabel: {
        type: String,
        default: 'Copié !'
    },
    showLabel: {
        type: Boolean,
        default: false
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    },
    variant: {
        type: String,
        default: 'ghost',
        validator: (v) => ['ghost', 'outline'].includes(v)
    }
})

const emit = defineEmits(['copy'])

const copied = ref(false)

const copy = async () => {
    try {
        await navigator.clipboard.writeText(props.value)
        copied.value = true
        emit('copy', props.value)
        setTimeout(() => {
            copied.value = false
        }, 2000)
    } catch (err) {
        console.error('Failed to copy:', err)
    }
}

const sizeClasses = {
    sm: 'p-1',
    md: 'p-1.5',
    lg: 'p-2'
}

const iconSizes = {
    sm: 'h-4 w-4',
    md: 'h-5 w-5',
    lg: 'h-6 w-6'
}
</script>

<template>
    <button
        type="button"
        :class="[
            'inline-flex items-center gap-1.5 rounded-md transition-colors',
            sizeClasses[size],
            variant === 'outline'
                ? 'border border-gray-300 hover:bg-gray-100'
                : 'hover:bg-gray-100',
            copied ? 'text-emerald-600' : 'text-gray-500 hover:text-gray-700'
        ]"
        :title="copied ? successLabel : label"
        @click="copy"
    >
        <CheckIcon v-if="copied" :class="iconSizes[size]" />
        <ClipboardDocumentIcon v-else :class="iconSizes[size]" />
        <span v-if="showLabel" class="text-sm">
            {{ copied ? successLabel : label }}
        </span>
    </button>
</template>
