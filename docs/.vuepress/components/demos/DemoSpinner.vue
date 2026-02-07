<template>
  <div
    :class="[
      'demo-spinner',
      `demo-spinner--${size}`,
      `demo-spinner--${color}`
    ]"
  >
    <svg viewBox="0 0 24 24" fill="none">
      <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.25"/>
      <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
    </svg>
    <span v-if="label" class="demo-spinner__label">{{ label }}</span>
  </div>
</template>

<script setup>
defineProps({
  size: { type: String, default: 'md' },
  color: { type: String, default: 'primary' },
  label: { type: String, default: '' }
})
</script>

<style scoped>
.demo-spinner {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.demo-spinner svg {
  animation: spin 1s linear infinite;
}

.demo-spinner--xs svg { width: 0.75rem; height: 0.75rem; }
.demo-spinner--sm svg { width: 1rem; height: 1rem; }
.demo-spinner--md svg { width: 1.5rem; height: 1.5rem; }
.demo-spinner--lg svg { width: 2rem; height: 2rem; }
.demo-spinner--xl svg { width: 3rem; height: 3rem; }

.demo-spinner--primary { color: #6366f1; }
.demo-spinner--secondary { color: #6b7280; }
.demo-spinner--success { color: #10b981; }
.demo-spinner--danger { color: #ef4444; }
.demo-spinner--warning { color: #f59e0b; }
.demo-spinner--white { color: white; }

.demo-spinner__label {
  font-size: 0.875rem;
  color: inherit;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
