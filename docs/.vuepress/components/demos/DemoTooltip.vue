<template>
  <div class="demo-tooltip-wrapper" @mouseenter="show = true" @mouseleave="show = false">
    <slot>Hover me</slot>
    <div
      v-show="show"
      :class="['demo-tooltip', `demo-tooltip--${position}`]"
    >
      {{ content }}
      <span class="demo-tooltip__arrow"></span>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  content: { type: String, default: 'Tooltip text' },
  position: { type: String, default: 'top' }
})

const show = ref(false)
</script>

<style scoped>
.demo-tooltip-wrapper {
  position: relative;
  display: inline-block;
}

.demo-tooltip {
  position: absolute;
  z-index: 50;
  padding: 0.375rem 0.75rem;
  font-size: 0.75rem;
  font-weight: 500;
  color: white;
  background: #1f2937;
  border-radius: 0.375rem;
  white-space: nowrap;
  pointer-events: none;
}

.demo-tooltip--top {
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  margin-bottom: 0.5rem;
}

.demo-tooltip--bottom {
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  margin-top: 0.5rem;
}

.demo-tooltip--left {
  right: 100%;
  top: 50%;
  transform: translateY(-50%);
  margin-right: 0.5rem;
}

.demo-tooltip--right {
  left: 100%;
  top: 50%;
  transform: translateY(-50%);
  margin-left: 0.5rem;
}

.demo-tooltip__arrow {
  position: absolute;
  width: 0.5rem;
  height: 0.5rem;
  background: #1f2937;
  transform: rotate(45deg);
}

.demo-tooltip--top .demo-tooltip__arrow {
  bottom: -0.25rem;
  left: 50%;
  margin-left: -0.25rem;
}

.demo-tooltip--bottom .demo-tooltip__arrow {
  top: -0.25rem;
  left: 50%;
  margin-left: -0.25rem;
}

.demo-tooltip--left .demo-tooltip__arrow {
  right: -0.25rem;
  top: 50%;
  margin-top: -0.25rem;
}

.demo-tooltip--right .demo-tooltip__arrow {
  left: -0.25rem;
  top: 50%;
  margin-top: -0.25rem;
}
</style>
