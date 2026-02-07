<template>
  <div class="demo-dropzone-showcase">
    <span class="demo-label">Drag and drop files:</span>
    <div
      :class="['demo-file-dropzone', { 'demo-file-dropzone--dragover': isDragOver }]"
      @dragover.prevent="isDragOver = true"
      @dragleave.prevent="isDragOver = false"
      @drop.prevent="handleDrop"
      @click="$refs.fileInput.click()"
    >
      <input
        ref="fileInput"
        type="file"
        multiple
        class="demo-file-dropzone__input"
        @change="handleFileChange"
      />
      <div v-if="files.length === 0" class="demo-file-dropzone__empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="17 8 12 3 7 8"/>
          <line x1="12" y1="3" x2="12" y2="15"/>
        </svg>
        <p class="demo-file-dropzone__text">
          <span class="demo-file-dropzone__link">Click to upload</span> or drag and drop
        </p>
        <p class="demo-file-dropzone__hint">PNG, JPG, PDF up to 10MB each</p>
      </div>
      <div v-else class="demo-file-dropzone__files">
        <div v-for="(file, index) in files" :key="index" class="demo-file-dropzone__file">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
          </svg>
          <div class="demo-file-dropzone__file-info">
            <span class="demo-file-dropzone__file-name">{{ file.name }}</span>
            <span class="demo-file-dropzone__file-size">{{ formatSize(file.size) }}</span>
          </div>
          <button class="demo-file-dropzone__file-remove" @click.stop="removeFile(index)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <button class="demo-file-dropzone__add-more" @click.stop="$refs.fileInput.click()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
          Add more files
        </button>
      </div>
    </div>
    <span class="demo-selected">{{ files.length }} file(s) uploaded</span>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const fileInput = ref(null)
const isDragOver = ref(false)
const files = ref([])

const handleFileChange = (event) => {
  addFiles(Array.from(event.target.files))
}

const handleDrop = (event) => {
  isDragOver.value = false
  addFiles(Array.from(event.dataTransfer.files))
}

const addFiles = (newFiles) => {
  files.value = [...files.value, ...newFiles]
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
.demo-dropzone-showcase { display: flex; flex-direction: column; gap: 0.75rem; width: 100%; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }

.demo-file-dropzone {
  padding: 2rem;
  background: #f9fafb;
  border: 2px dashed #d1d5db;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.15s;
}

.demo-file-dropzone:hover {
  background: #f3f4f6;
  border-color: #9ca3af;
}

.demo-file-dropzone--dragover {
  background: #eef2ff;
  border-color: #6366f1;
}

.demo-file-dropzone__input {
  display: none;
}

.demo-file-dropzone__empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.demo-file-dropzone__empty svg {
  width: 2.5rem;
  height: 2.5rem;
  color: #9ca3af;
}

.demo-file-dropzone__text {
  font-size: 0.875rem;
  color: #6b7280;
  margin: 0;
}

.demo-file-dropzone__link {
  color: #6366f1;
  font-weight: 500;
}

.demo-file-dropzone__hint {
  font-size: 0.75rem;
  color: #9ca3af;
  margin: 0;
}

.demo-file-dropzone__files {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.demo-file-dropzone__file {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 0.75rem;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
}

.demo-file-dropzone__file > svg {
  width: 1.5rem;
  height: 1.5rem;
  color: #6b7280;
  flex-shrink: 0;
}

.demo-file-dropzone__file-info {
  flex: 1;
  min-width: 0;
}

.demo-file-dropzone__file-name {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.demo-file-dropzone__file-size {
  font-size: 0.75rem;
  color: #6b7280;
}

.demo-file-dropzone__file-remove {
  display: flex;
  padding: 0.25rem;
  background: none;
  border: none;
  cursor: pointer;
  color: #9ca3af;
  border-radius: 0.25rem;
}

.demo-file-dropzone__file-remove:hover {
  color: #ef4444;
  background: #fef2f2;
}

.demo-file-dropzone__file-remove svg {
  width: 1rem;
  height: 1rem;
}

.demo-file-dropzone__add-more {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.5rem;
  font-size: 0.875rem;
  color: #6366f1;
  background: none;
  border: 1px dashed #c7d2fe;
  border-radius: 0.375rem;
  cursor: pointer;
}

.demo-file-dropzone__add-more:hover {
  background: #eef2ff;
}

.demo-file-dropzone__add-more svg {
  width: 1rem;
  height: 1rem;
}
</style>
