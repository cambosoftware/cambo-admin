<script setup>
import { computed, ref, onMounted } from 'vue'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Saisissez votre texte...'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    error: {
        type: [String, Boolean],
        default: null
    },
    minHeight: {
        type: String,
        default: '200px'
    },
    maxHeight: {
        type: String,
        default: '500px'
    },
    toolbar: {
        type: Array,
        default: () => ['bold', 'italic', 'underline', 'strike', '|', 'h1', 'h2', 'h3', '|', 'ul', 'ol', '|', 'link', 'quote', '|', 'undo', 'redo']
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'focus', 'blur'])

const editorRef = ref(null)
const focused = ref(false)

const hasError = computed(() => !!props.error)

const toolbarButtons = {
    bold: { label: 'B', command: 'bold', title: 'Gras', class: 'font-bold' },
    italic: { label: 'I', command: 'italic', title: 'Italique', class: 'italic' },
    underline: { label: 'U', command: 'underline', title: 'Souligné', class: 'underline' },
    strike: { label: 'S', command: 'strikeThrough', title: 'Barré', class: 'line-through' },
    h1: { label: 'H1', command: 'formatBlock', value: 'h1', title: 'Titre 1', class: 'font-bold text-xs' },
    h2: { label: 'H2', command: 'formatBlock', value: 'h2', title: 'Titre 2', class: 'font-bold text-xs' },
    h3: { label: 'H3', command: 'formatBlock', value: 'h3', title: 'Titre 3', class: 'font-bold text-xs' },
    ul: { label: '•', command: 'insertUnorderedList', title: 'Liste à puces' },
    ol: { label: '1.', command: 'insertOrderedList', title: 'Liste numérotée' },
    link: { label: '🔗', command: 'createLink', title: 'Lien', prompt: true },
    quote: { label: '"', command: 'formatBlock', value: 'blockquote', title: 'Citation', class: 'font-serif' },
    undo: { label: '↩', command: 'undo', title: 'Annuler' },
    redo: { label: '↪', command: 'redo', title: 'Rétablir' }
}

function execCommand(btn) {
    if (props.disabled) return
    editorRef.value?.focus()

    if (btn.prompt) {
        const url = prompt('Entrez l\'URL :')
        if (url) document.execCommand(btn.command, false, url)
    } else if (btn.value) {
        document.execCommand(btn.command, false, btn.value)
    } else {
        document.execCommand(btn.command, false, null)
    }
}

function onInput() {
    const html = editorRef.value?.innerHTML || ''
    emit('update:modelValue', html)
    emit('change', html)
}

function onFocus(e) {
    focused.value = true
    emit('focus', e)
}

function onBlur(e) {
    focused.value = false
    emit('blur', e)
}

function onPaste(e) {
    e.preventDefault()
    const text = e.clipboardData?.getData('text/plain') || ''
    document.execCommand('insertText', false, text)
}

onMounted(() => {
    if (editorRef.value && props.modelValue) {
        editorRef.value.innerHTML = props.modelValue
    }
})
</script>

<template>
    <div
        :class="[
            'rounded-lg border bg-white transition-colors overflow-hidden',
            hasError
                ? 'border-red-300'
                : 'border-gray-300',
            focused ? (hasError ? 'border-red-500 ring-2 ring-red-500/20' : 'border-primary-500 ring-2 ring-primary-500/20') : '',
            disabled ? 'opacity-60' : ''
        ]"
    >
        <!-- Toolbar -->
        <div class="flex flex-wrap items-center gap-0.5 border-b border-gray-200 bg-gray-50 px-2 py-1.5">
            <template v-for="(item, i) in toolbar" :key="i">
                <div v-if="item === '|'" class="mx-1 h-5 w-px bg-gray-300" />
                <button
                    v-else-if="toolbarButtons[item]"
                    type="button"
                    :class="[
                        'inline-flex items-center justify-center rounded px-1.5 py-1 text-xs text-gray-600 hover:bg-gray-200 hover:text-gray-900 cursor-pointer transition-colors',
                        toolbarButtons[item].class || ''
                    ]"
                    :title="toolbarButtons[item].title"
                    :disabled="disabled"
                    @mousedown.prevent="execCommand(toolbarButtons[item])"
                >
                    {{ toolbarButtons[item].label }}
                </button>
            </template>
        </div>

        <!-- Editor -->
        <div
            ref="editorRef"
            :contenteditable="!disabled"
            :class="[
                'prose prose-sm max-w-none px-3 py-2 outline-none overflow-auto',
                disabled ? 'bg-gray-50 cursor-not-allowed' : ''
            ]"
            :style="{ minHeight: minHeight, maxHeight: maxHeight }"
            :data-placeholder="placeholder"
            @input="onInput"
            @focus="onFocus"
            @blur="onBlur"
            @paste="onPaste"
        />
    </div>
</template>

<style scoped>
[contenteditable]:empty::before {
    content: attr(data-placeholder);
    color: #9ca3af;
    pointer-events: none;
}
</style>
