<template>
  <div
    :class="[
      'demo-alert',
      `demo-alert--${variant}`,
      { 'demo-alert--dismissible': dismissible }
    ]"
    v-if="!dismissed"
  >
    <div class="demo-alert__icon">
      <svg v-if="variant === 'success'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      <svg v-else-if="variant === 'danger'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
      <svg v-else-if="variant === 'warning'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    </div>
    <div class="demo-alert__content">
      <div v-if="title" class="demo-alert__title">{{ title }}</div>
      <div class="demo-alert__message"><slot>This is an alert message.</slot></div>
    </div>
    <button v-if="dismissible" class="demo-alert__close" @click="dismissed = true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  variant: { type: String, default: 'info' },
  title: { type: String, default: '' },
  dismissible: { type: Boolean, default: false }
})

const dismissed = ref(false)
</script>

<style scoped>
.demo-alert {
  display: flex;
  gap: 0.75rem;
  padding: 1rem;
  border-radius: 0.5rem;
  border: 1px solid;
}

.demo-alert--info { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }
.demo-alert--success { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
.demo-alert--warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }
.demo-alert--danger { background: #fef2f2; border-color: #fecaca; color: #991b1b; }

.demo-alert__icon {
  flex-shrink: 0;
  width: 1.25rem;
  height: 1.25rem;
}
.demo-alert__icon svg { width: 100%; height: 100%; }

.demo-alert__content { flex: 1; }
.demo-alert__title { font-weight: 600; margin-bottom: 0.25rem; }
.demo-alert__message { font-size: 0.875rem; }

.demo-alert__close {
  flex-shrink: 0;
  width: 1.25rem;
  height: 1.25rem;
  padding: 0;
  background: transparent;
  border: none;
  color: currentColor;
  opacity: 0.5;
  cursor: pointer;
}
.demo-alert__close:hover { opacity: 1; }
.demo-alert__close svg { width: 100%; height: 100%; }
</style>
