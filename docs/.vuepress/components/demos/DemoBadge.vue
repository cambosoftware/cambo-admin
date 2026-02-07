<template>
  <span
    :class="[
      'demo-badge',
      `demo-badge--${variant}`,
      `demo-badge--${size}`,
      {
        'demo-badge--outline': outline,
        'demo-badge--pill': pill,
        'demo-badge--dot': dot
      }
    ]"
  >
    <span v-if="dot" class="demo-badge__dot"></span>
    <slot>Badge</slot>
    <button v-if="removable" class="demo-badge__remove" @click="$emit('remove')">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M18 6L6 18M6 6l12 12"/>
      </svg>
    </button>
  </span>
</template>

<script setup>
defineProps({
  variant: { type: String, default: 'default' },
  size: { type: String, default: 'md' },
  outline: { type: Boolean, default: false },
  pill: { type: Boolean, default: false },
  dot: { type: Boolean, default: false },
  removable: { type: Boolean, default: false }
})

defineEmits(['remove'])
</script>

<style scoped>
.demo-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  font-weight: 500;
  border-radius: 0.25rem;
  border: 1px solid transparent;
}

.demo-badge--default { background: #e5e7eb; color: #374151; }
.demo-badge--primary { background: #e0e7ff; color: #4338ca; }
.demo-badge--success { background: #d1fae5; color: #065f46; }
.demo-badge--danger { background: #fee2e2; color: #991b1b; }
.demo-badge--warning { background: #fef3c7; color: #92400e; }
.demo-badge--info { background: #dbeafe; color: #1e40af; }

.demo-badge--sm { padding: 0.125rem 0.375rem; font-size: 0.75rem; }
.demo-badge--md { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
.demo-badge--lg { padding: 0.375rem 0.75rem; font-size: 0.875rem; }

.demo-badge--pill { border-radius: 9999px; }

.demo-badge--outline { background: transparent !important; }
.demo-badge--outline.demo-badge--primary { border-color: #6366f1; color: #6366f1; }
.demo-badge--outline.demo-badge--success { border-color: #10b981; color: #10b981; }
.demo-badge--outline.demo-badge--danger { border-color: #ef4444; color: #ef4444; }

.demo-badge__dot {
  width: 0.5rem;
  height: 0.5rem;
  border-radius: 50%;
  background: currentColor;
}

.demo-badge__remove {
  display: flex;
  padding: 0;
  margin-left: 0.125rem;
  background: transparent;
  border: none;
  color: currentColor;
  opacity: 0.6;
  cursor: pointer;
}
.demo-badge__remove:hover { opacity: 1; }
</style>
