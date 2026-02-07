<template>
  <div class="demo-file-picker-showcase">
    <span class="demo-label">Upload files:</span>
    <div class="demo-file-picker">
      <input
        ref="fileInput"
        type="file"
        multiple
        class="demo-file-picker__input"
        @change="handleFileChange"
      />
      <button class="demo-file-picker__button" @click="$refs.fileInput.click()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="17 8 12 3 7 8"/>
          <line x1="12" y1="3" x2="12" y2="15"/>
        </svg>
        <span>Choose files</span>
      </button>
      <div v-if="files.length > 0" class="demo-file-picker__files">
        <div v-for="(file, index) in files" :key="index" class="demo-file-picker__file">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
          </svg>
          <div class="demo-file-picker__file-info">
            <span class="demo-file-picker__file-name">{{ file.name }}</span>
            <span class="demo-file-picker__file-size">{{ formatSize(file.size) }}</span>
          </div>
          <button class="demo-file-picker__file-remove" @click="removeFile(index)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
    <span class="demo-selected">{{ files.length }} file(s) selected</span>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const fileInput = ref(null)
const files = ref([])

const handleFileChange = (event) => {
  const selectedFiles = Array.from(event.target.files)
  files.value = [...files.value, ...selectedFiles]
}

const removeFile = (index) => {
  files.value.splice(index, 1)
}

const formatSize = (bytes) => {
  if (bytes === 0) return '0 B'
  const k = 1024
  const sizes = ['B', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i]
}
</script>

<style scoped>
.demo-file-picker-showcase { display: flex; flex-direction: column; gap: 0.75rem; width: 100%; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }

.demo-file-picker {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.demo-file-picker__input {
  display: none;
}

.demo-file-picker__button {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  background: white;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: all 0.15s;
  width: fit-content;
}

.demo-file-picker__button:hover {
  background: #f9fafb;
  border-color: #9ca3af;
}

.demo-file-picker__button svg {
  width: 1.25rem;
  height: 1.25rem;
  color: #6b7280;
}

.demo-file-picker__files {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.demo-file-picker__file {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 0.75rem;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
}

.demo-file-picker__file > svg {
  width: 1.5rem;
  height: 1.5rem;
  color: #6b7280;
  flex-shrink: 0;
}

.demo-file-picker__file-info {
  flex: 1;
  min-width: 0;
}

.demo-file-picker__file-name {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.demo-file-picker__file-size {
  font-size: 0.75rem;
  color: #6b7280;
}

.demo-file-picker__file-remove {
  display: flex;
  padding: 0.25rem;
  background: none;
  border: none;
  cursor: pointer;
  color: #9ca3af;
  border-radius: 0.25rem;
}

.demo-file-picker__file-remove:hover {
  color: #ef4444;
  background: #fef2f2;
}

.demo-file-picker__file-remove svg {
  width: 1rem;
  height: 1rem;
}
</style>
