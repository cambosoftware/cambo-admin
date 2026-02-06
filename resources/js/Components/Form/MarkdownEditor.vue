<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Écrivez en Markdown...'
    },
    disabled: {
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
    preview: {
        type: Boolean,
        default: true
    },
    toolbar: {
        type: Array,
        default: () => ['bold', 'italic', 'strike', '|', 'h1', 'h2', 'h3', '|', 'ul', 'ol', 'task', '|', 'link', 'image', 'code', 'quote', '|', 'hr']
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const textareaRef = ref(null)
const activeTab = ref('write')

const hasError = computed(() => !!props.error)

const toolbarActions = {
    bold: { label: 'B', title: 'Gras', prefix: '**', suffix: '**', class: 'font-bold' },
    italic: { label: 'I', title: 'Italique', prefix: '_', suffix: '_', class: 'italic' },
    strike: { label: 'S', title: 'Barré', prefix: '~~', suffix: '~~', class: 'line-through' },
    h1: { label: 'H1', title: 'Titre 1', prefix: '# ', suffix: '', line: true, class: 'font-bold text-xs' },
    h2: { label: 'H2', title: 'Titre 2', prefix: '## ', suffix: '', line: true, class: 'font-bold text-xs' },
    h3: { label: 'H3', title: 'Titre 3', prefix: '### ', suffix: '', line: true, class: 'font-bold text-xs' },
    ul: { label: '•', title: 'Liste', prefix: '- ', suffix: '', line: true },
    ol: { label: '1.', title: 'Liste numérotée', prefix: '1. ', suffix: '', line: true },
    task: { label: '☐', title: 'Tâche', prefix: '- [ ] ', suffix: '', line: true },
    link: { label: '🔗', title: 'Lien', prefix: '[', suffix: '](url)' },
    image: { label: '🖼', title: 'Image', prefix: '![alt](', suffix: ')' },
    code: { label: '`', title: 'Code', prefix: '```\n', suffix: '\n```' },
    quote: { label: '"', title: 'Citation', prefix: '> ', suffix: '', line: true },
    hr: { label: '—', title: 'Ligne horizontale', prefix: '\n---\n', suffix: '' }
}

// Simple markdown to HTML for preview
const renderedHtml = computed(() => {
    if (!props.modelValue) return ''
    let html = props.modelValue
        // Escape HTML
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        // Headers
        .replace(/^### (.+)$/gm, '<h3 class="text-lg font-semibold mt-3 mb-1">$1</h3>')
        .replace(/^## (.+)$/gm, '<h2 class="text-xl font-semibold mt-4 mb-1">$1</h2>')
        .replace(/^# (.+)$/gm, '<h1 class="text-2xl font-bold mt-4 mb-2">$1</h1>')
        // Bold, italic, strike
        .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
        .replace(/_(.+?)_/g, '<em>$1</em>')
        .replace(/~~(.+?)~~/g, '<del>$1</del>')
        // Code blocks
        .replace(/```[\s\S]*?```/g, (match) => {
            const code = match.replace(/^```\w*\n?/, '').replace(/\n?```$/, '')
            return `<pre class="bg-gray-100 rounded p-2 text-xs my-2 overflow-x-auto"><code>${code}</code></pre>`
        })
        // Inline code
        .replace(/`(.+?)`/g, '<code class="bg-gray-100 rounded px-1 text-xs">$1</code>')
        // Links and images
        .replace(/!\[([^\]]*)\]\(([^)]+)\)/g, '<img src="$2" alt="$1" class="max-w-full rounded my-2" />')
        .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="text-primary-600 underline">$1</a>')
        // Lists
        .replace(/^- \[x\] (.+)$/gm, '<div class="flex items-center gap-2"><input type="checkbox" checked disabled class="rounded" /><span>$1</span></div>')
        .replace(/^- \[ \] (.+)$/gm, '<div class="flex items-center gap-2"><input type="checkbox" disabled class="rounded" /><span>$1</span></div>')
        .replace(/^- (.+)$/gm, '<li class="ml-4">$1</li>')
        .replace(/^\d+\. (.+)$/gm, '<li class="ml-4 list-decimal">$1</li>')
        // Blockquote
        .replace(/^&gt; (.+)$/gm, '<blockquote class="border-l-4 border-gray-300 pl-3 text-gray-600 my-2">$1</blockquote>')
        // HR
        .replace(/^---$/gm, '<hr class="my-3 border-gray-200" />')
        // Paragraphs
        .replace(/\n\n/g, '</p><p class="my-1">')
        .replace(/\n/g, '<br />')
    return `<p class="my-1">${html}</p>`
})

function insertAtCursor(action) {
    if (props.disabled) return
    const textarea = textareaRef.value
    if (!textarea) return

    const start = textarea.selectionStart
    const end = textarea.selectionEnd
    const text = props.modelValue
    const selected = text.substring(start, end) || 'texte'

    let newText
    if (action.line) {
        // Line-level: insert at beginning of line
        const lineStart = text.lastIndexOf('\n', start - 1) + 1
        newText = text.substring(0, lineStart) + action.prefix + text.substring(lineStart)
    } else {
        newText = text.substring(0, start) + action.prefix + selected + action.suffix + text.substring(end)
    }

    emit('update:modelValue', newText)
    emit('change', newText)

    // Restore cursor
    setTimeout(() => {
        const cursorPos = action.line ? start + action.prefix.length : start + action.prefix.length + selected.length
        textarea.focus()
        textarea.setSelectionRange(cursorPos, cursorPos)
    }, 0)
}
</script>

<template>
    <div
        :class="[
            'rounded-lg border bg-white overflow-hidden',
            hasError ? 'border-red-300' : 'border-gray-300',
            disabled ? 'opacity-60' : ''
        ]"
    >
        <!-- Tabs + Toolbar -->
        <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-2 py-1">
            <!-- Tabs -->
            <div v-if="preview" class="flex gap-1">
                <button
                    type="button"
                    :class="[
                        'rounded px-2.5 py-1 text-xs font-medium cursor-pointer transition-colors',
                        activeTab === 'write' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'
                    ]"
                    @click="activeTab = 'write'"
                >
                    Écrire
                </button>
                <button
                    type="button"
                    :class="[
                        'rounded px-2.5 py-1 text-xs font-medium cursor-pointer transition-colors',
                        activeTab === 'preview' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'
                    ]"
                    @click="activeTab = 'preview'"
                >
                    Aperçu
                </button>
            </div>

            <!-- Toolbar -->
            <div v-if="activeTab === 'write'" class="flex flex-wrap items-center gap-0.5">
                <template v-for="(item, i) in toolbar" :key="i">
                    <div v-if="item === '|'" class="mx-0.5 h-4 w-px bg-gray-300" />
                    <button
                        v-else-if="toolbarActions[item]"
                        type="button"
                        :class="[
                            'inline-flex items-center justify-center rounded px-1.5 py-0.5 text-xs text-gray-600 hover:bg-gray-200 cursor-pointer',
                            toolbarActions[item].class || ''
                        ]"
                        :title="toolbarActions[item].title"
                        :disabled="disabled"
                        @click="insertAtCursor(toolbarActions[item])"
                    >
                        {{ toolbarActions[item].label }}
                    </button>
                </template>
            </div>
        </div>

        <!-- Write mode -->
        <textarea
            v-show="activeTab === 'write'"
            ref="textareaRef"
            :value="modelValue"
            :placeholder="placeholder"
            :disabled="disabled"
            :rows="rows"
            :class="[
                'block w-full resize-y border-0 bg-white px-3 py-2 font-mono text-sm text-gray-900',
                'placeholder:text-gray-400 focus:outline-none focus:ring-0',
                disabled ? 'bg-gray-50 cursor-not-allowed' : ''
            ]"
            @input="e => { emit('update:modelValue', e.target.value); emit('change', e.target.value) }"
        />

        <!-- Preview mode -->
        <div
            v-if="activeTab === 'preview'"
            class="prose prose-sm max-w-none px-3 py-2 text-gray-900"
            :style="{ minHeight: rows * 1.5 + 'em' }"
            v-html="renderedHtml"
        />
    </div>
</template>
