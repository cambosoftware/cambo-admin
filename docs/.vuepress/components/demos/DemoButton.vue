<template>
  <button
    :class="[
      'demo-button',
      `demo-button--${variant}`,
      `demo-button--${size}`,
      {
        'demo-button--outline': outline,
        'demo-button--pill': pill,
        'demo-button--block': block,
        'demo-button--loading': loading,
        'demo-button--disabled': disabled
      }
    ]"
    :disabled="disabled || loading"
  >
    <svg v-if="loading" class="demo-button__spinner" viewBox="0 0 24 24" fill="none">
      <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.25"/>
      <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
    </svg>
    <slot>{{ loading ? 'Loading...' : 'Button' }}</slot>
  </button>
</template>

<script setup>
defineProps({
  variant: { type: String, default: 'primary' },
  size: { type: String, default: 'md' },
  outline: { type: Boolean, default: false },
  pill: { type: Boolean, default: false },
  block: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false }
})
</script>

<style scoped>
.demo-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  font-weight: 500;
  border: 1px solid transparent;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: all 0.15s ease;
}

.demo-button--primary { background: #6366f1; color: white; }
.demo-button--primary:hover { background: #4f46e5; }
.demo-button--secondary { background: #6b7280; color: white; }
.demo-button--secondary:hover { background: #4b5563; }
.demo-button--success { background: #10b981; color: white; }
.demo-button--success:hover { background: #059669; }
.demo-button--danger { background: #ef4444; color: white; }
.demo-button--danger:hover { background: #dc2626; }
.demo-button--warning { background: #f59e0b; color: white; }
.demo-button--warning:hover { background: #d97706; }
.demo-button--ghost { background: transparent; color: #6b7280; }
.demo-button--ghost:hover { background: #f3f4f6; }

.demo-button--outline { background: transparent !important; }
.demo-button--outline.demo-button--primary { border-color: #6366f1; color: #6366f1; }
.demo-button--outline.demo-button--secondary { border-color: #6b7280; color: #6b7280; }
.demo-button--outline.demo-button--success { border-color: #10b981; color: #10b981; }
.demo-button--outline.demo-button--danger { border-color: #ef4444; color: #ef4444; }

.demo-button--xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
.demo-button--sm { padding: 0.375rem 0.75rem; font-size: 0.875rem; }
.demo-button--md { padding: 0.5rem 1rem; font-size: 0.875rem; }
.demo-button--lg { padding: 0.625rem 1.25rem; font-size: 1rem; }

.demo-button--pill { border-radius: 9999px; }
.demo-button--block { width: 100%; }
.demo-button--disabled { opacity: 0.5; cursor: not-allowed; pointer-events: none; }
.demo-button--loading { cursor: wait; }

.demo-button__spinner {
  width: 1em;
  height: 1em;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
