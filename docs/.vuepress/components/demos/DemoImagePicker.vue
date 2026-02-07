<template>
  <div class="demo-image-picker-showcase">
    <span class="demo-label">Upload profile image:</span>
    <div class="demo-image-picker">
      <input
        ref="fileInput"
        type="file"
        accept="image/*"
        class="demo-image-picker__input"
        @change="handleFileChange"
      />
      <div
        v-if="!preview"
        class="demo-image-picker__placeholder"
        @click="$refs.fileInput.click()"
      >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
          <circle cx="8.5" cy="8.5" r="1.5"/>
          <polyline points="21 15 16 10 5 21"/>
        </svg>
        <span>Click to upload image</span>
        <span class="demo-image-picker__hint">PNG, JPG, GIF up to 5MB</span>
      </div>
      <div v-else class="demo-image-picker__preview-wrapper">
        <img :src="preview" alt="Preview" class="demo-image-picker__preview" />
        <div class="demo-image-picker__overlay">
          <button @click="$refs.fileInput.click()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
          </button>
          <button @click="removeImage">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="3 6 5 6 21 6"/>
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
    <span class="demo-selected">{{ fileName ? `Selected: ${fileName}` : 'No image selected' }}</span>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const fileInput = ref(null)
const preview = ref('')
const fileName = ref('')

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    fileName.value = file.name
    const reader = new FileReader()
    reader.onload = (e) => {
      preview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const removeImage = () => {
  preview.value = ''
  fileName.value = ''
}
</script>

<style scoped>
.demo-image-picker-showcase { display: flex; flex-direction: column; gap: 0.75rem; width: 100%; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }

.demo-image-picker {
  width: 100%;
  max-width: 16rem;
}

.demo-image-picker__input {
  display: none;
}

.demo-image-picker__placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 2rem;
  background: #f9fafb;
  border: 2px dashed #d1d5db;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.15s;
}

.demo-image-picker__placeholder:hover {
  background: #f3f4f6;
  border-color: #9ca3af;
}

.demo-image-picker__placeholder svg {
  width: 2.5rem;
  height: 2.5rem;
  color: #9ca3af;
}

.demo-image-picker__placeholder span {
  font-size: 0.875rem;
  color: #6b7280;
}

.demo-image-picker__hint {
  font-size: 0.75rem !important;
  color: #9ca3af !important;
}

.demo-image-picker__preview-wrapper {
  position: relative;
  border-radius: 0.5rem;
  overflow: hidden;
}

.demo-image-picker__preview {
  width: 100%;
  height: auto;
  display: block;
}

.demo-image-picker__overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  opacity: 0;
  transition: opacity 0.15s;
}

.demo-image-picker__preview-wrapper:hover .demo-image-picker__overlay {
  opacity: 1;
}

.demo-image-picker__overlay button {
  display: flex;
  padding: 0.5rem;
  background: white;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: transform 0.15s;
}

.demo-image-picker__overlay button:hover {
  transform: scale(1.1);
}

.demo-image-picker__overlay svg {
  width: 1.25rem;
  height: 1.25rem;
  color: #374151;
}
</style>
