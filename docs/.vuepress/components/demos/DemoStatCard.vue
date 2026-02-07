<template>
  <div :class="['demo-stat-card', { 'demo-stat-card--with-trend': trend }]">
    <div class="demo-stat-card__icon" v-if="$slots.icon">
      <slot name="icon"></slot>
    </div>
    <div class="demo-stat-card__content">
      <div class="demo-stat-card__label">{{ label }}</div>
      <div class="demo-stat-card__value">{{ formattedValue }}</div>
      <div v-if="trend" :class="['demo-stat-card__trend', `demo-stat-card__trend--${trendDirection}`]">
        <svg v-if="trendDirection === 'up'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
          <polyline points="17 6 23 6 23 12"/>
        </svg>
        <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/>
          <polyline points="17 18 23 18 23 12"/>
        </svg>
        {{ trend }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: { type: String, default: 'Total Users' },
  value: { type: [Number, String], default: 1234 },
  trend: { type: String, default: '' },
  trendDirection: { type: String, default: 'up' },
  prefix: { type: String, default: '' },
  suffix: { type: String, default: '' }
})

const formattedValue = computed(() => {
  let val = props.value
  if (typeof val === 'number') {
    val = val.toLocaleString()
  }
  return `${props.prefix}${val}${props.suffix}`
})
</script>

<style scoped>
.demo-stat-card {
  display: flex;
  gap: 1rem;
  padding: 1.25rem;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 0.75rem;
}

.demo-stat-card__icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 3rem;
  height: 3rem;
  background: #eff6ff;
  border-radius: 0.5rem;
  color: #6366f1;
}
.demo-stat-card__icon svg {
  width: 1.5rem;
  height: 1.5rem;
}

.demo-stat-card__content {
  flex: 1;
}

.demo-stat-card__label {
  font-size: 0.875rem;
  color: #6b7280;
}

.demo-stat-card__value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
  margin-top: 0.25rem;
}

.demo-stat-card__trend {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.75rem;
  font-weight: 500;
  margin-top: 0.25rem;
}
.demo-stat-card__trend svg {
  width: 1rem;
  height: 1rem;
}

.demo-stat-card__trend--up { color: #10b981; }
.demo-stat-card__trend--down { color: #ef4444; }
</style>
