<template>
  <div
    :class="[
      'demo-toast',
      `demo-toast--${variant}`,
      { 'demo-toast--visible': visible }
    ]"
  >
    <div class="demo-toast__icon">
      <svg v-if="variant === 'success'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      <svg v-else-if="variant === 'error'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
      <svg v-else-if="variant === 'warning'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    </div>
    <div class="demo-toast__content">
      <div v-if="title" class="demo-toast__title">{{ title }}</div>
      <div class="demo-toast__message"><slot>Toast message</slot></div>
    </div>
    <button v-if="closable" class="demo-toast__close" @click="$emit('close')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
</template>

<script setup>
defineProps({
  variant: { type: String, default: 'info' },
  title: { type: String, default: '' },
  closable: { type: Boolean, default: true },
  visible: { type: Boolean, default: true }
})

defineEmits(['close'])
</script>

<style scoped>
.demo-toast {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  padding: 1rem;
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
  border-left: 4px solid;
  max-width: 24rem;
  opacity: 0;
  transform: translateX(100%);
  transition: all 0.3s ease;
}

.demo-toast--visible {
  opacity: 1;
  transform: translateX(0);
}

.demo-toast--info { border-left-color: #3b82f6; }
.demo-toast--success { border-left-color: #10b981; }
.demo-toast--warning { border-left-color: #f59e0b; }
.demo-toast--error { border-left-color: #ef4444; }

.demo-toast__icon {
  flex-shrink: 0;
  width: 1.25rem;
  height: 1.25rem;
}
.demo-toast__icon svg { width: 100%; height: 100%; }

.demo-toast--info .demo-toast__icon { color: #3b82f6; }
.demo-toast--success .demo-toast__icon { color: #10b981; }
.demo-toast--warning .demo-toast__icon { color: #f59e0b; }
.demo-toast--error .demo-toast__icon { color: #ef4444; }

.demo-toast__content { flex: 1; }
.demo-toast__title { font-weight: 600; color: #111827; margin-bottom: 0.25rem; }
.demo-toast__message { font-size: 0.875rem; color: #6b7280; }

.demo-toast__close {
  flex-shrink: 0;
  padding: 0;
  background: transparent;
  border: none;
  color: #9ca3af;
  cursor: pointer;
}
.demo-toast__close:hover { color: #6b7280; }
.demo-toast__close svg { width: 1rem; height: 1rem; }
</style>
