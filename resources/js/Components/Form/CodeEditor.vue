<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    language: {
        type: String,
        default: 'javascript'
    },
    placeholder: {
        type: String,
        default: '// Écrivez votre code ici...'
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
    rows: {
        type: Number,
        default: 10
    },
    showLineNumbers: {
        type: Boolean,
        default: true
    },
    tabSize: {
        type: Number,
        default: 2
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'focus', 'blur'])

const textareaRef = ref(null)
const focused = ref(false)

const hasError = computed(() => !!props.error)

const lineCount = computed(() => {
    const count = (props.modelValue || '').split('\n').length
    return Math.max(count, props.rows)
})

const lineNumbers = computed(() =>
    Array.from({ length: lineCount.value }, (_, i) => i + 1)
)

function onInput(e) {
    emit('update:modelValue', e.target.value)
    emit('change', e.target.value)
}

function onKeydown(e) {
    // Tab support
    if (e.key === 'Tab') {
        e.preventDefault()
        const textarea = textareaRef.value
        const start = textarea.selectionStart
        const end = textarea.selectionEnd
        const tab = ' '.repeat(props.tabSize)

        if (e.shiftKey) {
            // Unindent
            const lineStart = props.modelValue.lastIndexOf('\n', start - 1) + 1
            const line = props.modelValue.substring(lineStart, end)
            if (line.startsWith(tab)) {
                const newText = props.modelValue.substring(0, lineStart) + line.substring(tab.length) + props.modelValue.substring(end)
                emit('update:modelValue', newText)
                setTimeout(() => textarea.setSelectionRange(start - tab.length, start - tab.length), 0)
            }
        } else {
            // Indent
            const newText = props.modelValue.substring(0, start) + tab + props.modelValue.substring(end)
            emit('update:modelValue', newText)
            setTimeout(() => textarea.setSelectionRange(start + tab.length, start + tab.length), 0)
        }
    }

    // Auto-close brackets
    const pairs = { '(': ')', '[': ']', '{': '}', '"': '"', "'": "'", '`': '`' }
    if (pairs[e.key]) {
        const textarea = textareaRef.value
        const start = textarea.selectionStart
        const end = textarea.selectionEnd
        if (start !== end) {
            e.preventDefault()
            const selected = props.modelValue.substring(start, end)
            const newText = props.modelValue.substring(0, start) + e.key + selected + pairs[e.key] + props.modelValue.substring(end)
            emit('update:modelValue', newText)
            setTimeout(() => textarea.setSelectionRange(start + 1, end + 1), 0)
        }
    }

    // Enter auto-indent
    if (e.key === 'Enter') {
        const textarea = textareaRef.value
        const start = textarea.selectionStart
        const lineStart = props.modelValue.lastIndexOf('\n', start - 1) + 1
        const currentLine = props.modelValue.substring(lineStart, start)
        const indent = currentLine.match(/^\s*/)?.[0] || ''
        if (indent) {
            e.preventDefault()
            const newText = props.modelValue.substring(0, start) + '\n' + indent + props.modelValue.substring(start)
            emit('update:modelValue', newText)
            setTimeout(() => {
                const pos = start + 1 + indent.length
                textarea.setSelectionRange(pos, pos)
            }, 0)
        }
    }
}
</script>

<template>
    <div
        :class="[
            'rounded-lg border bg-gray-900 overflow-hidden font-mono text-sm',
            hasError ? 'border-red-500' : 'border-gray-700',
            focused ? 'ring-2 ring-primary-500/20' : '',
            disabled ? 'opacity-60' : ''
        ]"
    >
        <!-- Language badge -->
        <div class="flex items-center justify-between border-b border-gray-700 bg-gray-800 px-3 py-1.5">
            <span class="text-xs font-medium text-gray-400">{{ language }}</span>
            <span class="text-xs text-gray-500">{{ (modelValue || '').split('\n').length }} lignes</span>
        </div>

        <!-- Editor -->
        <div class="flex">
            <!-- Line numbers -->
            <div
                v-if="showLineNumbers"
                class="select-none border-r border-gray-700 bg-gray-800/50 py-2 text-right"
                style="min-width: 3rem;"
            >
                <div
                    v-for="n in lineNumbers"
                    :key="n"
                    class="px-2 text-xs leading-5 text-gray-600"
                >
                    {{ n }}
                </div>
            </div>

            <!-- Textarea -->
            <textarea
                ref="textareaRef"
                :value="modelValue"
                :placeholder="placeholder"
                :disabled="disabled"
                :readonly="readonly"
                :rows="rows"
                spellcheck="false"
                :class="[
                    'flex-1 resize-none border-0 bg-transparent px-3 py-2 text-gray-100 leading-5',
                    'placeholder:text-gray-600 focus:outline-none focus:ring-0',
                    disabled ? 'cursor-not-allowed' : ''
                ]"
                :style="{ tabSize: tabSize }"
                @input="onInput"
                @keydown="onKeydown"
                @focus="focused = true; $emit('focus', $event)"
                @blur="focused = false; $emit('blur', $event)"
            />
        </div>
    </div>
</template>
