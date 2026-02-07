<template>
  <div
    :class="[
      'demo-skeleton',
      `demo-skeleton--${variant}`,
      { 'demo-skeleton--animated': animated }
    ]"
    :style="skeletonStyle"
  >
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: { type: String, default: 'text' },
  width: { type: String, default: '' },
  height: { type: String, default: '' },
  animated: { type: Boolean, default: true }
})

const skeletonStyle = computed(() => {
  const style = {}
  if (props.width) style.width = props.width
  if (props.height) style.height = props.height
  return style
})
</script>

<style scoped>
.demo-skeleton {
  background: #e5e7eb;
}

.demo-skeleton--text {
  width: 100%;
  height: 1rem;
  border-radius: 0.25rem;
}

.demo-skeleton--circular {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 9999px;
}

.demo-skeleton--rectangular {
  width: 100%;
  height: 8rem;
  border-radius: 0;
}

.demo-skeleton--rounded {
  width: 100%;
  height: 8rem;
  border-radius: 0.5rem;
}

.demo-skeleton--animated {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}
</style>
