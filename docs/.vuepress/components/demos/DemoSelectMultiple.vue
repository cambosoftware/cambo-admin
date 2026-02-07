<template>
  <div class="demo-select-multiple-showcase">
    <span class="demo-label">Select technologies:</span>
    <div class="demo-select-multiple">
      <div class="demo-select-multiple__trigger" @click="isOpen = !isOpen">
        <div class="demo-select-multiple__tags">
          <span v-for="val in selected" :key="val" class="demo-select-multiple__tag">
            {{ getLabel(val) }}
            <button @click.stop="removeValue(val)">×</button>
          </span>
          <span v-if="selected.length === 0" class="demo-select-multiple__placeholder">
            Select multiple...
          </span>
        </div>
        <svg class="demo-select-multiple__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="6 9 12 15 18 9"/>
        </svg>
      </div>
      <div v-if="isOpen" class="demo-select-multiple__dropdown">
        <div
          v-for="option in options"
          :key="option.value"
          :class="['demo-select-multiple__option', { 'demo-select-multiple__option--selected': selected.includes(option.value) }]"
          @click="toggleOption(option.value)"
        >
          <span class="demo-select-multiple__checkbox">
            <svg v-if="selected.includes(option.value)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </span>
          {{ option.label }}
        </div>
      </div>
    </div>
    <span class="demo-selected">Selected: {{ selected.join(', ') || 'None' }}</span>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const options = [
  { value: 'vue', label: 'Vue.js' },
  { value: 'react', label: 'React' },
  { value: 'angular', label: 'Angular' },
  { value: 'svelte', label: 'Svelte' },
  { value: 'nuxt', label: 'Nuxt' },
  { value: 'next', label: 'Next.js' }
]

const selected = ref(['vue', 'nuxt'])
const isOpen = ref(false)

const getLabel = (value) => options.find(o => o.value === value)?.label || value

const toggleOption = (value) => {
  const idx = selected.value.indexOf(value)
  if (idx === -1) selected.value.push(value)
  else selected.value.splice(idx, 1)
}

const removeValue = (value) => {
  selected.value = selected.value.filter(v => v !== value)
}
</script>

<style scoped>
.demo-select-multiple-showcase {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  width: 100%;
  max-width: 20rem;
}

.demo-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.demo-selected {
  font-size: 0.75rem;
  color: #6b7280;
}

.demo-select-multiple {
  position: relative;
}

.demo-select-multiple__trigger {
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 2.5rem;
  padding: 0.25rem 0.5rem;
  padding-right: 0.75rem;
  background: white;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  cursor: pointer;
}

.demo-select-multiple__tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.25rem;
  flex: 1;
}

.demo-select-multiple__tag {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.125rem 0.375rem;
  font-size: 0.75rem;
  background: #e0e7ff;
  color: #4338ca;
  border-radius: 0.25rem;
}

.demo-select-multiple__tag button {
  font-size: 1rem;
  line-height: 1;
  background: none;
  border: none;
  cursor: pointer;
  color: #6366f1;
}

.demo-select-multiple__placeholder {
  font-size: 0.875rem;
  color: #9ca3af;
  padding: 0.25rem;
}

.demo-select-multiple__arrow {
  width: 1rem;
  height: 1rem;
  color: #6b7280;
}

.demo-select-multiple__dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  margin-top: 0.25rem;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  z-index: 50;
  max-height: 12rem;
  overflow-y: auto;
}

.demo-select-multiple__option {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  cursor: pointer;
}

.demo-select-multiple__option:hover {
  background: #f3f4f6;
}

.demo-select-multiple__option--selected {
  background: #eef2ff;
}

.demo-select-multiple__checkbox {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 1rem;
  height: 1rem;
  border: 2px solid #d1d5db;
  border-radius: 0.25rem;
  background: white;
}

.demo-select-multiple__option--selected .demo-select-multiple__checkbox {
  background: #6366f1;
  border-color: #6366f1;
}

.demo-select-multiple__checkbox svg {
  width: 0.625rem;
  height: 0.625rem;
  color: white;
}
</style>
