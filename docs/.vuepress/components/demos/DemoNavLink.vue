<template>
  <a
    :href="href"
    :class="[
      'demo-nav-link',
      { 'demo-nav-link--active': active, 'demo-nav-link--disabled': disabled }
    ]"
    @click.prevent
  >
    <span v-if="$slots.icon" class="demo-nav-link__icon">
      <slot name="icon"></slot>
    </span>
    <span class="demo-nav-link__text"><slot>Link</slot></span>
    <span v-if="badge" class="demo-nav-link__badge">{{ badge }}</span>
  </a>
</template>

<script setup>
defineProps({
  href: { type: String, default: '#' },
  active: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  badge: { type: [String, Number], default: '' }
})
</script>

<style scoped>
.demo-nav-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.625rem 1rem;
  font-size: 0.875rem;
  color: #6b7280;
  text-decoration: none;
  border-radius: 0.375rem;
  transition: all 0.15s;
}

.demo-nav-link:hover {
  color: #111827;
  background: #f3f4f6;
}

.demo-nav-link--active {
  color: #6366f1;
  background: #eff6ff;
}

.demo-nav-link--disabled {
  opacity: 0.5;
  pointer-events: none;
}

.demo-nav-link__icon {
  display: flex;
  width: 1.25rem;
  height: 1.25rem;
  color: currentColor;
}
.demo-nav-link__icon svg {
  width: 100%;
  height: 100%;
}

.demo-nav-link__text {
  flex: 1;
}

.demo-nav-link__badge {
  padding: 0.125rem 0.5rem;
  font-size: 0.75rem;
  font-weight: 500;
  background: #e5e7eb;
  border-radius: 9999px;
}

.demo-nav-link--active .demo-nav-link__badge {
  background: #6366f1;
  color: white;
}
</style>
