<template>
  <div class="demo-checkbox-group-showcase">
    <div class="demo-section">
      <span class="demo-label">Select your interests:</span>
      <div class="demo-checkbox-group">
        <label v-for="option in options" :key="option.value" class="demo-checkbox">
          <input
            type="checkbox"
            :value="option.value"
            :checked="selected.includes(option.value)"
            @change="toggleOption(option.value)"
          />
          <span class="demo-checkbox__box">
            <svg v-if="selected.includes(option.value)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </span>
          <span class="demo-checkbox__label">{{ option.label }}</span>
        </label>
      </div>
      <span class="demo-selected">Selected: {{ selected.join(', ') || 'None' }}</span>
    </div>

    <div class="demo-section">
      <span class="demo-label">Inline layout:</span>
      <div class="demo-checkbox-group demo-checkbox-group--inline">
        <label v-for="size in sizes" :key="size" class="demo-checkbox">
          <input
            type="checkbox"
            :value="size"
            :checked="selectedSizes.includes(size)"
            @change="toggleSize(size)"
          />
          <span class="demo-checkbox__box">
            <svg v-if="selectedSizes.includes(size)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </span>
          <span class="demo-checkbox__label">{{ size }}</span>
        </label>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const options = [
  { value: 'tech', label: 'Technology' },
  { value: 'design', label: 'Design' },
  { value: 'business', label: 'Business' },
  { value: 'marketing', label: 'Marketing' }
]
const selected = ref(['tech'])

const sizes = ['S', 'M', 'L', 'XL']
const selectedSizes = ref(['M', 'L'])

const toggleOption = (value) => {
  const idx = selected.value.indexOf(value)
  if (idx === -1) selected.value.push(value)
  else selected.value.splice(idx, 1)
}

const toggleSize = (value) => {
  const idx = selectedSizes.value.indexOf(value)
  if (idx === -1) selectedSizes.value.push(value)
  else selectedSizes.value.splice(idx, 1)
}
</script>

<style scoped>
.demo-checkbox-group-showcase {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  width: 100%;
}

.demo-section {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
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

.demo-checkbox-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.demo-checkbox-group--inline {
  flex-direction: row;
  flex-wrap: wrap;
  gap: 1rem;
}

.demo-checkbox {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
}

.demo-checkbox input {
  position: absolute;
  opacity: 0;
}

.demo-checkbox__box {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 1.25rem;
  height: 1.25rem;
  border: 2px solid #d1d5db;
  border-radius: 0.25rem;
  background: white;
  transition: all 0.15s;
}

.demo-checkbox__box svg {
  width: 0.75rem;
  height: 0.75rem;
  color: white;
}

.demo-checkbox input:checked + .demo-checkbox__box {
  background: #6366f1;
  border-color: #6366f1;
}

.demo-checkbox__label {
  font-size: 0.875rem;
  color: #374151;
}
</style>
