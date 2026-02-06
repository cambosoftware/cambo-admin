<script setup>
import { ref } from 'vue'
import { ClipboardDocumentIcon, CheckIcon } from '@heroicons/vue/20/solid'
import Tooltip from '@/Components/UI/Tooltip.vue'

const props = defineProps({
    value: {
        type: String,
        required: true
    },
    display: {
        type: String,
        default: null
    },
    successMessage: {
        type: String,
        default: 'Copié !'
    },
    showIcon: {
        type: Boolean,
        default: true
    },
    truncate: {
        type: Boolean,
        default: false
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
</script>

<template>
    <Tooltip :content="copied ? successMessage : 'Cliquer pour copier'">
        <button
            type="button"
            :class="[
                'inline-flex items-center gap-1 text-left group',
                'text-gray-900 hover:text-primary-600 transition-colors',
                truncate ? 'max-w-full' : ''
            ]"
            @click="copy"
        >
            <span :class="truncate ? 'truncate' : ''">
                {{ display || value }}
            </span>
            <span v-if="showIcon" class="flex-shrink-0">
                <CheckIcon v-if="copied" class="h-4 w-4 text-emerald-500" />
                <ClipboardDocumentIcon
                    v-else
                    class="h-4 w-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"
                />
            </span>
        </button>
    </Tooltip>
</template>
