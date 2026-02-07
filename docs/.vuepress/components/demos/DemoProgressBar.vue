<template>
  <div class="demo-progress">
    <div v-if="showLabel" class="demo-progress__label">
      <span>{{ label }}</span>
      <span>{{ value }}%</span>
    </div>
    <div :class="['demo-progress__track', `demo-progress__track--${size}`]">
      <div
        :class="[
          'demo-progress__bar',
          `demo-progress__bar--${color}`,
          { 'demo-progress__bar--striped': striped, 'demo-progress__bar--animated': animated }
        ]"
        :style="{ width: `${value}%` }"
      ></div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  value: { type: Number, default: 50 },
  label: { type: String, default: 'Progress' },
  showLabel: { type: Boolean, default: true },
  color: { type: String, default: 'primary' },
  size: { type: String, default: 'md' },
  striped: { type: Boolean, default: false },
  animated: { type: Boolean, default: false }
})
</script>

<style scoped>
.demo-progress {
  width: 100%;
  max-width: 20rem;
}

.demo-progress__label {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
  color: #374151;
}

.demo-progress__track {
  width: 100%;
  background: #e5e7eb;
  border-radius: 9999px;
  overflow: hidden;
}

.demo-progress__track--sm { height: 0.25rem; }
.demo-progress__track--md { height: 0.5rem; }
.demo-progress__track--lg { height: 0.75rem; }

.demo-progress__bar {
  height: 100%;
  border-radius: 9999px;
  transition: width 0.3s ease;
}

.demo-progress__bar--primary { background: #6366f1; }
.demo-progress__bar--success { background: #10b981; }
.demo-progress__bar--warning { background: #f59e0b; }
.demo-progress__bar--danger { background: #ef4444; }

.demo-progress__bar--striped {
  background-image: linear-gradient(
    45deg,
    rgba(255,255,255,0.15) 25%,
    transparent 25%,
    transparent 50%,
    rgba(255,255,255,0.15) 50%,
    rgba(255,255,255,0.15) 75%,
    transparent 75%,
    transparent
  );
  background-size: 1rem 1rem;
}

.demo-progress__bar--animated {
  animation: progress-stripes 1s linear infinite;
}

@keyframes progress-stripes {
  from { background-position: 1rem 0; }
  to { background-position: 0 0; }
}
</style>
