<template>
  <div class="demo-empty-state">
    <div class="demo-empty-state__icon">
      <slot name="icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
      </slot>
    </div>
    <h3 class="demo-empty-state__title">{{ title }}</h3>
    <p class="demo-empty-state__description">{{ description }}</p>
    <div v-if="$slots.action" class="demo-empty-state__action">
      <slot name="action"></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: { type: String, default: 'No data found' },
  description: { type: String, default: 'There are no items to display at the moment.' }
})
</script>

<style scoped>
.demo-empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 3rem 1.5rem;
  text-align: center;
}

.demo-empty-state__icon {
  width: 4rem;
  height: 4rem;
  margin-bottom: 1rem;
  color: #d1d5db;
}

.demo-empty-state__icon svg {
  width: 100%;
  height: 100%;
}

.demo-empty-state__title {
  margin: 0 0 0.5rem;
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
}

.demo-empty-state__description {
  margin: 0;
  font-size: 0.875rem;
  color: #6b7280;
  max-width: 24rem;
}

.demo-empty-state__action {
  margin-top: 1.5rem;
}
</style>
