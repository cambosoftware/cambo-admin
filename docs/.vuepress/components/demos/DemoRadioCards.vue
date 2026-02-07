<template>
  <div class="demo-radio-cards-showcase">
    <span class="demo-label">Choose a plan:</span>
    <div class="demo-radio-cards">
      <label
        v-for="plan in plans"
        :key="plan.value"
        :class="['demo-radio-card', { 'demo-radio-card--selected': selected === plan.value }]"
      >
        <input
          type="radio"
          name="plan"
          :value="plan.value"
          :checked="selected === plan.value"
          @change="selected = plan.value"
        />
        <div class="demo-radio-card__icon">{{ plan.icon }}</div>
        <div class="demo-radio-card__content">
          <span class="demo-radio-card__title">{{ plan.title }}</span>
          <span class="demo-radio-card__price">{{ plan.price }}</span>
          <span class="demo-radio-card__desc">{{ plan.description }}</span>
        </div>
        <div class="demo-radio-card__check">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </div>
      </label>
    </div>
    <span class="demo-selected">Selected: {{ selected }}</span>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const plans = [
  { value: 'starter', title: 'Starter', price: '$9/mo', description: 'For individuals', icon: '🚀' },
  { value: 'pro', title: 'Pro', price: '$29/mo', description: 'For small teams', icon: '⭐' },
  { value: 'enterprise', title: 'Enterprise', price: '$99/mo', description: 'For large orgs', icon: '🏢' }
]

const selected = ref('pro')
</script>

<style scoped>
.demo-radio-cards-showcase {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  width: 100%;
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

.demo-radio-cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0.75rem;
}

.demo-radio-card {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 0.5rem;
  background: white;
  cursor: pointer;
  transition: all 0.15s;
  text-align: center;
}

.demo-radio-card:hover {
  border-color: #c7d2fe;
}

.demo-radio-card--selected {
  border-color: #6366f1;
  background: #eef2ff;
}

.demo-radio-card input {
  position: absolute;
  opacity: 0;
}

.demo-radio-card__icon {
  font-size: 1.5rem;
}

.demo-radio-card__title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #374151;
}

.demo-radio-card__price {
  font-size: 1rem;
  font-weight: 700;
  color: #6366f1;
}

.demo-radio-card__desc {
  font-size: 0.75rem;
  color: #6b7280;
}

.demo-radio-card__check {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  width: 1.25rem;
  height: 1.25rem;
  border-radius: 50%;
  background: #6366f1;
  display: none;
  align-items: center;
  justify-content: center;
}

.demo-radio-card__check svg {
  width: 0.75rem;
  height: 0.75rem;
  color: white;
}

.demo-radio-card--selected .demo-radio-card__check {
  display: flex;
}
</style>
