<template>
  <div class="demo-tag-input-showcase">
    <span class="demo-label">Add tags (press Enter):</span>
    <div :class="['demo-tag-input', { 'demo-tag-input--focused': isFocused }]" @click="$refs.input.focus()">
      <span v-for="(tag, i) in tags" :key="i" class="demo-tag">
        {{ tag }}
        <button @click.stop="removeTag(i)">×</button>
      </span>
      <input
        ref="input"
        v-model="inputValue"
        type="text"
        :placeholder="tags.length === 0 ? 'Type and press Enter...' : ''"
        @keydown.enter.prevent="addTag"
        @keydown.backspace="handleBackspace"
        @focus="isFocused = true"
        @blur="isFocused = false"
      />
    </div>
    <span class="demo-selected">Tags: {{ tags.join(', ') || 'None' }}</span>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const tags = ref(['Vue.js', 'Laravel', 'Tailwind'])
const inputValue = ref('')
const isFocused = ref(false)

const addTag = () => {
  const val = inputValue.value.trim()
  if (val && !tags.value.includes(val)) {
    tags.value.push(val)
    inputValue.value = ''
  }
}

const removeTag = (index) => {
  tags.value.splice(index, 1)
}

const handleBackspace = () => {
  if (inputValue.value === '' && tags.value.length > 0) {
    tags.value.pop()
  }
}
</script>

<style scoped>
.demo-tag-input-showcase { display: flex; flex-direction: column; gap: 0.5rem; width: 100%; max-width: 24rem; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }
.demo-tag-input { display: flex; flex-wrap: wrap; gap: 0.375rem; padding: 0.375rem 0.5rem; min-height: 2.5rem; background: white; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: text; transition: border-color 0.15s, box-shadow 0.15s; }
.demo-tag-input--focused { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
.demo-tag { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.125rem 0.375rem; font-size: 0.75rem; font-weight: 500; color: #4338ca; background: #e0e7ff; border-radius: 0.25rem; }
.demo-tag button { font-size: 1rem; line-height: 1; background: none; border: none; cursor: pointer; color: #6366f1; padding: 0; }
.demo-tag button:hover { color: #4338ca; }
.demo-tag-input input { flex: 1; min-width: 4rem; padding: 0.125rem 0; font-size: 0.875rem; border: none; outline: none; background: transparent; }
</style>
