<template>
  <div class="demo-rich-text-showcase">
    <span class="demo-label">Rich text editor:</span>
    <div class="demo-rich-text-editor">
      <div class="demo-rich-text-editor__toolbar">
        <button @click="execCommand('bold')" :class="{ active: isBold }" title="Bold">
          <strong>B</strong>
        </button>
        <button @click="execCommand('italic')" :class="{ active: isItalic }" title="Italic">
          <em>I</em>
        </button>
        <button @click="execCommand('underline')" :class="{ active: isUnderline }" title="Underline">
          <u>U</u>
        </button>
        <span class="demo-rich-text-editor__divider"></span>
        <button @click="execCommand('insertUnorderedList')" title="Bullet List">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="8" y1="6" x2="21" y2="6"/>
            <line x1="8" y1="12" x2="21" y2="12"/>
            <line x1="8" y1="18" x2="21" y2="18"/>
            <line x1="3" y1="6" x2="3.01" y2="6"/>
            <line x1="3" y1="12" x2="3.01" y2="12"/>
            <line x1="3" y1="18" x2="3.01" y2="18"/>
          </svg>
        </button>
        <button @click="execCommand('insertOrderedList')" title="Numbered List">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="10" y1="6" x2="21" y2="6"/>
            <line x1="10" y1="12" x2="21" y2="12"/>
            <line x1="10" y1="18" x2="21" y2="18"/>
            <path d="M4 6h1v4"/>
            <path d="M4 10h2"/>
            <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/>
          </svg>
        </button>
        <span class="demo-rich-text-editor__divider"></span>
        <button @click="execCommand('createLink')" title="Insert Link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
          </svg>
        </button>
      </div>
      <div
        ref="editor"
        class="demo-rich-text-editor__content"
        contenteditable="true"
        @input="handleInput"
        @blur="updateState"
        @mouseup="updateState"
        @keyup="updateState"
      ></div>
    </div>
    <span class="demo-selected">{{ charCount }} characters</span>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'

const editor = ref(null)
const content = ref('')
const isBold = ref(false)
const isItalic = ref(false)
const isUnderline = ref(false)

const charCount = computed(() => {
  return content.value.replace(/<[^>]*>/g, '').length
})

const handleInput = () => {
  content.value = editor.value.innerHTML
}

const execCommand = (command) => {
  if (command === 'createLink') {
    const url = prompt('Enter URL:')
    if (url) {
      document.execCommand(command, false, url)
    }
  } else {
    document.execCommand(command, false, null)
  }
  editor.value.focus()
  updateState()
}

const updateState = () => {
  isBold.value = document.queryCommandState('bold')
  isItalic.value = document.queryCommandState('italic')
  isUnderline.value = document.queryCommandState('underline')
}

onMounted(() => {
  // Set default content
  editor.value.innerHTML = '<p>This is a <strong>rich text</strong> editor with <em>formatting</em> options.</p><p>Try selecting text and clicking the toolbar buttons!</p>'
  content.value = editor.value.innerHTML
})
</script>

<style scoped>
.demo-rich-text-showcase { display: flex; flex-direction: column; gap: 0.75rem; width: 100%; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }

.demo-rich-text-editor {
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  overflow: hidden;
}

.demo-rich-text-editor__toolbar {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.5rem;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

.demo-rich-text-editor__toolbar button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  font-size: 0.875rem;
  background: none;
  border: none;
  border-radius: 0.25rem;
  cursor: pointer;
  color: #374151;
}

.demo-rich-text-editor__toolbar button:hover {
  background: #e5e7eb;
}

.demo-rich-text-editor__toolbar button.active {
  background: #e0e7ff;
  color: #4f46e5;
}

.demo-rich-text-editor__toolbar button svg {
  width: 1rem;
  height: 1rem;
}

.demo-rich-text-editor__divider {
  width: 1px;
  height: 1.5rem;
  background: #d1d5db;
  margin: 0 0.25rem;
}

.demo-rich-text-editor__content {
  min-height: 8rem;
  padding: 0.75rem;
  font-size: 0.875rem;
  line-height: 1.5;
  outline: none;
}

.demo-rich-text-editor__content:empty::before {
  content: 'Start typing...';
  color: #9ca3af;
}
</style>
