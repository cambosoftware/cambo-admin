<template>
  <div class="demo-toggle-showcase">
    <!-- Basic Toggle -->
    <div class="demo-toggle-item">
      <span class="demo-toggle-label">View mode:</span>
      <div class="demo-toggle">
        <button
          v-for="option in viewOptions"
          :key="option.value"
          :class="['demo-toggle__button', { 'demo-toggle__button--active': view === option.value }]"
          @click="view = option.value"
        >
          {{ option.label }}
        </button>
      </div>
      <span class="demo-toggle-value">Selected: {{ view }}</span>
    </div>

    <!-- Toggle with icons -->
    <div class="demo-toggle-item">
      <span class="demo-toggle-label">Theme:</span>
      <div class="demo-toggle">
        <button
          v-for="option in themeOptions"
          :key="option.value"
          :class="['demo-toggle__button', { 'demo-toggle__button--active': theme === option.value }]"
          @click="theme = option.value"
        >
          <span class="demo-toggle__icon">{{ option.icon }}</span>
          {{ option.label }}
        </button>
      </div>
    </div>

    <!-- Boolean Toggle -->
    <div class="demo-toggle-item">
      <span class="demo-toggle-label">Billing:</span>
      <div class="demo-toggle">
        <button
          :class="['demo-toggle__button', { 'demo-toggle__button--active': billing === 'monthly' }]"
          @click="billing = 'monthly'"
        >
          Monthly
        </button>
        <button
          :class="['demo-toggle__button', { 'demo-toggle__button--active': billing === 'yearly' }]"
          @click="billing = 'yearly'"
        >
          Yearly (Save 20%)
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const view = ref('grid')
const viewOptions = [
  { value: 'grid', label: 'Grid' },
  { value: 'list', label: 'List' },
  { value: 'table', label: 'Table' }
]

const theme = ref('light')
const themeOptions = [
  { value: 'light', label: 'Light', icon: '☀️' },
  { value: 'dark', label: 'Dark', icon: '🌙' },
  { value: 'system', label: 'Auto', icon: '💻' }
]

const billing = ref('monthly')
</script>

<style scoped>
.demo-toggle-showcase {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  width: 100%;
}

.demo-toggle-item {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  align-items: flex-start;
}

.demo-toggle-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.demo-toggle-value {
  font-size: 0.75rem;
  color: #6b7280;
}

.demo-toggle {
  display: inline-flex;
  background: #f3f4f6;
  border-radius: 0.5rem;
  padding: 0.25rem;
}

.demo-toggle__button {
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #6b7280;
  background: transparent;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: all 0.15s;
}

.demo-toggle__button:hover {
  color: #374151;
}

.demo-toggle__button--active {
  color: #374151;
  background: white;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.demo-toggle__icon {
  font-size: 1rem;
}
</style>
