<template>
  <div
    :class="[
      'demo-divider',
      `demo-divider--${orientation}`,
      { 'demo-divider--dashed': dashed }
    ]"
  >
    <span v-if="label && orientation === 'horizontal'" class="demo-divider__label">
      {{ label }}
    </span>
  </div>
</template>

<script setup>
defineProps({
  orientation: { type: String, default: 'horizontal' },
  label: { type: String, default: '' },
  dashed: { type: Boolean, default: false }
})
</script>

<style scoped>
.demo-divider--horizontal {
  display: flex;
  align-items: center;
  width: 100%;
  margin: 1rem 0;
}

.demo-divider--horizontal::before,
.demo-divider--horizontal::after {
  content: '';
  flex: 1;
  height: 1px;
  background: #e5e7eb;
}

.demo-divider--horizontal.demo-divider--dashed::before,
.demo-divider--horizontal.demo-divider--dashed::after {
  background: transparent;
  border-top: 1px dashed #e5e7eb;
}

.demo-divider__label {
  padding: 0 1rem;
  font-size: 0.875rem;
  color: #6b7280;
  white-space: nowrap;
}

.demo-divider--horizontal:not(:has(.demo-divider__label))::after {
  display: none;
}

.demo-divider--vertical {
  display: inline-block;
  width: 1px;
  height: 1.5rem;
  margin: 0 0.75rem;
  background: #e5e7eb;
  vertical-align: middle;
}

.demo-divider--vertical.demo-divider--dashed {
  background: transparent;
  border-left: 1px dashed #e5e7eb;
}
</style>
