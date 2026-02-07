<template>
  <div class="demo-code-showcase">
    <span class="demo-label">Code editor:</span>
    <div class="demo-code-editor">
      <div class="demo-code-editor__header">
        <select v-model="currentLanguage" class="demo-code-editor__language">
          <option v-for="lang in languages" :key="lang" :value="lang">{{ lang }}</option>
        </select>
        <button class="demo-code-editor__copy" @click="copyCode">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
          </svg>
          {{ copied ? 'Copied!' : 'Copy' }}
        </button>
      </div>
      <div class="demo-code-editor__body">
        <div class="demo-code-editor__lines">
          <span v-for="n in lineCount" :key="n">{{ n }}</span>
        </div>
        <textarea
          ref="codeInput"
          class="demo-code-editor__input"
          v-model="code"
          spellcheck="false"
          @keydown.tab.prevent="handleTab"
        ></textarea>
      </div>
    </div>
    <span class="demo-selected">{{ lineCount }} lines | {{ currentLanguage }}</span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const codeInput = ref(null)
const currentLanguage = ref('javascript')
const copied = ref(false)
const languages = ['javascript', 'typescript', 'python', 'php', 'html', 'css', 'json', 'sql']

const code = ref(`function greet(name) {
  const message = \`Hello, \${name}!\`;
  console.log(message);
  return message;
}

// Call the function
greet('World');`)

const lineCount = computed(() => {
  return Math.max(1, (code.value.match(/\n/g) || []).length + 1)
})

const handleTab = (event) => {
  const textarea = event.target
  const start = textarea.selectionStart
  const end = textarea.selectionEnd
  code.value = code.value.substring(0, start) + '  ' + code.value.substring(end)
  setTimeout(() => {
    textarea.selectionStart = textarea.selectionEnd = start + 2
  }, 0)
}

const copyCode = async () => {
  await navigator.clipboard.writeText(code.value)
  copied.value = true
  setTimeout(() => { copied.value = false }, 2000)
}
</script>

<style scoped>
.demo-code-showcase { display: flex; flex-direction: column; gap: 0.75rem; width: 100%; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }

.demo-code-editor {
  border: 1px solid #1f2937;
  border-radius: 0.375rem;
  overflow: hidden;
  background: #1f2937;
}

.demo-code-editor__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.5rem 0.75rem;
  background: #111827;
  border-bottom: 1px solid #374151;
}

.demo-code-editor__language {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
  color: #9ca3af;
  background: #1f2937;
  border: 1px solid #374151;
  border-radius: 0.25rem;
  outline: none;
}

.demo-code-editor__copy {
  display: flex;
  align-items: center;
  gap: 0.375rem;
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
  color: #9ca3af;
  background: none;
  border: none;
  cursor: pointer;
}

.demo-code-editor__copy:hover { color: white; }
.demo-code-editor__copy svg { width: 0.875rem; height: 0.875rem; }

.demo-code-editor__body {
  display: flex;
  min-height: 10rem;
}

.demo-code-editor__lines {
  display: flex;
  flex-direction: column;
  padding: 0.75rem 0.5rem;
  text-align: right;
  font-size: 0.75rem;
  font-family: ui-monospace, monospace;
  line-height: 1.5;
  color: #6b7280;
  background: #111827;
  user-select: none;
}

.demo-code-editor__input {
  flex: 1;
  padding: 0.75rem;
  font-size: 0.75rem;
  font-family: ui-monospace, monospace;
  line-height: 1.5;
  color: #e5e7eb;
  background: #1f2937;
  border: none;
  outline: none;
  resize: none;
}

.demo-code-editor__input::placeholder {
  color: #6b7280;
}
</style>
