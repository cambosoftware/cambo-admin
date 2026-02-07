<template>
  <div :class="['demo-card', { 'demo-card--hoverable': hoverable, 'demo-card--bordered': bordered }]">
    <div v-if="$slots.header || title" class="demo-card__header">
      <slot name="header">
        <h3 class="demo-card__title">{{ title }}</h3>
        <p v-if="subtitle" class="demo-card__subtitle">{{ subtitle }}</p>
      </slot>
    </div>
    <div class="demo-card__body">
      <slot>Card content goes here</slot>
    </div>
    <div v-if="$slots.footer" class="demo-card__footer">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: { type: String, default: '' },
  subtitle: { type: String, default: '' },
  hoverable: { type: Boolean, default: false },
  bordered: { type: Boolean, default: true }
})
</script>

<style scoped>
.demo-card {
  background: white;
  border-radius: 0.5rem;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.demo-card--bordered {
  border: 1px solid #e5e7eb;
}

.demo-card--hoverable {
  transition: transform 0.2s, box-shadow 0.2s;
  cursor: pointer;
}

.demo-card--hoverable:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.demo-card__header {
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #e5e7eb;
}

.demo-card__title {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
  color: #111827;
}

.demo-card__subtitle {
  margin: 0.25rem 0 0;
  font-size: 0.875rem;
  color: #6b7280;
}

.demo-card__body {
  padding: 1.25rem;
}

.demo-card__footer {
  padding: 1rem 1.25rem;
  border-top: 1px solid #e5e7eb;
  background: #f9fafb;
}
</style>
