<template>
  <div class="demo-markdown-showcase">
    <span class="demo-label">Markdown editor:</span>
    <div class="demo-markdown-editor">
      <div class="demo-markdown-editor__toolbar">
        <button @click="insertMarkdown('**', '**')" title="Bold">
          <strong>B</strong>
        </button>
        <button @click="insertMarkdown('*', '*')" title="Italic">
          <em>I</em>
        </button>
        <button @click="insertMarkdown('~~', '~~')" title="Strikethrough">
          <s>S</s>
        </button>
        <span class="demo-markdown-editor__divider"></span>
        <button @click="insertMarkdown('# ', '')" title="Heading">
          H1
        </button>
        <button @click="insertMarkdown('## ', '')" title="Heading 2">
          H2
        </button>
        <button @click="insertMarkdown('### ', '')" title="Heading 3">
          H3
        </button>
        <span class="demo-markdown-editor__divider"></span>
        <button @click="insertMarkdown('- ', '')" title="Bullet List">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="8" y1="6" x2="21" y2="6"/>
            <line x1="8" y1="12" x2="21" y2="12"/>
            <line x1="8" y1="18" x2="21" y2="18"/>
            <line x1="3" y1="6" x2="3.01" y2="6"/>
            <line x1="3" y1="12" x2="3.01" y2="12"/>
            <line x1="3" y1="18" x2="3.01" y2="18"/>
          </svg>
        </button>
        <button @click="insertMarkdown('`', '`')" title="Inline Code">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="16 18 22 12 16 6"/>
            <polyline points="8 6 2 12 8 18"/>
          </svg>
        </button>
        <button @click="insertMarkdown('[', '](url)')" title="Link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
          </svg>
        </button>
      </div>
      <div class="demo-markdown-editor__body">
        <textarea
          ref="textarea"
          class="demo-markdown-editor__input"
          v-model="content"
          placeholder="Write markdown here..."
        ></textarea>
        <div v-if="preview" class="demo-markdown-editor__preview" v-html="renderedMarkdown"></div>
      </div>
      <div class="demo-markdown-editor__footer">
        <label class="demo-markdown-editor__toggle">
          <input type="checkbox" v-model="preview" />
          <span>Preview</span>
        </label>
      </div>
    </div>
    <span class="demo-selected">{{ content.length }} characters</span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const textarea = ref(null)
const preview = ref(false)
const content = ref(`# Welcome to Markdown

This is a **bold** text and this is *italic*.

## Features
- Easy to write
- Fast to render
- \`Code support\`

[Learn more](https://example.com)`)

const renderedMarkdown = computed(() => {
  let html = content.value
    .replace(/^### (.*$)/gim, '<h3>$1</h3>')
    .replace(/^## (.*$)/gim, '<h2>$1</h2>')
    .replace(/^# (.*$)/gim, '<h1>$1</h1>')
    .replace(/\*\*(.*?)\*\*/gim, '<strong>$1</strong>')
    .replace(/\*(.*?)\*/gim, '<em>$1</em>')
    .replace(/~~(.*?)~~/gim, '<del>$1</del>')
    .replace(/`([^`]+)`/gim, '<code>$1</code>')
    .replace(/\[([^\]]+)\]\(([^)]+)\)/gim, '<a href="$2">$1</a>')
    .replace(/^- (.*$)/gim, '<li>$1</li>')
    .replace(/\n/gim, '<br>')
  return html
})

const insertMarkdown = (before, after) => {
  const start = textarea.value.selectionStart
  const end = textarea.value.selectionEnd
  const text = content.value
  const selected = text.substring(start, end)
  content.value = text.substring(0, start) + before + selected + after + text.substring(end)
  setTimeout(() => {
    textarea.value.focus()
    textarea.value.setSelectionRange(start + before.length, end + before.length)
  }, 0)
}
</script>

<style scoped>
.demo-markdown-showcase { display: flex; flex-direction: column; gap: 0.75rem; width: 100%; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }

.demo-markdown-editor {
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  overflow: hidden;
}

.demo-markdown-editor__toolbar {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.5rem;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

.demo-markdown-editor__toolbar button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  font-size: 0.75rem;
  background: none;
  border: none;
  border-radius: 0.25rem;
  cursor: pointer;
  color: #374151;
}

.demo-markdown-editor__toolbar button:hover { background: #e5e7eb; }
.demo-markdown-editor__toolbar button svg { width: 1rem; height: 1rem; }
.demo-markdown-editor__divider { width: 1px; height: 1.5rem; background: #d1d5db; margin: 0 0.25rem; }

.demo-markdown-editor__body { display: flex; min-height: 10rem; }

.demo-markdown-editor__input {
  flex: 1;
  padding: 0.75rem;
  font-size: 0.875rem;
  font-family: ui-monospace, monospace;
  line-height: 1.5;
  border: none;
  outline: none;
  resize: vertical;
}

.demo-markdown-editor__preview {
  flex: 1;
  padding: 0.75rem;
  font-size: 0.875rem;
  line-height: 1.5;
  background: #f9fafb;
  border-left: 1px solid #e5e7eb;
  overflow-y: auto;
}

.demo-markdown-editor__preview h1 { font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem; }
.demo-markdown-editor__preview h2 { font-size: 1.25rem; font-weight: bold; margin-bottom: 0.5rem; }
.demo-markdown-editor__preview h3 { font-size: 1rem; font-weight: bold; margin-bottom: 0.5rem; }
.demo-markdown-editor__preview code { background: #e5e7eb; padding: 0.125rem 0.25rem; border-radius: 0.25rem; font-size: 0.875em; }
.demo-markdown-editor__preview a { color: #6366f1; }

.demo-markdown-editor__footer {
  display: flex;
  justify-content: flex-end;
  padding: 0.5rem;
  border-top: 1px solid #e5e7eb;
  background: #f9fafb;
}

.demo-markdown-editor__toggle {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.75rem;
  color: #6b7280;
  cursor: pointer;
}

.demo-markdown-editor__toggle input { width: 1rem; height: 1rem; }
</style>
