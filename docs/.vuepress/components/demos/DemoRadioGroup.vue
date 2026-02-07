<template>
  <div class="demo-radio-group-showcase">
    <div class="demo-section">
      <span class="demo-label">Select a plan:</span>
      <div class="demo-radio-group">
        <label v-for="plan in plans" :key="plan.value" class="demo-radio">
          <input
            type="radio"
            name="plan"
            :value="plan.value"
            :checked="selectedPlan === plan.value"
            @change="selectedPlan = plan.value"
          />
          <span class="demo-radio__circle"></span>
          <span class="demo-radio__label">{{ plan.label }}</span>
        </label>
      </div>
      <span class="demo-selected">Selected: {{ selectedPlan }}</span>
    </div>

    <div class="demo-section">
      <span class="demo-label">Inline layout:</span>
      <div class="demo-radio-group demo-radio-group--inline">
        <label v-for="color in colors" :key="color" class="demo-radio">
          <input
            type="radio"
            name="color"
            :value="color"
            :checked="selectedColor === color"
            @change="selectedColor = color"
          />
          <span class="demo-radio__circle"></span>
          <span class="demo-radio__label">{{ color }}</span>
        </label>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const plans = [
  { value: 'free', label: 'Free - $0/month' },
  { value: 'pro', label: 'Pro - $19/month' },
  { value: 'enterprise', label: 'Enterprise - $99/month' }
]
const selectedPlan = ref('pro')

const colors = ['Red', 'Blue', 'Green', 'Yellow']
const selectedColor = ref('Blue')
</script>

<style scoped>
.demo-radio-group-showcase {
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

.demo-radio-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.demo-radio-group--inline {
  flex-direction: row;
  flex-wrap: wrap;
  gap: 1rem;
}

.demo-radio {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
}

.demo-radio input {
  position: absolute;
  opacity: 0;
}

.demo-radio__circle {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 1.25rem;
  height: 1.25rem;
  border: 2px solid #d1d5db;
  border-radius: 50%;
  background: white;
  transition: all 0.15s;
}

.demo-radio__circle::after {
  content: '';
  width: 0.5rem;
  height: 0.5rem;
  border-radius: 50%;
  background: white;
  transform: scale(0);
  transition: transform 0.15s;
}

.demo-radio input:checked + .demo-radio__circle {
  border-color: #6366f1;
  background: #6366f1;
}

.demo-radio input:checked + .demo-radio__circle::after {
  transform: scale(1);
}

.demo-radio__label {
  font-size: 0.875rem;
  color: #374151;
}
</style>
