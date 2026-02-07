<template>
  <div class="live-demo">
    <div class="demo-preview">
      <slot></slot>
    </div>
    <div v-if="$slots.code" class="demo-code">
      <div class="code-header">
        <span>Code</span>
        <button class="copy-btn" @click="copyCode" :title="copied ? 'Copied!' : 'Copy'">
          <svg v-if="!copied" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        </button>
      </div>
      <div class="code-content" ref="codeRef">
        <slot name="code"></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const codeRef = ref(null)
const copied = ref(false)

const copyCode = async () => {
  if (!codeRef.value) return

  const code = codeRef.value.textContent
  try {
    await navigator.clipboard.writeText(code)
    copied.value = true
    setTimeout(() => copied.value = false, 2000)
  } catch (err) {
    console.error('Failed to copy:', err)
  }
}
</script>

<style scoped>
.live-demo {
  border: 1px solid var(--c-border);
  border-radius: 8px;
  margin: 1rem 0;
  overflow: hidden;
}

.demo-preview {
  padding: 1.5rem;
  background: var(--c-bg);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.demo-code {
  border-top: 1px solid var(--c-border);
  background: var(--c-bg-light);
}

.code-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 1rem;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--c-text-lighter);
  border-bottom: 1px solid var(--c-border);
}

.copy-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.25rem;
  border: none;
  background: transparent;
  color: var(--c-text-lighter);
  cursor: pointer;
  transition: color 0.2s;
}

.copy-btn:hover {
  color: var(--c-brand);
}

.code-content {
  padding: 1rem;
  overflow-x: auto;
}

.code-content :deep(pre) {
  margin: 0;
}
</style>
