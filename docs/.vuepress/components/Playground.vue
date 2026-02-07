<template>
  <div class="playground">
    <div class="playground-header">
      <span class="playground-title">Playground</span>
      <div class="playground-actions">
        <button class="playground-btn" @click="resetProps" title="Reset">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
        </button>
        <button class="playground-btn" @click="copyCode" title="Copy Code">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
        </button>
      </div>
    </div>

    <div class="playground-content">
      <!-- Live Preview -->
      <div class="playground-preview">
        <div class="preview-label">Preview</div>
        <div class="preview-area">
          <slot :props="currentProps"></slot>
        </div>
      </div>

      <!-- Props Controls -->
      <div class="playground-controls">
        <div class="controls-label">Props</div>
        <div class="controls-list">
          <div v-for="(prop, key) in propsConfig" :key="key" class="control-item">
            <label class="control-label">{{ key }}</label>

            <!-- Boolean -->
            <template v-if="prop.type === 'boolean'">
              <button
                class="control-toggle"
                :class="{ active: currentProps[key] }"
                @click="currentProps[key] = !currentProps[key]"
              >
                {{ currentProps[key] ? 'true' : 'false' }}
              </button>
            </template>

            <!-- Select -->
            <template v-else-if="prop.type === 'select'">
              <select class="control-select" v-model="currentProps[key]">
                <option v-for="opt in prop.options" :key="opt" :value="opt">{{ opt }}</option>
              </select>
            </template>

            <!-- String -->
            <template v-else-if="prop.type === 'string'">
              <input type="text" class="control-input" v-model="currentProps[key]" />
            </template>

            <!-- Number -->
            <template v-else-if="prop.type === 'number'">
              <input type="number" class="control-input" v-model.number="currentProps[key]" />
            </template>
          </div>
        </div>
      </div>
    </div>

    <!-- Generated Code -->
    <div class="playground-code">
      <div class="code-label">Generated Code</div>
      <pre class="code-block"><code>{{ generatedCode }}</code></pre>
    </div>

    <!-- Copy notification -->
    <div v-if="copied" class="copy-notification">Copied!</div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'

const props = defineProps({
  component: {
    type: String,
    required: true
  },
  props: {
    type: Object,
    default: () => ({})
  },
  defaults: {
    type: Object,
    default: () => ({})
  }
})

const propsConfig = props.props
const initialProps = { ...props.defaults }

const currentProps = reactive({ ...initialProps })
const copied = ref(false)

const resetProps = () => {
  Object.keys(initialProps).forEach(key => {
    currentProps[key] = initialProps[key]
  })
}

const generatedCode = computed(() => {
  let propsStr = ''

  Object.entries(currentProps).forEach(([key, value]) => {
    if (value === initialProps[key] && value === false) return
    if (value === initialProps[key] && value === '') return

    if (typeof value === 'boolean') {
      if (value) propsStr += ` ${key}`
    } else if (typeof value === 'string') {
      propsStr += ` ${key}="${value}"`
    } else {
      propsStr += ` :${key}="${value}"`
    }
  })

  return `<${props.component}${propsStr}>Content</${props.component}>`
})

const copyCode = async () => {
  try {
    await navigator.clipboard.writeText(generatedCode.value)
    copied.value = true
    setTimeout(() => copied.value = false, 2000)
  } catch (err) {
    console.error('Failed to copy:', err)
  }
}
</script>

<style scoped>
.playground {
  border: 1px solid var(--c-border);
  border-radius: 8px;
  margin: 1.5rem 0;
  overflow: hidden;
  background: var(--c-bg);
}

.playground-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 1rem;
  background: var(--c-bg-light);
  border-bottom: 1px solid var(--c-border);
}

.playground-title {
  font-weight: 600;
  font-size: 0.875rem;
  color: var(--c-text);
}

.playground-actions {
  display: flex;
  gap: 0.5rem;
}

.playground-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border: 1px solid var(--c-border);
  border-radius: 6px;
  background: var(--c-bg);
  color: var(--c-text-lighter);
  cursor: pointer;
  transition: all 0.2s;
}

.playground-btn:hover {
  border-color: var(--c-brand);
  color: var(--c-brand);
}

.playground-content {
  display: grid;
  grid-template-columns: 1fr 300px;
  min-height: 200px;
}

@media (max-width: 768px) {
  .playground-content {
    grid-template-columns: 1fr;
  }
}

.playground-preview {
  padding: 1rem;
  border-right: 1px solid var(--c-border);
}

.playground-controls {
  padding: 1rem;
  background: var(--c-bg-light);
}

.preview-label,
.controls-label,
.code-label {
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--c-text-lighter);
  margin-bottom: 0.75rem;
}

.preview-area {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 120px;
  padding: 1rem;
  background: var(--c-bg);
  border: 1px dashed var(--c-border);
  border-radius: 6px;
}

.controls-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.control-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.control-label {
  font-size: 0.8125rem;
  font-weight: 500;
  color: var(--c-text);
}

.control-toggle {
  padding: 0.375rem 0.75rem;
  border: 1px solid var(--c-border);
  border-radius: 4px;
  background: var(--c-bg);
  color: var(--c-text-lighter);
  font-size: 0.8125rem;
  cursor: pointer;
  transition: all 0.2s;
}

.control-toggle.active {
  background: var(--c-brand);
  border-color: var(--c-brand);
  color: white;
}

.control-select,
.control-input {
  padding: 0.375rem 0.5rem;
  border: 1px solid var(--c-border);
  border-radius: 4px;
  background: var(--c-bg);
  color: var(--c-text);
  font-size: 0.8125rem;
}

.control-select:focus,
.control-input:focus {
  outline: none;
  border-color: var(--c-brand);
}

.playground-code {
  padding: 1rem;
  border-top: 1px solid var(--c-border);
  background: var(--c-bg-light);
}

.code-block {
  margin: 0;
  padding: 0.75rem;
  background: #1e1e1e;
  border-radius: 6px;
  overflow-x: auto;
}

.code-block code {
  color: #d4d4d4;
  font-size: 0.8125rem;
  font-family: var(--font-family-code);
}

.copy-notification {
  position: fixed;
  bottom: 1rem;
  right: 1rem;
  padding: 0.5rem 1rem;
  background: var(--c-brand);
  color: white;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 500;
  z-index: 1000;
  animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
